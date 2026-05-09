<div class="page-sections">
    <!-- Stats -->
    <div class="grid-4">
        <div class="stat-card gradient grad-indigo animate-fade-in-up delay-1">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">group</span></div>
                <span class="stat-badge up">+128</span>
            </div>
            <p class="stat-label">Total Subscribers</p>
            <h3 class="stat-value">12,480</h3>
        </div>
        <div class="stat-card gradient grad-emerald animate-fade-in-up delay-2">
            <div class="stat-card-header">
                <div class="stat-card-icon success"><span class="material-symbols-outlined">open_in_new</span></div>
                <span class="stat-badge up">+3.2%</span>
            </div>
            <p class="stat-label">Open Rate</p>
            <h3 class="stat-value">42.8%</h3>
        </div>
        <div class="stat-card gradient grad-amber animate-fade-in-up delay-3">
            <div class="stat-card-header">
                <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">ads_click</span></div>
            </div>
            <p class="stat-label">Click Rate</p>
            <h3 class="stat-value">18.6%</h3>
        </div>
        <div class="stat-card gradient grad-slate animate-fade-in-up delay-4">
            <div class="stat-card-header">
                <div class="stat-card-icon secondary"><span class="material-symbols-outlined">mail</span></div>
            </div>
            <p class="stat-label">Campaigns Sent</p>
            <h3 class="stat-value">156</h3>
        </div>
    </div>

    <!-- Content -->
    <div class="grid-3">
        <!-- Campaigns Table -->
        <div class="col-span-2" style="display:flex;flex-direction:column;gap:24px;">
            <!-- Recent Campaigns -->
            <div class="card animate-fade-in-up delay-3">
                <div class="card-header">
                    <h4 class="card-title">Recent Campaigns</h4>
                    <div class="pill-tabs">
                        <button class="pill-tab active">All</button>
                        <button class="pill-tab">Sent</button>
                        <button class="pill-tab">Draft</button>
                        <button class="pill-tab">Scheduled</button>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:16px;">
<?php
$campaigns = [
    ['Diwali Collection Preview','Showcasing our exclusive Diwali jewellery collection','Oct 14, 2023','12,480','44.2%','22.1%','active','campaign'],
    ['Bridal Season Lookbook','Curated bridal sets for the wedding season','Oct 10, 2023','11,200','38.5%','15.8%','active','auto_stories'],
    ['Flash Sale Alert','48-hour flash sale on silver accessories','Oct 8, 2023','12,480','52.1%','28.4%','active','flash_on'],
    ['New Arrivals October','Fresh designs added this month','Oct 1, 2023','10,850','36.7%','14.2%','active','new_releases'],
    ['VIP Early Access','Exclusive early access to festive collection','Sep 28, 2023','2,400','68.3%','35.6%','active','diamond'],
    ['Customer Survey','Share your shopping experience with us','Sep 25, 2023','12,480','28.4%','8.2%','active','poll'],
];
foreach ($campaigns as $i => $c):
?>
                    <div style="display:flex;align-items:center;gap:20px;padding:20px;border-radius:var(--radius-2xl);border:1px solid var(--outline-variant);transition:all 250ms ease;cursor:pointer;" onmouseover="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 20px rgba(103,80,164,0.1)'" onmouseout="this.style.borderColor='var(--outline-variant)';this.style.boxShadow='none'">
                        <div style="width:48px;height:48px;border-radius:var(--radius-xl);background:rgba(103,80,164,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span class="material-symbols-outlined" style="color:var(--primary);"><?php echo $c[7]; ?></span>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-weight:800;font-size:14px;"><?php echo $c[0]; ?></p>
                            <p style="font-size:12px;color:var(--on-surface-variant);margin-top:2px;"><?php echo $c[1]; ?></p>
                            <div style="display:flex;gap:16px;margin-top:8px;">
                                <span style="font-size:10px;color:var(--outline);display:flex;align-items:center;gap:4px;"><span class="material-symbols-outlined" style="font-size:12px;">schedule</span><?php echo $c[2]; ?></span>
                                <span style="font-size:10px;color:var(--outline);display:flex;align-items:center;gap:4px;"><span class="material-symbols-outlined" style="font-size:12px;">group</span><?php echo $c[3]; ?> sent</span>
                            </div>
                        </div>
                        <div style="display:flex;gap:24px;text-align:center;">
                            <div>
                                <p style="font-family:var(--font-headline);font-size:18px;font-weight:800;color:var(--success);"><?php echo $c[4]; ?></p>
                                <p class="text-label-caps" style="margin-top:2px;">Opened</p>
                            </div>
                            <div>
                                <p style="font-family:var(--font-headline);font-size:18px;font-weight:800;color:var(--primary);"><?php echo $c[5]; ?></p>
                                <p class="text-label-caps" style="margin-top:2px;">Clicked</p>
                            </div>
                        </div>
                        <button class="btn-icon"><span class="material-symbols-outlined">more_vert</span></button>
                    </div>
<?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div style="display:flex;flex-direction:column;gap:24px;">
            <!-- Subscriber Growth -->
            <div class="card animate-fade-in-up delay-4" style="padding:24px;">
                <h4 class="card-title" style="font-size:16px;margin-bottom:16px;">Subscriber Growth</h4>
                <div style="display:flex;flex-direction:column;gap:12px;">
<?php
$months = [['October','12,480','+128','up'],['September','12,352','+215','up'],['August','12,137','+189','up'],['July','11,948','-42','down']];
foreach ($months as $m):
?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(203,196,210,0.2);">
                        <div>
                            <p style="font-size:13px;font-weight:700;"><?php echo $m[0]; ?></p>
                            <p style="font-size:11px;color:var(--outline);"><?php echo $m[1]; ?> subscribers</p>
                        </div>
                        <span class="stat-badge <?php echo $m[3]; ?>"><?php echo $m[2]; ?></span>
                    </div>
<?php endforeach; ?>
                </div>
            </div>

            <!-- Audience Segments -->
            <div class="card animate-fade-in-up delay-5" style="padding:24px;">
                <h4 class="card-title" style="font-size:16px;margin-bottom:16px;">Audience Segments</h4>
                <div style="display:flex;flex-direction:column;gap:10px;">
<?php
$segments = [
    ['All Subscribers','12,480','100%','primary'],
    ['VIP Customers','2,400','19%','tertiary'],
    ['Bridal Interests','4,200','34%','secondary'],
    ['Inactive 30d+','1,860','15%','outline'],
    ['New This Month','128','1%','success'],
];
foreach ($segments as $s):
?>
                    <div style="display:flex;align-items:center;gap:12px;padding:8px 0;">
                        <div style="width:8px;height:8px;border-radius:var(--radius-full);background:var(--<?php echo $s[3]; ?>);flex-shrink:0;"></div>
                        <div style="flex:1;">
                            <p style="font-size:12px;font-weight:600;"><?php echo $s[0]; ?></p>
                        </div>
                        <span class="text-mono-data"><?php echo $s[1]; ?></span>
                        <span style="font-size:10px;color:var(--outline);width:32px;text-align:right;"><?php echo $s[2]; ?></span>
                    </div>
<?php endforeach; ?>
                </div>
                <button class="btn btn-ghost" style="width:100%;margin-top:12px;justify-content:center;color:var(--primary);font-size:12px;">Manage Segments</button>
            </div>

            <!-- Draft -->
            <div class="card animate-fade-in-up delay-6" style="padding:24px;background:rgba(103,80,164,0.04);border:1px solid rgba(103,80,164,0.1);">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <span class="material-symbols-outlined" style="color:var(--primary);font-size:20px;">edit_note</span>
                    <p style="font-size:10px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:0.08em;">Draft in Progress</p>
                </div>
                <p style="font-weight:700;font-size:14px;">Navratri Special Edition</p>
                <p style="font-size:12px;color:var(--on-surface-variant);margin-top:4px;">Traditional gold & silver pieces for Navratri celebrations</p>
                <div style="display:flex;gap:8px;margin-top:16px;">
                    <button class="btn btn-primary btn-sm" style="flex:1;">Continue Editing</button>
                    <button class="btn btn-secondary btn-sm">Preview</button>
                </div>
            </div>
        </div>
    </div>
</div>
