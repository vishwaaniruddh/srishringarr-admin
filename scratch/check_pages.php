<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=u464193275_srishrinjewels', 'reporting', 'reporting');
    echo "PAGES TABLE:\n";
    $stmt = $db->query("SELECT * FROM pages");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
