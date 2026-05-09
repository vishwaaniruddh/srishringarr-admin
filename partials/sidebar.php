<?php
$page_nav = [
    'dashboard'  => ['label' => 'Dashboard', 'icon' => 'dashboard'],
    'inventory'  => [
        'label' => 'Inventory', 
        'icon' => 'inventory_2',
        'sub' => [
            'inventory' => 'Product',
            'categories' => 'Categories',
            'yn_remote' => 'YN Remote'
        ]
    ],
    'customers'  => ['label' => 'Customers', 'icon' => 'person'],
    'orders'     => ['label' => 'Orders', 'icon' => 'receipt_long'],
    'rentals'    => ['label' => 'Rentals', 'icon' => 'event_available'],
    'finance'    => ['label' => 'Finance', 'icon' => 'account_balance']
];

$marketing_nav = [
    'discounts'  => ['label' => 'Discounts', 'icon' => 'sell'],
    'coupons'    => ['label' => 'Coupons', 'icon' => 'confirmation_number'],
    'newsletter' => ['label' => 'Newsletter', 'icon' => 'campaign']
];

$system_nav = [
    'audit'  => ['label' => 'System Audit', 'icon' => 'terminal'],
    'settings' => [
        'label' => 'Settings',
        'icon' => 'settings',
        'sub' => [
            'settings_email' => 'Email',
            'settings_site_info' => 'Site Info'
        ]
    ]
];
?>
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <span class="material-symbols-outlined">grid_view</span>
        </div>
        <div class="sidebar-brand-text">NEXUS<span>CRM</span></div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main Menu</div>
        <?php foreach ($page_nav as $key => $item): ?>
            <?php if (isset($item['sub'])): ?>
                <div class="nav-item-wrapper <?php echo ($page === $key || isset($item['sub'][$page])) ? 'active' : ''; ?>">
                    <div class="nav-item has-sub" onclick="toggleSubmenu(this)">
                        <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                        <span class="nav-item-text"><?php echo $item['label']; ?></span>
                        <span class="material-symbols-outlined sub-arrow">expand_more</span>
                    </div>
                    <div class="submenu">
                        <?php foreach ($item['sub'] as $sub_key => $sub_label): ?>
                            <a href="?page=<?php echo $sub_key; ?>" class="submenu-item <?php echo ($page === $sub_key) ? 'active' : ''; ?>">
                                <?php echo $sub_label; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <a class="nav-item <?php echo ($page === $key) ? 'active' : ''; ?>" 
                   href="?page=<?php echo $key; ?>" id="nav-<?php echo $key; ?>">
                    <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                    <span class="nav-item-text"><?php echo $item['label']; ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="nav-section-label" style="margin-top: 16px;">Marketing</div>
        <?php foreach ($marketing_nav as $key => $item): ?>
        <a class="nav-item <?php echo ($page === $key) ? 'active' : ''; ?>" 
           href="?page=<?php echo $key; ?>" id="nav-<?php echo $key; ?>">
            <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
            <span class="nav-item-text"><?php echo $item['label']; ?></span>
        </a>
        <?php endforeach; ?>

        <div class="nav-section-label" style="margin-top: 16px;">System</div>
        <?php foreach ($system_nav as $key => $item): ?>
            <?php if (isset($item['sub'])): ?>
                <div class="nav-item-wrapper <?php echo ($page === $key || isset($item['sub'][$page])) ? 'active' : ''; ?>">
                    <div class="nav-item has-sub" onclick="toggleSubmenu(this)">
                        <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                        <span class="nav-item-text"><?php echo $item['label']; ?></span>
                        <span class="material-symbols-outlined sub-arrow">expand_more</span>
                    </div>
                    <div class="submenu">
                        <?php foreach ($item['sub'] as $sub_key => $sub_label): ?>
                            <a href="?page=<?php echo $sub_key; ?>" class="submenu-item <?php echo ($page === $sub_key) ? 'active' : ''; ?>">
                                <?php echo $sub_label; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <a class="nav-item <?php echo ($page === $key) ? 'active' : ''; ?>" 
                   href="?page=<?php echo $key; ?>" id="nav-<?php echo $key; ?>">
                    <span class="material-symbols-outlined"><?php echo $item['icon']; ?></span>
                    <span class="nav-item-text"><?php echo $item['label']; ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
        <a class="nav-item" href="#" id="nav-help">
            <span class="material-symbols-outlined">help_outline</span>
            <span class="nav-item-text">Help Center</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <img alt="User Avatar" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix"/>
            </div>
            <div class="sidebar-user-info">
                <p class="sidebar-user-name">Alex Rivera</p>
                <p class="sidebar-user-role">Admin Account</p>
            </div>
            <button class="sidebar-settings-btn" aria-label="User settings">
                <span class="material-symbols-outlined" style="font-size:18px;">settings</span>
            </button>
        </div>
    </div>
</aside>

<script>
function toggleSubmenu(el) {
    const wrapper = el.closest('.nav-item-wrapper');
    wrapper.classList.toggle('active');
}
</script>
