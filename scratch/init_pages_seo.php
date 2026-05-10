<?php
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'c:/xampp/htdocs/sri/config.php';

$pages = [
    ['Home', 'index.php', 'Sri Shringarr Fashion Studio - Elegance for Every Occasion'],
    ['About Us', 'about_us.php', 'About Us | Sri Shringarr Fashion Studio'],
    ['Contact Us', 'contact_us.php', 'Contact Us | Sri Shringarr Fashion Studio'],
    ['FAQ', 'faq.php', 'Frequently Asked Questions | Sri Shringarr']
];

foreach ($pages as $p) {
    $title = mysqli_real_escape_index($con, $p[2]);
    $slug = mysqli_real_escape_index($con, $p[1]);
    $query = "INSERT IGNORE INTO seo_meta (page_type, url_slug, meta_title) VALUES ('page', '$slug', '$title')";
    mysqli_query($con, $query);
}

echo "Static pages initialized in seo_meta table.";

function mysqli_real_escape_index($con, $val) {
    return mysqli_real_escape_string($con, $val);
}
