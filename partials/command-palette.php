<div class="command-palette-overlay" id="command-palette">
    <div class="command-palette animate-slide-down">
        <div class="command-palette-input">
            <span class="material-symbols-outlined" style="color:var(--on-surface-variant)">search</span>
            <input type="text" placeholder="Type a command or search..." id="command-input" autofocus/>
            <kbd style="font-family:var(--font-label);font-size:10px;background:var(--surface-container-high);padding:2px 8px;border-radius:4px;color:var(--outline);">ESC</kbd>
        </div>
        <div class="command-palette-results" id="command-results">
            <div class="command-result-item" onclick="window.location='?page=dashboard'">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="command-result-text">Dashboard</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>1</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=inventory'">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="command-result-text">Inventory Management</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>2</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=customers'">
                <span class="material-symbols-outlined">person</span>
                <span class="command-result-text">Customer Profiles</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>3</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=orders'">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="command-result-text">Orders & Invoices</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>4</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=rentals'">
                <span class="material-symbols-outlined">event_available</span>
                <span class="command-result-text">Rental Timeline</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>5</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=finance'">
                <span class="material-symbols-outlined">account_balance</span>
                <span class="command-result-text">Finance & Subscriptions</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>6</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=discounts'">
                <span class="material-symbols-outlined">sell</span>
                <span class="command-result-text">Discount Management</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>7</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=coupons'">
                <span class="material-symbols-outlined">confirmation_number</span>
                <span class="command-result-text">Coupon Center</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>8</kbd></span>
            </div>
            <div class="command-result-item" onclick="window.location='?page=newsletter'">
                <span class="material-symbols-outlined">campaign</span>
                <span class="command-result-text">Newsletter Hub</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>9</kbd></span>
            </div>
            <div class="command-result-item" onclick="toggleTheme()">
                <span class="material-symbols-outlined">dark_mode</span>
                <span class="command-result-text">Toggle Dark Mode</span>
                <span class="command-result-shortcut"><kbd>⌘</kbd><kbd>D</kbd></span>
            </div>
        </div>
    </div>
</div>
