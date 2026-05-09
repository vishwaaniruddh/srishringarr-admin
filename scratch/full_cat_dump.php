<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Full jewel_subcat:\n";
    $stmt = $db->query("SELECT subcat_id, categories_name FROM jewel_subcat");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['subcat_id'] . ": " . $row['categories_name'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
