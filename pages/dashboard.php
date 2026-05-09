<!-- ApexCharts for modern data viz -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="page-sections">
    <!-- Breadcrumbs & Live Stats -->
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; color:var(--on-surface-variant); font-size:12px; margin-bottom:8px;">
                <span>System Console</span>
                <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                <span style="color:var(--primary); font-weight:600;">Enterprise Insights</span>
            </div>
            <h1 style="font-size:28px; font-weight:800; letter-spacing:-0.5px;">Dashboard</h1>
            <p style="color:var(--on-surface-variant); font-size:14px; margin-top:4px;">Real-time operational metrics and commerce intelligence.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-secondary" onclick="fetchDashboardData()">
                <span class="material-symbols-outlined">refresh</span> Refresh Stats
            </button>
            <button class="btn btn-primary">
                <span class="material-symbols-outlined">file_download</span> Export Report
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid-4" style="margin-bottom:32px;">
        <!-- Total Orders -->
        <div class="stat-card gradient grad-indigo animate-fade-in-up">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">shopping_bag</span></div>
                <span id="orders-trend" class="stat-badge">0%</span>
            </div>
            <p class="stat-label">Total Orders</p>
            <h3 id="total-orders" class="stat-value">0</h3>
            <div class="stat-progress"><div class="stat-progress-bar primary" style="width:100%"></div></div>
        </div>

        <!-- Monthly Revenue -->
        <div class="stat-card gradient grad-emerald animate-fade-in-up" style="animation-delay: 0.1s">
            <div class="stat-card-header">
                <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">payments</span></div>
                <span id="revenue-trend" class="stat-badge">0%</span>
            </div>
            <p class="stat-label">Monthly Revenue</p>
            <h3 id="monthly-revenue" class="stat-value">₹0</h3>
            <div class="stat-progress"><div class="stat-progress-bar tertiary" style="width:100%"></div></div>
        </div>

        <!-- Active Products -->
        <div class="stat-card gradient grad-amber animate-fade-in-up" style="animation-delay: 0.2s">
            <div class="stat-card-header">
                <div class="stat-card-icon secondary"><span class="material-symbols-outlined">inventory_2</span></div>
                <span class="stat-badge" style="background:rgba(255,255,255,0.2); color:white;">Live</span>
            </div>
            <p class="stat-label">Catalog Size</p>
            <h3 id="total-products" class="stat-value">0</h3>
            <div style="display:flex; gap:12px; margin-top:12px; font-size:11px; opacity:0.8;">
                <span id="jewel-count">Jewellery: 0</span>
                <span id="garment-count">Garments: 0</span>
            </div>
        </div>

        <!-- Active Rentals -->
        <div class="stat-card gradient grad-purple animate-fade-in-up" style="animation-delay: 0.3s">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">event_repeat</span></div>
                <span id="rentals-trend" class="stat-badge">0%</span>
            </div>
            <p class="stat-label">Active Rentals</p>
            <h3 id="active-rentals" class="stat-value">0</h3>
            <div class="stat-progress"><div class="stat-progress-bar primary" style="width:100%"></div></div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid-12" style="gap:24px; margin-bottom:32px;">
        <!-- Revenue Trend Chart -->
        <div class="col-span-8 card animate-fade-in-up" style="padding:24px; animation-delay: 0.4s">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                <h4 style="font-size:16px; font-weight:700;">Revenue Performance (Last 12 Months)</h4>
                <div style="display:flex; gap:8px;">
                    <span style="display:flex; align-items:center; gap:6px; font-size:12px; color:var(--on-surface-variant);">
                        <span style="width:8px; height:8px; background:var(--primary); border-radius:50%;"></span> Revenue
                    </span>
                </div>
            </div>
            <div id="revenueTrendChart" style="min-height: 350px;"></div>
        </div>

        <!-- Category Distribution -->
        <div class="col-span-4 card animate-fade-in-up" style="padding:24px; animation-delay: 0.5s">
            <h4 style="font-size:16px; font-weight:700; margin-bottom:24px;">Inventory Split</h4>
            <div id="categoryChart" style="min-height: 350px;"></div>
        </div>
    </div>

    <!-- Bottom Row: Recent Orders & Payment Methods -->
    <div class="grid-12" style="gap:24px;">
        <!-- Recent Orders -->
        <div class="col-span-7 card animate-fade-in-up" style="padding:0; overflow:hidden; animation-delay: 0.6s">
            <div style="padding:20px 24px; border-bottom:1px solid var(--outline-variant); display:flex; justify-content:space-between; align-items:center;">
                <h4 style="font-size:16px; font-weight:700;">Recent Transactions</h4>
                <button class="btn btn-ghost" onclick="window.location.href='?page=orders'" style="font-size:12px; color:var(--primary);">View All Orders</button>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="recent-orders-list">
                    <!-- Data injected by JS -->
                </tbody>
            </table>
        </div>

        <!-- Payment Methods & Popular -->
        <div class="col-span-5 card animate-fade-in-up" style="padding:24px; animation-delay: 0.7s">
            <h4 style="font-size:16px; font-weight:700; margin-bottom:24px;">Payment Channels</h4>
            <div id="paymentChart" style="min-height: 250px;"></div>
            
            <hr style="margin:24px 0; border:none; border-top:1px solid var(--outline-variant);">
            
            <h4 style="font-size:14px; font-weight:700; margin-bottom:16px;">Most Rented Items</h4>
            <div id="popular-products-list" style="display:flex; flex-direction:column; gap:12px;">
                <!-- Data injected by JS -->
            </div>
        </div>
    </div>
</div>

<style>
.stat-card { padding: 24px; border-radius: 5px; color: white; position: relative; overflow: hidden; }
.stat-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.stat-card-icon { width: 40px; height: 40px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; border-radius: 8px; }
.stat-badge { font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px; background: rgba(255,255,255,0.2); }
.stat-badge.up::before { content: '↑ '; }
.stat-badge.down::before { content: '↓ '; }
.stat-label { font-size: 12px; opacity: 0.8; margin-bottom: 4px; font-weight: 600; }
.stat-value { font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
.stat-progress { height: 4px; background: rgba(255,255,255,0.1); margin-top: 16px; border-radius: 2px; }
.stat-progress-bar { height: 100%; background: white; border-radius: 2px; opacity: 0.5; }

.data-table { width: 100%; border-collapse: collapse; }
.data-table th { text-align: left; padding: 12px 24px; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--outline); background: var(--surface-container-low); }
.data-table td { padding: 16px 24px; border-bottom: 1px solid var(--outline-variant); font-size: 13px; }
.data-table tr:last-child td { border-bottom: none; }
</style>

<script>
let revenueChart = null;
let categoryChart = null;
let paymentChart = null;

async function fetchDashboardData() {
    try {
        const response = await fetch('api/v1/dashboard-stats');
        const result = await response.json();
        
        if (result.status === 'success') {
            updateSummary(result.data.summary);
            renderRevenueTrend(result.data.revenue_trend);
            renderCategoryDistribution(result.data.category_distribution);
            renderRecentOrders(result.data.recent_orders);
            renderPopularProducts(result.data.popular_products);
            renderPaymentMethods(result.data.payment_methods);
        }
    } catch (err) {
        console.error('Error fetching dashboard stats:', err);
    }
}

function updateSummary(summary) {
    // Orders
    document.getElementById('total-orders').textContent = summary.orders.total.toLocaleString();
    updateTrend('orders-trend', summary.orders.trend);
    
    // Revenue
    document.getElementById('monthly-revenue').textContent = '₹' + summary.revenue.current_month.toLocaleString();
    updateTrend('revenue-trend', summary.revenue.trend);
    
    // Products
    document.getElementById('total-products').textContent = summary.products.total.toLocaleString();
    document.getElementById('jewel-count').textContent = 'Jewellery: ' + summary.products.jewellery;
    document.getElementById('garment-count').textContent = 'Garments: ' + summary.products.garments;
    
    // Rentals
    document.getElementById('active-rentals').textContent = summary.rentals.active.toLocaleString();
    updateTrend('rentals-trend', summary.rentals.trend);
}

function updateTrend(id, val) {
    const el = document.getElementById(id);
    const absVal = Math.abs(val).toFixed(1) + '%';
    el.textContent = absVal;
    el.className = 'stat-badge ' + (val >= 0 ? 'up' : 'down');
}

function renderRevenueTrend(data) {
    const options = {
        series: [{ name: 'Revenue', data: data.values }],
        chart: { type: 'area', height: 350, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        colors: ['#6366f1'],
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05 } },
        xaxis: { categories: data.labels, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { formatter: (v) => '₹' + v.toLocaleString() } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f1f1' }
    };
    
    if (revenueChart) revenueChart.destroy();
    revenueChart = new ApexCharts(document.querySelector("#revenueTrendChart"), options);
    revenueChart.render();
}

function renderCategoryDistribution(data) {
    const options = {
        series: data.values,
        chart: { type: 'donut', height: 350, fontFamily: 'Inter, sans-serif' },
        labels: data.labels,
        colors: ['#6366f1', '#10b981'],
        legend: { position: 'bottom' },
        plotOptions: { pie: { donut: { size: '75%' } } }
    };
    
    if (categoryChart) categoryChart.destroy();
    categoryChart = new ApexCharts(document.querySelector("#categoryChart"), options);
    categoryChart.render();
}

function renderRecentOrders(orders) {
    const tbody = document.getElementById('recent-orders-list');
    tbody.innerHTML = orders.map(o => `
        <tr>
            <td>
                <div style="font-weight:600;">#${o.bill_id}</div>
                <div style="font-size:11px; color:var(--outline);">${new Date(o.bill_date).toLocaleDateString()}</div>
            </td>
            <td>
                <div style="font-weight:500;">${o.cust_name}</div>
                <div style="font-size:11px; color:var(--outline);">${o.payment_mode_name}</div>
            </td>
            <td style="font-weight:700;">₹${parseFloat(o.rent_amount).toLocaleString()}</td>
            <td>
                <span class="status-badge status-${o.booking_status.toLowerCase() === 'active' ? 'active' : 'pending'}">
                    ${o.booking_status}
                </span>
            </td>
        </tr>
    `).join('');
}

function renderPopularProducts(products) {
    const container = document.getElementById('popular-products-list');
    container.innerHTML = products.map(p => `
        <div style="display:flex; align-items:center; gap:12px; padding:12px; background:var(--surface-container-low); border-radius:5px;">
            <div style="width:36px; height:36px; background:var(--primary-container); color:var(--primary); display:flex; align-items:center; justify-content:center; border-radius:5px;">
                <span class="material-symbols-outlined" style="font-size:18px;">package_2</span>
            </div>
            <div style="flex:1;">
                <div style="font-size:13px; font-weight:600;">${p.item_id}</div>
                <div style="font-size:11px; color:var(--outline);">${p.count} rentals | ₹${parseFloat(p.price).toLocaleString()}</div>
            </div>
        </div>
    `).join('');
}

function renderPaymentMethods(data) {
    const options = {
        series: data.map(d => d.value),
        chart: { type: 'pie', height: 250, fontFamily: 'Inter, sans-serif' },
        labels: data.map(d => d.label),
        legend: { position: 'bottom', fontSize: '11px' },
        stroke: { show: false }
    };
    
    if (paymentChart) paymentChart.destroy();
    paymentChart = new ApexCharts(document.querySelector("#paymentChart"), options);
    paymentChart.render();
}

document.addEventListener('DOMContentLoaded', fetchDashboardData);
</script>
