<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Subcategories (subcat table) for missing IDs:\n";
    $stmt = $db->query("SELECT * FROM subcat WHERE subcat_id IN (74, 63, 3, 2, 73, 75, 4, 53, 57, 66)");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
