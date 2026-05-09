<?php
require_once 'c:/xampp/htdocs/sri/admin/project/bootstrap.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection('pos');
    echo "Connected to POS DB\n";
    
    $stmt = $db->query("DESCRIBE phppos_items");
    echo "Schema for phppos_items:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
