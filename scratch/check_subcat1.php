<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "subcat1 rows:\n";
    $stmt = $db->query("SELECT COUNT(*) FROM subcat1");
    echo $stmt->fetchColumn() . "\n";

    echo "\nSample data from subcat1:\n";
    $stmt = $db->query("SELECT * FROM subcat1 LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
