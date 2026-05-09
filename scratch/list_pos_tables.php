<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection('pos');
    echo "Tables in POS DB:\n";
    $stmt = $db->query("SHOW TABLES");
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "  " . $row[0] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
