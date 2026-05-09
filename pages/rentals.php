<div class="page-sections">
    <!-- Stats Row -->
    <div class="grid-4">
        <div class="stat-card gradient grad-indigo animate-fade-in-up delay-1">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">checkroom</span></div>
                <span class="stat-badge up">+18</span>
            </div>
            <p class="stat-label">Active Rentals</p>
            <h3 class="stat-value">64</h3>
        </div>
        <div class="stat-card gradient grad-amber animate-fade-in-up delay-2">
            <div class="stat-card-header">
                <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">event_upcoming</span></div>
            </div>
            <p class="stat-label">Upcoming Returns</p>
            <h3 class="stat-value">23</h3>
        </div>
        <div class="stat-card gradient grad-emerald animate-fade-in-up delay-3">
            <div class="stat-card-header">
                <div class="stat-card-icon success"><span class="material-symbols-outlined">attach_money</span></div>
                <span class="stat-badge up">+22.3%</span>
            </div>
            <p class="stat-label">Monthly Revenue</p>
            <h3 class="stat-value">₹8,45,000</h3>
        </div>
        <div class="stat-card gradient grad-rose animate-fade-in-up delay-4">
            <div class="stat-card-header">
                <div class="stat-card-icon secondary"><span class="material-symbols-outlined">event_busy</span></div>
            </div>
            <p class="stat-label">Overdue Items</p>
            <h3 class="stat-value">7</h3>
        </div>
    </div>

    <!-- Calendar + Rentals List -->
    <div class="grid-3">
        <!-- Calendar -->
        <div class="col-span-2 card animate-fade-in-up delay-3">
            <div class="card-header">
                <h4 class="card-title">October 2023</h4>
                <div style="display:flex;gap:8px;">
                    <button class="btn-icon"><span class="material-symbols-outlined">chevron_left</span></button>
                    <button class="btn btn-ghost btn-sm" style="font-weight:700;">Today</button>
                    <button class="btn-icon"><span class="material-symbols-outlined">chevron_right</span></button>
                </div>
            </div>
            <div class="rental-calendar">
                <?php
                $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                foreach ($days as $d): ?>
                    <div class="rental-day header"><?php echo $d; ?></div>
                <?php endforeach;
                $bookedDays = [3,4,5,10,11,12,13,17,18,19,24,25];
                $partialDays = [6,14,20,26];
                $today = 15;
                for ($i = 1; $i <= 31; $i++):
                    $offset = ($i === 1) ? 0 : 0;
                    $class = '';
                    if ($i === $today) $class = 'today';
                    elseif (in_array($i, $bookedDays)) $class = 'booked';
                    elseif (in_array($i, $partialDays)) $class = 'partial';
                ?>
                    <div class="rental-day <?php echo $class; ?>"><?php echo $i; ?></div>
                <?php endfor; ?>
            </div>
            <div style="display:flex;gap:24px;margin-top:20px;padding-top:16px;border-top:1px solid var(--outline-variant);">
                <div style="display:flex;align-items:center;gap:8px;font-size:11px;color:var(--on-surface-variant);">
                    <span style="width:12px;height:12px;border-radius:4px;background:var(--primary);opacity:0.15;"></span> Booked
                </div>
                <div style="display:flex;align-items:center;gap:8px;font-size:11px;color:var(--on-surface-variant);">
                    <span style="width:12px;height:12px;border-radius:4px;background:var(--tertiary);opacity:0.15;"></span> Partial
                </div>
                <div style="display:flex;align-items:center;gap:8px;font-size:11px;color:var(--on-surface-variant);">
                    <span style="width:12px;height:12px;border-radius:4px;background:var(--primary);"></span> Today
                </div>
            </div>
        </div>

        <!-- Active Rentals List -->
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card animate-fade-in-up delay-4" style="padding:24px;">
                <h4 class="card-title" style="font-size:16px;margin-bottom:16px;">Active Rentals</h4>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <?php
                    $rentals = [
                        ['Bridal Lehenga Set', 'Oct 12 - Oct 18', '₹15,000/day', 'active'],
                        ['Designer Sherwani', 'Oct 14 - Oct 16', '₹8,000/day', 'active'],
                        ['Kundan Jewellery Set', 'Oct 10 - Oct 15', '₹25,000/day', 'overdue'],
                        ['Silk Saree Collection', 'Oct 16 - Oct 20', '₹5,000/day', 'pending'],
                        ['Diamond Necklace', 'Oct 18 - Oct 22', '₹35,000/day', 'pending'],
                    ];
                    foreach ($rentals as $r):
                    ?>
                    <div class="rental-item">
                        <div class="rental-item-image">
                            <span class="material-symbols-outlined">checkroom</span>
                        </div>
                        <div class="rental-item-info">
                            <p class="rental-item-title"><?php echo $r[0]; ?></p>
                            <p class="rental-item-dates"><?php echo $r[1]; ?></p>
                        </div>
                        <div style="text-align:right;">
                            <p class="rental-item-price"><?php echo $r[2]; ?></p>
                            <span class="chip <?php echo $r[3]; ?>" style="margin-top:4px;">
                                <span class="chip-dot"></span>
                                <?php echo ($r[3]==='active')?'Active':(($r[3]==='overdue')?'Overdue':'Upcoming'); ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
