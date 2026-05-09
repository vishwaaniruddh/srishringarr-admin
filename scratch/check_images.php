<?php
$db = new PDO('mysql:host=localhost;dbname=u464193275_srishrinjewels', 'reporting', 'reporting');
$stmt = $db->query("SELECT * FROM product_images_new LIMIT 3");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
}
