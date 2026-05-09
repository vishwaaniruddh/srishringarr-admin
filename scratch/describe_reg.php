<?php
$db = new PDO('mysql:host=localhost;dbname=u464193275_srishrinjewels', 'reporting', 'reporting');
$stmt = $db->query('DESCRIBE Registration');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
