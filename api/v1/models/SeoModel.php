<?php

namespace Api\V1\Models;

use PDO;

class SeoModel extends BaseModel {
    public function __construct($connectionType = null) {
        parent::__construct($connectionType);
        $this->ensureSchema();
    }

    private function ensureSchema() {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS seo_meta (
                id INT AUTO_INCREMENT PRIMARY KEY,
                page_type ENUM('page', 'product', 'garment', 'category', 'jewel_category') NOT NULL,
                entity_id INT DEFAULT NULL,
                url_slug VARCHAR(255) DEFAULT NULL,
                meta_title VARCHAR(255) DEFAULT NULL,
                meta_description TEXT,
                meta_keywords TEXT,
                focus_keyword VARCHAR(255) DEFAULT NULL,
                seo_score INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX seo_lookup_idx (page_type, entity_id),
                INDEX seo_slug_idx (page_type, url_slug)
            )
        ");

        $this->db->exec("
            CREATE TABLE IF NOT EXISTS seo_config (
                id INT AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT DEFAULT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");

        $stmt = $this->db->prepare("
            INSERT INTO seo_config (setting_key, setting_value)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE setting_value = setting_value
        ");
        foreach ([
            'site_name' => 'Sri Shringarr',
            'title_separator' => '|',
            'default_og_image' => 'main_logo.png'
        ] as $key => $value) {
            $stmt->execute([$key, $value]);
        }
    }
    
    public function getMetadata($pageType, $entityId = null, $urlSlug = null) {
        $sql = "SELECT * FROM seo_meta WHERE page_type = ?";
        $params = [$pageType];

        if ($entityId !== null) {
            $sql .= " AND entity_id = ?";
            $params[] = $entityId;
        } elseif ($urlSlug !== null) {
            $sql .= " AND url_slug = ? ORDER BY id DESC";
            $params[] = $urlSlug;
        } else {
            $sql .= " AND entity_id IS NULL AND url_slug IS NULL";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function getMetadataById($id) {
        $stmt = $this->db->prepare("SELECT * FROM seo_meta WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function getAllMetadata($pageType) {
        $sql = "SELECT * FROM seo_meta WHERE page_type = ?";
        if ($pageType === 'page') {
            $sql .= " AND url_slug IS NOT NULL AND url_slug <> '' ORDER BY id DESC";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pageType]);
        $rows = $stmt->fetchAll();

        if ($pageType !== 'page') {
            return $rows;
        }

        $bySlug = [];
        foreach ($rows as $row) {
            if (!isset($bySlug[$row['url_slug']])) {
                $bySlug[$row['url_slug']] = $row;
            }
        }

        return array_values($bySlug);
    }

    public function updateMetadata($data) {
        $pageType = $data['page_type'];
        $entityId = isset($data['entity_id']) && $data['entity_id'] !== '' ? $data['entity_id'] : null;
        $urlSlug = $data['url_slug'] ?? null;
        $allowedTypes = ['page', 'product', 'garment', 'category', 'jewel_category'];

        if (!in_array($pageType, $allowedTypes, true)) {
            return false;
        }
        
        // Check if exists
        $existing = $this->getMetadata($pageType, $entityId, $urlSlug);
        
        if ($existing) {
            $fields = ['meta_title', 'meta_description', 'meta_keywords', 'focus_keyword', 'seo_score', 'url_slug'];
            $updates = [];
            $params = [];
            
            foreach ($fields as $f) {
                if (isset($data[$f])) {
                    $updates[] = "$f = ?";
                    $params[] = $data[$f];
                }
            }
            
            if (empty($updates)) return true;
            
            $params[] = $existing['id'];
            $sql = "UPDATE seo_meta SET " . implode(', ', $updates) . " WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } else {
            $fields = ['page_type', 'entity_id', 'url_slug', 'meta_title', 'meta_description', 'meta_keywords', 'focus_keyword', 'seo_score'];
            $insertData = [];

            foreach ($fields as $field) {
                if (array_key_exists($field, $data)) {
                    $insertData[$field] = $data[$field] === '' && $field === 'entity_id' ? null : $data[$field];
                }
            }
            
            $fields = array_keys($insertData);
            $placeholders = array_fill(0, count($fields), '?');
            $sql = "INSERT INTO seo_meta (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(array_values($insertData));
        }
    }

    public function getSettings() {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM seo_config");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function updateSettings($settings) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO seo_config (setting_key, setting_value)
                VALUES (?, ?)
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)
            ");
            foreach ($settings as $key => $value) {
                $stmt->execute([$key, $value]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
