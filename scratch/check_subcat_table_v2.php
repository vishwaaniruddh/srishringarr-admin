<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Subcategories (subcat table):\n";
    $stmt = $db->query("SELECT subcat_id, name, mainsub_id FROM subcat ORDER BY name ASC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['subcat_id'] . ": " . $row['name'] . " (Parent ID: " . $row['mainsub_id'] . ")\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
