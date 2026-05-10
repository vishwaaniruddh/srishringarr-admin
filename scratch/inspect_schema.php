<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=u464193275_srishrinjewels', 'reporting', 'reporting');
    $tables = ['product', 'garment_product', 'jewel_subcat', 'garments', 'subcat1'];
    foreach ($tables as $table) {
        echo "TABLE: $table\n";
        $stmt = $db->query("DESCRIBE $table");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $c) {
            if (strpos($c['Field'], 'meta') !== false || strpos($c['Field'], 'title') !== false || strpos($c['Field'], 'desc') !== false) {
                echo "  - " . $c['Field'] . "\n";
            }
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
