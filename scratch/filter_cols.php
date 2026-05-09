<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    $tables = ['product', 'garment_product'];
    foreach ($tables as $table) {
        echo "Searching 'cat' or 'id' in $table:\n";
        $stmt = $db->query("SHOW COLUMNS FROM $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (stripos($row['Field'], 'cat') !== false || stripos($row['Field'], 'id') !== false) {
                echo "  " . $row['Field'] . "\n";
            }
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
