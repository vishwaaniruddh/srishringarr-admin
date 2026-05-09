<?php
/**
 * Nexus Unified Commerce Suite - Main Router
 * Built from Google Stitch Design System
 * Project: https://stitch.withgoogle.com/projects/6637252652806628698
 */

session_start();
require_once __DIR__ . '/bootstrap.php';

// Get the current page
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Valid pages
$valid_pages = [
    'dashboard',
    'inventory',
    'customers',
    'orders',
    'rentals',
    'finance',
    'discounts',
    'coupons',
    'newsletter',
    'product_view',
    'product_edit',
    'order_view',
    'categories',
    'yn_remote',
    'coupon_add'
];

if (!in_array($page, $valid_pages)) {
    $page = 'dashboard';
}

// Page titles mapping
$page_titles = [
    'dashboard'  => 'Enterprise Insights',
    'inventory'  => 'Inventory Management',
    'customers'  => 'Customer Profile',
    'orders'     => 'Orders & Invoices',
    'rentals'    => 'Rental Timeline',
    'finance'    => 'Finance & Subscriptions',
    'discounts'  => 'Discount Management',
    'coupons'    => 'Coupon Center',
    'newsletter' => 'Newsletter Hub',
    'product_view' => 'Product Details',
    'product_edit' => 'Edit Product',
    'order_view'   => 'Order Details',
    'categories'   => 'Collection Taxonomy',
    'yn_remote'    => 'YN Remote Management',
    'coupon_add'   => 'Add New Coupon'
];

$current_title = $page_titles[$page] ?? 'Nexus Enterprise';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $current_title; ?> | Nexus Enterprise</title>
    <meta name="description" content="Nexus Unified Commerce Suite - <?php echo $current_title; ?>. Enterprise-grade CRM and business management platform."/>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=Geist:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Styles -->
    <link rel="stylesheet" href="css/design-system.css"/>
    <link rel="stylesheet" href="css/layout.css"/>
    <link rel="stylesheet" href="css/components.css"/>
    <link rel="stylesheet" href="css/pages.css"/>
    <link rel="stylesheet" href="css/animations.css"/>
</head>
<body>
    <div class="app-shell">
        <!-- Sidebar -->
        <?php include 'partials/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-wrapper">
            <!-- Top Header -->
            <?php include 'partials/header.php'; ?>

            <!-- Page Content -->
            <main class="main-content" id="main-content">
                <?php include "pages/{$page}.php"; ?>
            </main>
        </div>
    </div>

    <!-- Command Palette Modal -->
    <?php include 'partials/command-palette.php'; ?>

    <!-- Global Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Confirmation Modal -->
    <div id="confirm-modal" class="modal-overlay">
        <div class="modal-content confirm-modal">
            <div class="confirm-modal-icon">
                <span class="material-symbols-outlined">warning</span>
            </div>
            <h3 id="confirm-title">Are you sure?</h3>
            <p id="confirm-message">This action cannot be undone.</p>
            <div class="confirm-modal-actions">
                <button class="btn btn-secondary" onclick="closeConfirmModal()">Cancel</button>
                <button id="confirm-btn" class="btn btn-error">Delete</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/app.js"></script>
</body>
</html>
