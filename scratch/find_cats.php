<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Tables in u464193275_srishrinjewels:\n";
    $stmt = $db->query("SHOW TABLES FROM u464193275_srishrinjewels LIKE '%cat%'");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "  " . $row[0] . "\n";
    }

    echo "\nTables in u464193275_srishringarr:\n";
    $stmt = $db->query("SHOW TABLES FROM u464193275_srishringarr LIKE '%cat%'");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "  " . $row[0] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
