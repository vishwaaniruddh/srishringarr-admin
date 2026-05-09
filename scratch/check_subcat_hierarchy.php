<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "jewel_subcat1 where mainsub_id = 15:\n";
    $stmt = $db->query("SELECT * FROM jewel_subcat1 WHERE mainsub_id = 15 OR main_id = 15");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
