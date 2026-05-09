<div id="customers-list-page">
    <div class="page-sections">
        <!-- Top Bar -->
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
            <div class="search-bar" style="max-width:360px;">
                <span class="material-symbols-outlined">search</span>
                <input type="text" placeholder="Search by name, email or phone..." id="customer-search" onkeyup="debounceSearch(event)"/>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <button class="btn btn-secondary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">file_download</span> Export CSV</button>
                <button class="btn btn-primary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">person_add</span> Add Customer</button>
            </div>
        </div>

        <!-- Customer Table -->
        <div class="card animate-fade-in-up" style="margin-top:24px;">
            <div class="card-header">
                <h4 class="card-title">Customer Directory</h4>
                <div style="display:flex;gap:8px;">
                    <span id="customer-count" style="font-size:12px;color:var(--outline);font-weight:600;">Loading...</span>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact Info</th>
                            <th>Location</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Tier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customers-table-body">
                        <!-- Skeletons -->
                        <?php for($i=0; $i<5; $i++): ?>
                        <tr class="skeleton-row">
                            <td><div class="skeleton-line" style="width:140px;"></div></td>
                            <td><div class="skeleton-line" style="width:180px;"></div></td>
                            <td><div class="skeleton-line" style="width:100px;"></div></td>
                            <td><div class="skeleton-line" style="width:40px;"></div></td>
                            <td><div class="skeleton-line" style="width:80px;"></div></td>
                            <td><div class="skeleton-line" style="width:60px;"></div></td>
                            <td><div class="skeleton-line" style="width:40px;"></div></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:24px;padding-top:16px;border-top:1px solid var(--outline-variant);">
                <span id="pagination-info" style="font-size:12px;color:var(--on-surface-variant);"></span>
                <div style="display:flex;gap:4px;" id="pagination-controls"></div>
            </div>
        </div>
    </div>
</div>

<!-- Customer Profile View (Initially Hidden) -->
<div id="customer-profile-page" style="display:none;">
    <div class="page-sections">
        <button class="btn btn-ghost btn-sm" onclick="showListPage()" style="margin-bottom:16px; padding-left:0;">
            <span class="material-symbols-outlined" style="font-size:18px;">arrow_back</span> Back to Directory
        </button>

        <div id="profile-content-container">
            <!-- Details will be injected here -->
        </div>
    </div>
</div>

<style>
.skeleton-row td { padding: 20px 16px; }
.skeleton-line { height: 12px; background: var(--surface-container-high); border-radius: var(--radius-sm); width: 80%; }
.customer-avatar {
    width: 32px; height: 32px; border-radius: 50%; background: var(--primary-container);
    color: var(--on-primary-container); display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700;
}
</style>

<script>
let currentPage = 1;
let currentSearch = '';
let searchTimer;

async function fetchCustomers(page = 1, search = '') {
    const tableBody = document.getElementById('customers-table-body');
    const paginationInfo = document.getElementById('pagination-info');
    
    try {
        const response = await fetch(`api/v1/customers?page=${page}&search=${encodeURIComponent(search)}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const data = result.data;
            const customers = data.customers;
            const pag = data.pagination;

            document.getElementById('customer-count').innerText = `${pag.total} total customers`;

            if (customers.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;">No customers found.</td></tr>';
                return;
            }

            tableBody.innerHTML = customers.map(c => `
                <tr class="animate-fade-in-up">
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div class="customer-avatar">${c.initials}</div>
                            <div style="display:flex;flex-direction:column;">
                                <span style="font-weight:600;font-size:14px;color:var(--on-surface);">${c.name}</span>
                                <span style="font-size:11px;color:var(--outline);">ID: #CUST-${c.id}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:2px;">
                            <span style="font-size:13px;display:flex;align-items:center;gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px;color:var(--outline);">mail</span> ${c.email}
                            </span>
                            <span style="font-size:12px;color:var(--outline);display:flex;align-items:center;gap:4px;">
                                <span class="material-symbols-outlined" style="font-size:14px;">phone</span> ${c.Mobile || 'N/A'}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13px;color:var(--on-surface-variant);">${c.city || 'N/A'}, ${c.state || ''}</span>
                    </td>
                    <td><span class="text-mono-data" style="font-weight:700;">${c.total_orders}</span></td>
                    <td><span class="table-amount" style="font-weight:700;color:var(--primary);">${c.total_spent_formatted}</span></td>
                    <td><span class="chip ${c.tier.toLowerCase()}">${c.tier}</span></td>
                    <td>
                        <button class="btn btn-ghost btn-sm" onclick="viewCustomer(${c.id})">
                            <span class="material-symbols-outlined" style="font-size:18px;">visibility</span>
                        </button>
                    </td>
                </tr>
            `).join('');

            paginationInfo.innerText = `Showing ${(pag.page-1)*pag.limit+1}-${Math.min(pag.page*pag.limit, pag.total)} of ${pag.total}`;
            renderPagination(pag.page, pag.pages);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--error);">Error loading customers.</td></tr>';
    }
}

async function viewCustomer(id) {
    document.getElementById('customers-list-page').style.display = 'none';
    document.getElementById('customer-profile-page').style.display = 'block';
    const container = document.getElementById('profile-content-container');
    container.innerHTML = '<div style="padding:40px;text-align:center;"><span class="material-symbols-outlined animate-spin">sync</span></div>';

    try {
        const response = await fetch(`api/v1/customers/show?id=${id}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const c = result.data;
            container.innerHTML = `
                <div class="profile-hero animate-fade-in-up">
                    <div class="profile-hero-content">
                        <div class="profile-avatar">
                            <img alt="${c.name}" src="https://api.dicebear.com/7.x/avataaars/svg?seed=${c.Firstname}"/>
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <h2 class="profile-name">${c.name}</h2>
                                <span class="chip active" style="background:rgba(255,255,255,0.2);color:white;"><span class="chip-dot" style="background:white;"></span>Active</span>
                            </div>
                            <p style="opacity:0.85;margin-top:4px;font-size:14px;">Customer ID: #CUST-${c.id} • Registered ${new Date(c.registration_date || Date.now()).getFullYear()}</p>
                            <div class="profile-meta">
                                <span class="profile-meta-item"><span class="material-symbols-outlined" style="font-size:16px;">mail</span> ${c.email}</span>
                                <span class="profile-meta-item"><span class="material-symbols-outlined" style="font-size:16px;">phone</span> ${c.Mobile || 'N/A'}</span>
                                <span class="profile-meta-item"><span class="material-symbols-outlined" style="font-size:16px;">location_on</span> ${c.city || 'N/A'}, ${c.state || ''}</span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-stats-grid">
                        <div class="profile-stat">
                            <div class="profile-stat-value">${c.total_spent_formatted}</div>
                            <div class="profile-stat-label">Lifetime Value</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value">${c.total_orders}</div>
                            <div class="profile-stat-label">Total Orders</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value">N/A</div>
                            <div class="profile-stat-label">Loyalty Points</div>
                        </div>
                    </div>
                </div>

                <div class="grid-3" style="margin-top:24px;">
                    <div class="col-span-2">
                        <div class="card animate-fade-in-up">
                            <div class="card-header">
                                <h4 class="card-title">Order History</h4>
                            </div>
                            <table class="data-table">
                                <thead>
                                    <tr><th>Order ID</th><th>Date</th><th>Amount</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    ${c.orders.map(o => `
                                        <tr>
                                            <td><span class="text-mono-data" style="font-weight:700;color:var(--primary);">#ORD-${o.id}</span></td>
                                            <td>${o.formatted_date}</td>
                                            <td><span class="table-amount">${o.formatted_amount}</span></td>
                                            <td><span class="chip active"><span class="chip-dot"></span>Completed</span></td>
                                        </tr>
                                    `).join('') || '<tr><td colspan="4" style="text-align:center;padding:20px;">No orders yet.</td></tr>'}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card" style="padding:24px;">
                        <h4 class="card-title" style="margin-bottom:16px;">Shipping Information</h4>
                        <div style="font-size:14px; line-height:1.6; color:var(--on-surface-variant);">
                            <p><strong>Address:</strong><br>${c.Address || 'No address provided'}</p>
                            <p style="margin-top:8px;"><strong>City:</strong> ${c.city || 'N/A'}</p>
                            <p><strong>State:</strong> ${c.state || 'N/A'}</p>
                            <p><strong>Pincode:</strong> ${c.pincode || 'N/A'}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('View error:', error);
        container.innerHTML = '<div style="padding:40px;text-align:center;color:var(--error);">Failed to load profile.</div>';
    }
}

function showListPage() {
    document.getElementById('customer-profile-page').style.display = 'none';
    document.getElementById('customers-list-page').style.display = 'block';
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
    fetchCustomers(currentPage, currentSearch);
}

function debounceSearch(e) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        currentSearch = e.target.value;
        currentPage = 1;
        fetchCustomers(currentPage, currentSearch);
    }, 500);
}

document.addEventListener('DOMContentLoaded', () => fetchCustomers());
</script>
