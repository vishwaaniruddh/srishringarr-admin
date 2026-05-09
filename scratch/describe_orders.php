<?php
$table = $argv[1] ?? 'orders';
$db = new PDO('mysql:host=localhost;dbname=u464193275_srishrinjewels', 'reporting', 'reporting');
$query = (strpos($table, ' ') !== false) ? $table : "DESCRIBE $table";
$stmt = $db->query($query);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
