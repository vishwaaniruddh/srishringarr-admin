<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "categories (Jewels DB):\n";
    $stmt = $db->query("SELECT * FROM categories LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

    $posDb = Database::getInstance()->getConnection('pos');
    echo "\ncategories (POS DB):\n";
    $stmt = $posDb->query("SELECT * FROM categories LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
