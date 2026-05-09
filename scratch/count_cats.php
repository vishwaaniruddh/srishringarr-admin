<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    $tables = ['maincategory', 'subcategory', 'jewel_subcat', 'jewel_subcat1', 'garments'];
    foreach ($tables as $table) {
        $stmt = $db->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "$table: $count rows\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
