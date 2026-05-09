<?php
require_once 'c:/xampp/htdocs/sri/admin/project/api/v1/core/Database.php';
use Api\V1\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    
    echo "Products where subcat_id != subcatagoty:\n";
    $stmt = $db->query("SELECT product_id, subcat_id, subcatagoty FROM product WHERE subcat_id != subcatagoty AND subcatagoty IS NOT NULL AND subcatagoty != 0 LIMIT 10");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
