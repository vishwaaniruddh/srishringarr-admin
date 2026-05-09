<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    $dbs = ['u464193275_srishrinjewels', 'u464193275_srishringarr'];
    
    foreach ($dbs as $dbname) {
        echo "Checking $dbname...\n";
        $stmt = $db->query("SHOW TABLES FROM $dbname LIKE '%product%'");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "  Match: " . $row[0] . "\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
