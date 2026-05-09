<div class="page-sections">
    <!-- Top Bar: Search + Filters -->
    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
        <div class="search-bar" style="max-width:360px;">
            <span class="material-symbols-outlined">search</span>
            <input type="text" placeholder="Search products, SKUs..." id="inventory-search" onkeyup="debounceSearch(event)"/>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <div class="pill-tabs" id="status-filters">
                <button class="pill-tab active" data-filter="all">All</button>
                <button class="pill-tab" data-filter="in_stock">In Stock</button>
                <button class="pill-tab" data-filter="low_stock">Low Stock</button>
                <button class="pill-tab" data-filter="out_of_stock">Out of Stock</button>
            </div>
            <button class="btn btn-secondary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">filter_list</span> Filters</button>
            <button class="btn btn-secondary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">file_download</span> Export</button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid-4" id="stats-container">
        <!-- Skeleton Stats -->
        <div class="stat-card gradient grad-indigo animate-pulse">
            <div class="stat-card-header"><div class="stat-card-icon primary"></div></div>
            <p class="stat-label">Total Products</p>
            <h3 class="stat-value">...</h3>
        </div>
        <div class="stat-card gradient grad-emerald animate-pulse">
            <div class="stat-card-header"><div class="stat-card-icon success"></div></div>
            <p class="stat-label">In Stock</p>
            <h3 class="stat-value">...</h3>
        </div>
        <div class="stat-card gradient grad-amber animate-pulse">
            <div class="stat-card-header"><div class="stat-card-icon tertiary"></div></div>
            <p class="stat-label">Low Stock</p>
            <h3 class="stat-value">...</h3>
        </div>
        <div class="stat-card gradient grad-slate animate-pulse">
            <div class="stat-card-header"><div class="stat-card-icon secondary"></div></div>
            <p class="stat-label">Out of Stock</p>
            <h3 class="stat-value">...</h3>
        </div>
    </div>

    <!-- Product Table -->
    <div class="card animate-fade-in-up">
        <div class="card-header">
            <h4 class="card-title">Product Catalog</h4>
            <div style="display:flex;gap:8px;">
                <button class="btn-icon" aria-label="Grid view"><span class="material-symbols-outlined">grid_view</span></button>
                <button class="btn-icon active" aria-label="List view"><span class="material-symbols-outlined">view_list</span></button>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Pricing (R/D/M)</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
        
        <!-- Empty State (Hidden by default) -->
        <div id="empty-state" class="empty-state" style="display:none;">
            <span class="material-symbols-outlined">inventory_2</span>
            <h3>No products found</h3>
            <p>Try adjusting your search or filters to find what you're looking for.</p>
        </div>

        <!-- Pagination -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:24px;padding-top:16px;border-top:1px solid var(--outline-variant);">
            <span id="pagination-info" style="font-size:12px;color:var(--on-surface-variant);">Loading...</span>
            <div style="display:flex;gap:4px;" id="pagination-controls">
                <!-- Pagination buttons will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .5; }
}
.skeleton-row td {
    padding: 20px 16px;
}
.skeleton-line {
    height: 12px;
    background: var(--surface-container-high);
    border-radius: var(--radius-sm);
    width: 80%;
}

/* Status Icon Badge */
.status-icon-badge {
    width: 32px;
    height: 32px;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.status-icon-badge.active { background: var(--success-container); color: var(--on-success-container); }
.status-icon-badge.pending { background: var(--warning-container); color: var(--on-warning-container); }
.status-icon-badge.inactive { background: var(--error-container); color: var(--on-error-container); }
.status-icon-badge span { font-size: 20px; }

/* Dropdown Menu */
.dropdown { position: relative; }
.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    z-index: 100;
    min-width: 180px;
    background: var(--surface-container-highest);
    border: 1px solid var(--outline-variant);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    padding: 8px;
    display: none;
    flex-direction: column;
    margin-top: 4px;
}
.dropdown-menu.show { display: flex; animation: fade-in 0.2s ease; }
.dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    color: var(--on-surface);
    text-decoration: none;
    font-size: 13px;
    border-radius: var(--radius-md);
    transition: background 0.2s;
}
.dropdown-menu a:hover { background: var(--surface-variant); }
.dropdown-menu a span { font-size: 18px; color: var(--outline); }
.dropdown-menu hr { border: none; border-top: 1px solid var(--outline-variant); margin: 4px 0; }
.dropdown-menu .text-error { color: var(--error); }
.dropdown-menu .text-error span { color: var(--error); }

@keyframes fade-in {
    from { opacity: 0; transform: translateY(-4px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
let currentPage = 1;
let currentSearch = '';
let searchDebounceTimer;

async function fetchStats() {
    try {
        const response = await fetch('api/v1/inventory-stats');
        const result = await response.json();
        if (result.status === 'success') {
            const stats = result.data;
            const container = document.getElementById('stats-container');
            container.innerHTML = `
                <div class="stat-card gradient grad-indigo animate-fade-in-up">
                    <div class="stat-card-header">
                        <div class="stat-card-icon primary"><span class="material-symbols-outlined">category</span></div>
                    </div>
                    <p class="stat-label">Total Products</p>
                    <h3 class="stat-value">${Number(stats.total).toLocaleString()}</h3>
                </div>
                <div class="stat-card gradient grad-emerald animate-fade-in-up">
                    <div class="stat-card-header">
                        <div class="stat-card-icon success"><span class="material-symbols-outlined">check_circle</span></div>
                    </div>
                    <p class="stat-label">In Stock</p>
                    <h3 class="stat-value">${Number(stats.in_stock).toLocaleString()}</h3>
                </div>
                <div class="stat-card gradient grad-amber animate-fade-in-up">
                    <div class="stat-card-header">
                        <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">warning</span></div>
                    </div>
                    <p class="stat-label">Low Stock</p>
                    <h3 class="stat-value">${Number(stats.low_stock).toLocaleString()}</h3>
                </div>
                <div class="stat-card gradient grad-slate animate-fade-in-up">
                    <div class="stat-card-header">
                        <div class="stat-card-icon secondary"><span class="material-symbols-outlined">cancel</span></div>
                    </div>
                    <p class="stat-label">Out of Stock</p>
                    <h3 class="stat-value">${Number(stats.out_of_stock).toLocaleString()}</h3>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error fetching stats:', error);
    }
}

async function fetchInventory(page = 1, search = '') {
    const tableBody = document.getElementById('inventory-table-body');
    const paginationInfo = document.getElementById('pagination-info');
    const emptyState = document.getElementById('empty-state');
    
    // Show skeleton rows
    tableBody.innerHTML = Array(5).fill(0).map(() => `
        <tr class="skeleton-row">
            <td><div class="skeleton-line" style="width:20px;"></div></td>
            <td>
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:32px;height:32px;border-radius:8px;background:var(--surface-container-high);"></div>
                    <div class="skeleton-line" style="width:150px;"></div>
                </div>
            </td>
            <td><div class="skeleton-line" style="width:60px;"></div></td>
            <td><div class="skeleton-line" style="width:80px;"></div></td>
            <td><div class="skeleton-line" style="width:30px;"></div></td>
            <td><div class="skeleton-line" style="width:100px;"></div></td>
            <td><div class="skeleton-line" style="width:80px;"></div></td>
            <td><div class="skeleton-line" style="width:40px;"></div></td>
        </tr>
    `).join('');

    try {
        const response = await fetch(`api/v1/products?page=${page}&search=${encodeURIComponent(search)}&limit=20`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const data = result.data;
            const products = data.products;
            const pagination = data.pagination;

            if (products.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                paginationInfo.innerText = 'No products found';
                renderPagination(0, 0);
                return;
            }

            emptyState.style.display = 'none';
            tableBody.innerHTML = products.map((p, i) => `
                <tr class="animate-fade-in-up" style="animation-delay: ${i * 0.05}s">
                    <td><span class="text-mono-data" style="font-size:12px;color:var(--outline);">${(pagination.page - 1) * pagination.limit + i + 1}</span></td>
                    <td>
                        <div class="table-user">
                            <div class="table-avatar" style="border-radius:var(--radius-lg); overflow:hidden; background:var(--surface-variant); position:relative;">
                                <img src="${p.image}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover;" 
                                     onerror="this.onerror=null; this.src='https://srishringarr.com/static/images/default.jpg'">
                            </div>
                            <div style="display:flex;flex-direction:column;gap:2px;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <a href="?page=product_view&id=${p.id}&type=${p.type}" class="table-user-name" style="text-decoration:none;color:inherit;">${p.name}</a>
                                    ${p.featured == 1 ? '<span class="material-symbols-outlined" style="font-size:16px;color:var(--tertiary);fill:1;" title="Featured Product">star</span>' : ''}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><span class="text-mono-data" style="color:var(--outline);">${p.code}</span></td>
                    <td>
                        <div style="display:flex; flex-direction:column;">
                            <span style="font-weight:500;">${p.category || 'Uncategorized'}</span>
                            <span style="font-size:10px; color:var(--outline);">${p.type.charAt(0).toUpperCase() + p.type.slice(1)}</span>
                        </div>
                    </td>
                    <td><span class="text-mono-data" style="font-weight:600; color: ${p.stock <= 5 ? 'var(--error)' : 'inherit'}">${p.stock}</span></td>
                    <td>
                        <div style="display:flex; flex-direction:column; gap:2px; font-size:12px;">
                            <span class="table-amount" style="color:var(--primary);">R: ${p.formatted_rent}</span>
                            <span style="color:var(--outline);">D: ${p.formatted_deposit}</span>
                            <span style="color:var(--outline); text-decoration: line-through; font-size:10px;">MRP: ${p.formatted_mrp}</span>
                        </div>
                    </td>
                    <td>
                        <div class="status-icon-badge ${p.status_class}" title="${p.status_label}">
                            <span class="material-symbols-outlined">${p.status_icon}</span>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;position:relative;">
                            <a href="?page=product_edit&id=${p.id}&type=${p.type}" class="btn-icon" aria-label="Edit">
                                <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                            </a>
                            <div class="dropdown">
                                <button class="btn-icon dropdown-trigger" onclick="toggleDropdown(event, this)">
                                    <span class="material-symbols-outlined" style="font-size:18px;">more_vert</span>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="?page=product_view&id=${p.id}&type=${p.type}"><span class="material-symbols-outlined">visibility</span> View Details</a>
                                    <a href="?page=product_edit&id=${p.id}&type=${p.type}"><span class="material-symbols-outlined">edit</span> Edit Product</a>
                                    <hr>
                                    <a href="#" class="text-error" onclick="confirmDelete(event, ${p.id}, '${p.type}', '${p.name.replace(/'/g, "\\'")}')">
                                        <span class="material-symbols-outlined">delete</span> Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            `).join('');

            paginationInfo.innerText = `Showing ${(pagination.page - 1) * pagination.limit + 1}-${Math.min(pagination.page * pagination.limit, pagination.total)} of ${Number(pagination.total).toLocaleString()} products`;
            renderPagination(pagination.page, pagination.pages);
        }
    } catch (error) {
        console.error('Error fetching inventory:', error);
        tableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--error);">Failed to load inventory data.</td></tr>';
    }
}

function confirmDelete(e, id, type, name) {
    e.preventDefault();
    closeAllDropdowns();
    
    showConfirm({
        title: 'Delete Product?',
        message: `Are you sure you want to delete "${name}"? This action is permanent and will remove all associated images.`,
        confirmText: 'Delete Now',
        type: 'error',
        onConfirm: async () => {
            const response = await fetch(`api/v1/products/delete?id=${id}&type=${type}`, {
                method: 'POST'
            });
            const result = await response.json();
            if (result.status === 'success') {
                showToast(`"${name}" has been deleted.`);
                fetchInventory(currentPage, currentSearch);
                fetchStats();
            } else {
                showToast(result.message || 'Failed to delete product', 'error');
            }
        }
    });
}

function renderPagination(current, total) {
    const container = document.getElementById('pagination-controls');
    let html = `
        <button onclick="goToPage(${current - 1})" class="btn btn-ghost btn-sm ${current <= 1 ? 'disabled' : ''}">Previous</button>
    `;
    
    for (let i = Math.max(1, current - 2); i <= Math.min(total, current + 2); i++) {
        html += `
            <button onclick="goToPage(${i})" class="btn btn-sm" style="${i === current ? 'background:var(--primary);color:var(--on-primary);' : 'background:transparent;'} border-radius:var(--radius-full);">${i}</button>
        `;
    }
    
    html += `
        <button onclick="goToPage(${current + 1})" class="btn btn-ghost btn-sm ${current >= total ? 'disabled' : ''}">Next</button>
    `;
    container.innerHTML = html;
}

function goToPage(page) {
    currentPage = page;
    fetchInventory(currentPage, currentSearch);
}

function debounceSearch(event) {
    clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(() => {
        currentSearch = event.target.value;
        currentPage = 1;
        fetchInventory(currentPage, currentSearch);
    }, 500);
}

// Initial Load
function init() {
    fetchStats();
    fetchInventory();
}

function toggleDropdown(event, button) {
    event.stopPropagation();
    const menu = button.nextElementSibling;
    const isOpen = menu.classList.contains('show');
    closeAllDropdowns();
    if (!isOpen) menu.classList.add('show');
}

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
}

window.addEventListener('click', closeAllDropdowns);

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
</script>
