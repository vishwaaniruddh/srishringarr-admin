<div class="page-sections">
    <!-- Order Summary Grid -->
    <div class="grid-5" id="order-stats-grid">
        <div class="stat-card gradient grad-indigo animate-fade-in-up delay-1">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">shopping_cart</span></div>
            </div>
            <p class="stat-label">Total Orders</p>
            <h3 class="stat-value" id="stat-total">-</h3>
        </div>
        <div class="stat-card gradient grad-emerald animate-fade-in-up delay-2">
            <div class="stat-card-header">
                <div class="stat-card-icon success"><span class="material-symbols-outlined">check_circle</span></div>
            </div>
            <p class="stat-label">Completed</p>
            <h3 class="stat-value" id="stat-completed">-</h3>
        </div>
        <div class="stat-card gradient grad-amber animate-fade-in-up delay-3">
            <div class="stat-card-header">
                <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">pending_actions</span></div>
            </div>
            <p class="stat-label">Processing</p>
            <h3 class="stat-value" id="stat-processing">-</h3>
        </div>
        <div class="stat-card gradient grad-rose animate-fade-in-up delay-4">
            <div class="stat-card-header">
                <div class="stat-card-icon primary" style="background:rgba(255,255,255,0.2);"><span class="material-symbols-outlined">cancel</span></div>
            </div>
            <p class="stat-label">Cancelled</p>
            <h3 class="stat-value" id="stat-cancelled">-</h3>
        </div>
        <div class="stat-card gradient grad-purple animate-fade-in-up delay-5">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">payments</span></div>
            </div>
            <p class="stat-label">Total Revenue</p>
            <h3 class="stat-value" id="stat-revenue">-</h3>
        </div>
    </div>

    <!-- Filters Row -->
    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap; margin-top:24px;">
        <div class="search-bar" style="max-width:360px;">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Search orders, customers, email..." id="order-search" onkeyup="debounceSearch(event)"/>
        </div>
        <div style="display:flex;gap:8px;">
            <div class="pill-tabs" id="status-tabs">
                <button class="pill-tab active" onclick="filterStatus('All', this)">All</button>
                <button class="pill-tab" onclick="filterStatus('Pending', this)">Processing</button>
                <button class="pill-tab" onclick="filterStatus('Completed', this)">Completed</button>
                <button class="pill-tab" onclick="filterStatus('Cancelled', this)">Cancelled</button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card animate-fade-in-up" style="margin-top:24px;">
        <div class="card-header">
            <h4 class="card-title">Order Directory</h4>
            <div style="display:flex;gap:8px;">
                <button class="btn btn-secondary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">file_download</span> Export</button>
                <button class="btn btn-primary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">add</span> New Order</button>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body">
                    <!-- Skeletons -->
                    <?php for($i=0; $i<5; $i++): ?>
                    <tr>
                        <td><div class="skeleton-line" style="width:80px;"></div></td>
                        <td><div class="skeleton-line" style="width:140px;"></div></td>
                        <td><div class="skeleton-line" style="width:100px;"></div></td>
                        <td><div class="skeleton-line" style="width:60px;"></div></td>
                        <td><div class="skeleton-line" style="width:80px;"></div></td>
                        <td><div class="skeleton-line" style="width:100px;"></div></td>
                        <td><div class="skeleton-line" style="width:40px;"></div></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
        
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:24px;padding-top:16px;border-top:1px solid var(--outline-variant);">
            <span id="pagination-info" style="font-size:12px;color:var(--on-surface-variant);"></span>
            <div style="display:flex;gap:4px;" id="pagination-controls"></div>
        </div>
    </div>
</div>

<!-- Order Detail Drawer -->
<div id="order-drawer" class="drawer">
    <div class="drawer-overlay" onclick="closeDrawer()"></div>
    <div class="drawer-content">
        <div class="drawer-header">
            <h3 id="drawer-title">Order Details</h3>
            <button class="btn-icon" onclick="closeDrawer()"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="drawer-body" id="drawer-body">
            <!-- Content injected here -->
        </div>
    </div>
</div>

<style>
.skeleton-line { height: 12px; background: var(--surface-container-high); border-radius: var(--radius-sm); margin: 8px 0; }
.drawer { position: fixed; top: 0; right: 0; width: 100%; height: 100%; z-index: 1000; visibility: hidden; pointer-events: none; }
.drawer.open { visibility: visible; pointer-events: auto; }
.drawer-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.3); backdrop-filter: blur(2px); opacity: 0; transition: opacity 0.3s; }
.drawer.open .drawer-overlay { opacity: 1; }
.drawer-content { position: absolute; top: 0; right: -480px; width: 100%; max-width: 480px; height: 100%; background: var(--surface-container-lowest); box-shadow: var(--shadow-2xl); transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; }
.drawer.open .drawer-content { right: 0; }
.drawer-header { padding: 24px; border-bottom: 1px solid var(--outline-variant); display: flex; align-items: center; justify-content: space-between; }
.drawer-body { flex: 1; overflow-y: auto; padding: 24px; }

.order-item-card { display: flex; gap: 16px; padding: 16px; background: var(--surface-container-low); border-radius: var(--radius-xl); margin-bottom: 12px; }
.order-item-img { width: 64px; height: 64px; border-radius: var(--radius-lg); object-fit: cover; background: var(--surface-container-highest); }
</style>

<script>
let currentPage = 1;
let currentSearch = '';
let currentStatus = 'All';
let searchTimer;

async function fetchOrders(page = 1, search = '', status = 'All') {
    const tableBody = document.getElementById('orders-table-body');
    const paginationInfo = document.getElementById('pagination-info');
    
    try {
        const response = await fetch(`api/v1/orders?page=${page}&search=${encodeURIComponent(search)}&status=${status}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const data = result.data;
            const orders = data.orders;
            const pag = data.pagination;
            const stats = data.stats;

            // Update stats
            document.getElementById('stat-total').innerText = stats.total;
            document.getElementById('stat-completed').innerText = stats.completed;
            document.getElementById('stat-processing').innerText = stats.processing;
            document.getElementById('stat-cancelled').innerText = stats.cancelled;
            document.getElementById('stat-revenue').innerText = stats.revenue;

            if (orders.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;">No orders found.</td></tr>';
                return;
            }

            tableBody.innerHTML = orders.map(o => `
                <tr class="animate-fade-in-up">
                    <td>
                        <a href="?page=order_view&id=${o.id}" class="text-mono-data" style="font-weight:700;color:var(--primary);cursor:pointer;text-decoration:none;">#ORD-${o.id}</a>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="customer-avatar" style="width:32px;height:32px;border-radius:50%;background:var(--primary-container);color:var(--on-primary-container);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;">${o.initials}</div>
                            <div>
                                <div class="table-user-name">${o.cust_name}</div>
                                <div class="table-user-email">${o.email}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--on-surface-variant);font-size:12px;">${o.formatted_date}</td>
                    <td><span class="chip active" style="background:var(--surface-container);color:var(--on-surface);border:1px solid var(--outline-variant);">${o.item_count} items</span></td>
                    <td class="table-amount" style="font-weight:700;color:var(--on-surface);">${o.formatted_amount}</td>
                    <td>
                        <span class="chip ${o.status.toLowerCase()}">
                            <span class="chip-dot"></span>
                            ${o.status}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:8px;">
                            <a href="?page=order_view&id=${o.id}" class="btn btn-icon" style="width:28px;height:28px;">
                                <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                            </a>
                            <button class="btn btn-icon" style="width:28px;height:28px;" onclick="downloadInvoice(${o.id})">
                                <span class="material-symbols-outlined" style="font-size:18px;">download</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            paginationInfo.innerText = `Showing ${(pag.page-1)*pag.limit+1}-${Math.min(pag.page*pag.limit, pag.total)} of ${pag.total}`;
            renderPagination(pag.page, pag.pages);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--error);">Error loading orders.</td></tr>';
    }
}

async function viewOrderItems(id) {
    const drawer = document.getElementById('order-drawer');
    const body = document.getElementById('drawer-body');
    document.getElementById('drawer-title').innerText = `Order #ORD-${id}`;
    
    drawer.classList.add('open');
    body.innerHTML = '<div style="padding:40px;text-align:center;"><span class="material-symbols-outlined animate-spin">sync</span></div>';

    try {
        const response = await fetch(`api/v1/orders/show?id=${id}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const items = result.data.items;
            body.innerHTML = `
                <div style="margin-bottom:24px;">
                    <h4 style="font-size:14px;font-weight:600;margin-bottom:12px;color:var(--outline);">Purchased Items (${items.length})</h4>
                    ${items.map(item => `
                        <div class="order-item-card">
                            <img src="${item.img_name ? 'uploads/' + item.img_name : 'assets/placeholder.png'}" class="order-item-img" onerror="this.src='https://placehold.co/100x100?text=Product'">
                            <div style="flex:1;">
                                <h5 style="font-size:13px;font-weight:600;margin-bottom:4px;">${item.product_name || 'Jewellery Item'}</h5>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:12px;color:var(--outline);">Qty: ${item.quantity || 1}</span>
                                    <span style="font-weight:700;color:var(--primary);">₹${number_format(item.price)}</span>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div style="padding-top:24px;border-top:1px solid var(--outline-variant);">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <span style="color:var(--outline);">Subtotal</span>
                        <span>₹${number_format(items.reduce((acc, curr) => acc + (curr.price * (curr.quantity || 1)), 0))}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
                        <span style="color:var(--outline);">Tax (GST)</span>
                        <span>₹0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-weight:700;font-size:16px;">
                        <span>Total</span>
                        <span style="color:var(--primary);">₹${number_format(items.reduce((acc, curr) => acc + (curr.price * (curr.quantity || 1)), 0))}</span>
                    </div>
                </div>
                <button class="btn btn-primary" style="width:100%;margin-top:32px;">Download Invoice</button>
            `;
        }
    } catch (error) {
        body.innerHTML = '<div style="padding:40px;text-align:center;color:var(--error);">Failed to load order items.</div>';
    }
}

function closeDrawer() {
    document.getElementById('order-drawer').classList.remove('open');
}

function number_format(num) {
    return Number(num).toLocaleString('en-IN');
}

function filterStatus(status, btn) {
    document.querySelectorAll('.pill-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentStatus = status;
    currentPage = 1;
    fetchOrders(currentPage, currentSearch, currentStatus);
}

function renderPagination(current, total) {
    const container = document.getElementById('pagination-controls');
    let html = `<button onclick="goToPage(${current - 1})" class="btn btn-ghost btn-sm ${current <= 1 ? 'disabled' : ''}">Previous</button>`;
    for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
        html += `<button onclick="goToPage(${i})" class="btn btn-sm" style="${i === current ? 'background:var(--primary);color:var(--on-primary);' : 'background:transparent;'}">${i}</button>`;
    }
    html += `<button onclick="goToPage(${current + 1})" class="btn btn-ghost btn-sm ${current >= total ? 'disabled' : ''}">Next</button>`;
    container.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    fetchOrders(currentPage, currentSearch, currentStatus);
}

function debounceSearch(e) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentSearch = e.target.value;
        currentPage = 1;
        fetchOrders(currentPage, currentSearch, currentStatus);
    }, 500);
}

document.addEventListener('DOMContentLoaded', () => fetchOrders());
</script>
