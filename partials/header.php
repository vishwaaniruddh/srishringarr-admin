<?php
$header_icons = [
    'dashboard'  => 'analytics',
    'inventory'  => 'inventory_2',
    'customers'  => 'person_search',
    'orders'     => 'receipt_long',
    'rentals'    => 'calendar_month',
    'finance'    => 'account_balance_wallet',
    'discounts'  => 'sell',
    'coupons'    => 'confirmation_number',
    'newsletter' => 'campaign',
    'product_view' => 'visibility',
    'product_edit' => 'edit',
    'order_view'   => 'receipt_long'
];
$header_subtitles = [
    'dashboard'  => 'Real-time performance monitoring across all regions',
    'inventory'  => 'Track and manage your product catalog',
    'customers'  => 'Comprehensive customer relationship management',
    'orders'     => 'Monitor orders, invoices and payment status',
    'rentals'    => 'Manage rental schedules and availability',
    'finance'    => 'Financial overview and subscription management',
    'discounts'  => 'Create and manage discounts, sales events and pricing rules',
    'coupons'    => 'Generate, distribute and track promotional coupon codes',
    'newsletter' => 'Manage subscribers, campaigns and email communications',
    'product_view' => 'Detailed information and performance metrics for this item',
    'product_edit' => 'Modify product details and pricing configuration',
    'order_view'   => 'Detailed breakdown of customer purchase and fulfillment'
];
$header_ctas = [
    'dashboard'  => 'Generate Report',
    'inventory'  => 'Add Product',
    'customers'  => 'Add Customer',
    'orders'     => 'New Order',
    'rentals'    => 'New Booking',
    'finance'    => 'Export Report',
    'discounts'  => 'New Discount',
    'coupons'    => 'Create Coupon',
    'newsletter' => 'New Campaign',
    'product_view' => 'Update Stats',
    'product_edit' => 'Save Product',
    'order_view'   => 'Print Invoice',
    'categories'   => 'New Category'
];
$header_icons['categories'] = 'category';
$header_subtitles['categories'] = 'Manage collection hierarchy and product taxonomy';
?>
<header class="top-header">
    <div class="header-left">
        <h1>
            <span class="material-symbols-outlined"><?php echo $header_icons[$page] ?? 'visibility'; ?></span>
            <?php echo $current_title; ?>
        </h1>
        <p class="header-subtitle"><?php echo $header_subtitles[$page] ?? ''; ?></p>
    </div>

    <div class="header-right">
        <!-- Search Trigger -->
        <div class="search-bar" id="search-trigger" style="max-width:240px; cursor: pointer;" onclick="openCommandPalette()">
            <span class="material-symbols-outlined">search</span>
            <span style="font-size:12px;color:var(--outline);">Quick search...</span>
            <kbd>⌘K</kbd>
        </div>

        <!-- Date Picker -->
        <div class="header-date-picker">
            <span class="material-symbols-outlined">calendar_today</span>
            <span>Oct 12 – Oct 18, 2023</span>
            <span class="material-symbols-outlined">expand_more</span>
        </div>

        <!-- Theme Toggle -->
        <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
            <span class="material-symbols-outlined" id="theme-icon">dark_mode</span>
        </button>

        <!-- Notifications -->
        <button class="header-notification-btn" aria-label="Notifications" id="notification-btn">
            <span class="material-symbols-outlined">notifications</span>
            <span class="header-notification-dot"></span>
        </button>

        <!-- CTA -->
        <button class="header-cta-btn" id="header-cta">
            <?php echo $header_ctas[$page] ?? 'Action'; ?>
        </button>
    </div>
</header>
