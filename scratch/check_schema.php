<?php
require_once __DIR__ . '/../bootstrap.php';
$db = \Api\V1\Core\Database::getInstance()->getConnection();

echo "jewel_subcat schema:\n";
print_r($db->query("DESCRIBE jewel_subcat")->fetchAll(PDO::FETCH_ASSOC));

echo "\nsubcat1 schema:\n";
print_r($db->query("DESCRIBE subcat1")->fetchAll(PDO::FETCH_ASSOC));

echo "\ngarments schema:\n";
print_r($db->query("DESCRIBE garments")->fetchAll(PDO::FETCH_ASSOC));
