<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Product count by categories_id:\n";
    $stmt = $db->query("SELECT categories_id, COUNT(*) as count FROM product GROUP BY categories_id ORDER BY count DESC LIMIT 20");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . ($row['categories_id'] ?? 'NULL') . ": " . $row['count'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
