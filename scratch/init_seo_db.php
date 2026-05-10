<?php
$_SERVER['HTTP_HOST'] = 'localhost'; // Force local detection for CLI
require_once 'c:/xampp/htdocs/sri/config.php';

$sql = [
    "CREATE TABLE IF NOT EXISTS seo_meta (
        id INT AUTO_INCREMENT PRIMARY KEY,
        page_type ENUM('page', 'product', 'garment', 'category', 'jewel_category') NOT NULL,
        entity_id INT DEFAULT NULL,
        url_slug VARCHAR(255) DEFAULT NULL,
        meta_title VARCHAR(255),
        meta_description TEXT,
        meta_keywords TEXT,
        focus_keyword VARCHAR(255),
        seo_score INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY entity_idx (page_type, entity_id)
    )",
    "CREATE TABLE IF NOT EXISTS seo_config (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    "INSERT IGNORE INTO seo_config (setting_key, setting_value) VALUES 
        ('site_name', 'Sri Shringarr'),
        ('title_separator', '|'),
        ('default_og_image', 'main_logo.png')"
];

foreach ($sql as $query) {
    if (mysqli_query($con, $query)) {
        echo "Successfully executed: " . substr($query, 0, 50) . "...\n";
    } else {
        echo "Error: " . mysqli_error($con) . "\n";
    }
}
