<?php
require_once __DIR__ . '/../bootstrap.php';
$db = \Api\V1\Core\Database::getInstance()->getConnection();

function printFirst5($db, $table) {
    echo "First 5 Columns for $table:\n";
    $cols = $db->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < min(5, count($cols)); $i++) {
        echo "- {$cols[$i]['Field']}\n";
    }
    echo "\n";
}

printFirst5($db, 'jewel_subcat');
printFirst5($db, 'subcat1');
printFirst5($db, 'garments');
printFirst5($db, 'product');
