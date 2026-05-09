<?php
require_once __DIR__ . '/../bootstrap.php';
$db = \Api\V1\Core\Database::getInstance()->getConnection();

function printCols($db, $table) {
    echo "Columns for $table:\n";
    $cols = $db->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
    foreach($cols as $col) {
        echo "- {$col['Field']} ({$col['Type']})\n";
    }
    echo "\n";
}

printCols($db, 'jewel_subcat');
printCols($db, 'subcat1');
printCols($db, 'garments');
printCols($db, 'product');
printCols($db, 'garment_product');
