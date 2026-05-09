<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Product count by subcat_id:\n";
    $stmt = $db->query("SELECT subcat_id, COUNT(*) as count FROM product GROUP BY subcat_id ORDER BY count DESC LIMIT 20");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  subcat_id " . ($row['subcat_id'] ?? 'NULL') . ": " . $row['count'] . "\n";
    }

    echo "\nJewel Subcategories:\n";
    $stmt = $db->query("SELECT subcat_id, categories_name FROM jewel_subcat ORDER BY categories_name ASC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['subcat_id'] . ": " . $row['categories_name'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
