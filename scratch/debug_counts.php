<?php
require_once __DIR__ . '/../bootstrap.php';
$model = new \Api\V1\Models\ProductModel();

echo "Total Count (all): " . $model->getTotalCount('', 'all', 'all') . "\n";
echo "Categories: \n";
$cats = $model->getCategoriesWithCounts();
echo "Apparel count: " . count($cats['apparel']) . "\n";
echo "Jewellery count: " . count($cats['jewellery']) . "\n";

foreach($cats['jewellery'] as $jc) {
    if ($jc['count'] > 0) {
        echo "Jewellery Parent {$jc['name']}: {$jc['count']}\n";
    }
}
