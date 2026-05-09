<div class="page-sections">
    <!-- Stats Row -->
    <div class="grid-4">
        <div class="stat-card gradient grad-indigo animate-fade-in-up delay-1">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">account_balance_wallet</span></div>
                <span class="stat-badge up">+18.5%</span>
            </div>
            <p class="stat-label">Total Revenue</p>
            <h3 class="stat-value">₹2.4Cr</h3>
            <div class="stat-progress"><div class="stat-progress-bar primary" style="width:85%"></div></div>
        </div>
        <div class="stat-card gradient grad-emerald animate-fade-in-up delay-2">
            <div class="stat-card-header">
                <div class="stat-card-icon success"><span class="material-symbols-outlined">trending_up</span></div>
                <span class="stat-badge up">+12.3%</span>
            </div>
            <p class="stat-label">Net Profit</p>
            <h3 class="stat-value">₹68.4L</h3>
            <div class="stat-progress"><div class="stat-progress-bar success" style="width:68%"></div></div>
        </div>
        <div class="stat-card gradient grad-amber animate-fade-in-up delay-3">
            <div class="stat-card-header">
                <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">receipt</span></div>
            </div>
            <p class="stat-label">Pending Invoices</p>
            <h3 class="stat-value">₹12.8L</h3>
            <div class="stat-progress"><div class="stat-progress-bar tertiary" style="width:32%"></div></div>
        </div>
        <div class="stat-card gradient grad-purple animate-fade-in-up delay-4">
            <div class="stat-card-header">
                <div class="stat-card-icon secondary"><span class="material-symbols-outlined">subscriptions</span></div>
            </div>
            <p class="stat-label">Active Subscriptions</p>
            <h3 class="stat-value">142</h3>
            <div class="stat-progress"><div class="stat-progress-bar secondary" style="width:72%"></div></div>
        </div>
    </div>

    <!-- Charts + Subscriptions -->
    <div class="grid-3">
        <!-- Revenue Chart -->
        <div class="col-span-2 card animate-fade-in-up delay-3">
            <div class="card-header">
                <h4 class="card-title">Revenue Overview</h4>
                <div class="chart-toggle-group">
                    <button class="chart-toggle active">Monthly</button>
                    <button class="chart-toggle">Quarterly</button>
                    <button class="chart-toggle">Yearly</button>
                </div>
            </div>
            <div class="chart-area" style="padding-bottom:28px;">
                <?php
                $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                $heights = [35,42,38,55,48,62,58,72,68,85,78,90];
                $fills = [60,65,55,75,70,80,75,88,82,92,85,95];
                foreach ($months as $i => $m):
                ?>
                <div class="chart-bar-group" style="height:<?php echo $heights[$i]; ?>%">
                    <div class="chart-bar-bg" style="height:100%"></div>
                    <div class="chart-bar-fill" style="height:<?php echo $fills[$i]; ?>%"></div>
                    <span class="chart-bar-label"><?php echo $m; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Subscription Plans -->
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card animate-fade-in-up delay-4" style="padding:24px;">
                <h4 class="card-title" style="font-size:16px;margin-bottom:16px;">Subscription Plans</h4>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php
                    $subs = [
                        ['Premium Care', '₹2,500/mo', 'diamond', 'primary', '82 active', 'active'],
                        ['Gold Maintenance', '₹1,500/mo', 'workspace_premium', 'tertiary', '45 active', 'active'],
                        ['Basic Insurance', '₹800/mo', 'shield', 'secondary', '15 active', 'pending'],
                    ];
                    foreach ($subs as $s):
                    ?>
                    <div class="subscription-card">
                        <div class="subscription-icon" style="background:rgba(103,80,164,0.1);">
                            <span class="material-symbols-outlined" style="color:var(--<?php echo $s[3]; ?>);"><?php echo $s[2]; ?></span>
                        </div>
                        <div class="subscription-info">
                            <p class="subscription-name"><?php echo $s[0]; ?></p>
                            <p class="subscription-price"><?php echo $s[1]; ?> • <?php echo $s[4]; ?></p>
                        </div>
                        <span class="chip <?php echo $s[5]; ?>"><span class="chip-dot"></span><?php echo ucfirst($s[5]); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card animate-fade-in-up delay-5" style="padding:24px;">
                <h4 class="card-title" style="font-size:16px;margin-bottom:16px;">Recent Transactions</h4>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php
                    $transactions = [
                        ['Payment Received', 'Priya Sharma', '₹4,25,000', 'var(--success)', 'arrow_downward'],
                        ['Refund Issued', 'Arjun Patel', '-₹1,45,000', 'var(--danger)', 'arrow_upward'],
                        ['Payment Received', 'Vikram Singh', '₹6,80,000', 'var(--success)', 'arrow_downward'],
                        ['Deposit Collected', 'Neha Gupta', '₹50,000', 'var(--info)', 'arrow_downward'],
                    ];
                    foreach ($transactions as $t):
                    ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:8px 0;">
                        <div style="width:32px;height:32px;border-radius:var(--radius-full);background:var(--surface-container);display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined" style="font-size:16px;color:<?php echo $t[3]; ?>;"><?php echo $t[4]; ?></span>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:12px;font-weight:700;"><?php echo $t[0]; ?></p>
                            <p style="font-size:10px;color:var(--outline);"><?php echo $t[1]; ?></p>
                        </div>
                        <span style="font-family:var(--font-label);font-weight:700;font-size:13px;color:<?php echo $t[3]; ?>;"><?php echo $t[2]; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
