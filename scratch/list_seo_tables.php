<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=u464193275_srishrinjewels', 'reporting', 'reporting');
    $stmt = $db->query("SHOW TABLES LIKE 'seo_%'");
    print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
