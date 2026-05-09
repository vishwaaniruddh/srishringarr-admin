<?php

namespace Api\V1\Models;

use Api\V1\Core\Database;

class BaseModel {
    protected $db;
    protected $table;
    protected $connectionType = 'default';

    public function __construct($connectionType = null) {
        if ($connectionType) $this->connectionType = $connectionType;
        $this->db = Database::getInstance()->getConnection($this->connectionType);
    }

    public function all() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getDb() {
        return $this->db;
    }
}
