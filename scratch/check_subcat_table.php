<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Subcategories (subcategory table):\n";
    $stmt = $db->query("SELECT sub_id, name, categories_id FROM subcategory ORDER BY name ASC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['sub_id'] . ": " . $row['name'] . " (Parent ID: " . $row['categories_id'] . ")\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
