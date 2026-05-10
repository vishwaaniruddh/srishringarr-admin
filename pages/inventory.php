<div class="page-sections inventory-page">
    <div class="inventory-hero animate-fade-in-up">
        <div>
            <div class="inventory-eyebrow">
                <span class="material-symbols-outlined">inventory_2</span>
                Catalog Operations
            </div>
            <h2>Inventory Management</h2>
            <p>Monitor product availability, pricing, media health, and SEO readiness from one focused workspace.</p>
        </div>
        <div class="inventory-hero-actions">
            <button class="inventory-tool-btn" onclick="init()" title="Refresh">
                <span class="material-symbols-outlined">refresh</span>
            </button>
            <button class="inventory-tool-btn" onclick="exportInventory()" title="Export">
                <span class="material-symbols-outlined">download</span>
            </button>
            <button class="inventory-primary-btn" onclick="window.location.href='?page=product_add'">
                <span class="material-symbols-outlined">add</span>
                Add Product
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="inventory-stats-grid" id="stats-container" style="margin-bottom:24px;">
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
        <div class="stat-card gradient grad-teal animate-pulse">
            <div class="stat-card-header"><div class="stat-card-icon success"></div></div>
            <p class="stat-label">SEO Optimized</p>
            <h3 class="stat-value">...</h3>
        </div>
        <div class="stat-card gradient grad-rose animate-pulse">
            <div class="stat-card-header"><div class="stat-card-icon secondary"></div></div>
            <p class="stat-label">Needs SEO</p>
            <h3 class="stat-value">...</h3>
        </div>
    </div>

    <!-- Unified Search & Filter Bar -->
    <div class="inventory-filter-panel animate-fade-in-up">
        <div class="inventory-filter-row">
            <!-- Search -->
            <div class="inventory-search-field">
                <span class="material-symbols-outlined">search</span>
                <input type="text" id="inventory-search" placeholder="Search products, SKUs..." onkeyup="debounceSearch(event)" 
                       aria-label="Search products and SKUs">
            </div>

            <!-- Stock Filter -->
            <label class="inventory-select-field">
                <span class="material-symbols-outlined">fact_check</span>
                <select id="filter-stock" onchange="applyFilters()" aria-label="Filter by stock status">
                    <option value="all">All Stock Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </label>

            <!-- Collections Filter -->
            <label class="inventory-select-field wide">
                <span class="material-symbols-outlined">category</span>
                <select id="filter-category" onchange="applyFilters()" aria-label="Filter by collection">
                    <option value="all">All Collections</option>
                    <!-- Will be populated dynamically -->
                </select>
            </label>
        </div>
    </div>

    <!-- Product Table -->
    <div class="inventory-table-card animate-fade-in-up">
        <div class="inventory-table-header">
            <div>
                <h4>Product Catalog</h4>
                <p>Review operational status and jump directly into product or SEO edits.</p>
            </div>
            <div class="inventory-view-toggle">
                <button aria-label="Grid view"><span class="material-symbols-outlined">grid_view</span></button>
                <button class="active" aria-label="List view"><span class="material-symbols-outlined">view_list</span></button>
            </div>
        </div>
        <div class="inventory-table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Pricing (R/D/M)</th>
                        <th>SEO</th>
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

.inventory-page {
    gap: 20px;
}

.inventory-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 22px 24px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 55%, #f7f2ff 100%);
    border: 1px solid rgba(203,196,210,0.45);
    box-shadow: 0 12px 34px rgba(30, 41, 59, 0.06);
}

.inventory-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--primary);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 8px;
}

.inventory-eyebrow .material-symbols-outlined {
    font-size: 17px;
}

.inventory-hero h2 {
    font-family: var(--font-headline);
    font-size: 28px;
    font-weight: 900;
    color: var(--on-surface);
    margin: 0;
    letter-spacing: 0;
}

.inventory-hero p {
    margin: 6px 0 0;
    max-width: 680px;
    color: var(--on-surface-variant);
    font-size: 13px;
    line-height: 1.55;
}

.inventory-hero-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.inventory-tool-btn,
.inventory-primary-btn {
    height: 40px;
    border: 1px solid var(--outline-variant);
    background: #fff;
    color: var(--on-surface);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.inventory-tool-btn {
    width: 40px;
}

.inventory-primary-btn {
    gap: 8px;
    padding: 0 16px;
    background: var(--primary);
    color: var(--on-primary);
    border-color: var(--primary);
    font-size: 12px;
    font-weight: 800;
}

.inventory-tool-btn:hover,
.inventory-primary-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(30,41,59,0.12);
}

.inventory-stats-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(150px, 1fr));
    gap: var(--space-4);
}

.inventory-stats-grid .stat-card {
    min-height: 112px;
    border-radius: 0;
}

.inventory-filter-panel {
    background: #fff;
    border: 1px solid rgba(203,196,210,0.7);
    box-shadow: 0 10px 26px rgba(30, 41, 59, 0.05);
    padding: 12px;
}

.inventory-filter-row {
    display: grid;
    grid-template-columns: minmax(280px, 1fr) 210px minmax(230px, 300px);
    gap: 10px;
    align-items: center;
}

.inventory-search-field,
.inventory-select-field {
    height: 42px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--surface-container-low);
    border: 1px solid transparent;
    padding: 0 12px;
    transition: all 0.2s ease;
}

.inventory-search-field:focus-within,
.inventory-select-field:focus-within {
    background: #fff;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(103,80,164,0.08);
}

.inventory-search-field .material-symbols-outlined,
.inventory-select-field .material-symbols-outlined {
    color: var(--outline);
    font-size: 19px;
}

.inventory-search-field input,
.inventory-select-field select {
    width: 100%;
    border: 0;
    outline: 0;
    background: transparent;
    color: var(--on-surface);
    font-size: 13px;
}

.inventory-select-field select {
    cursor: pointer;
}

.inventory-table-card {
    background: #fff;
    border: 1px solid rgba(203,196,210,0.7);
    box-shadow: 0 14px 34px rgba(30, 41, 59, 0.06);
    overflow: hidden;
}

.inventory-table-header {
    min-height: 64px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    border-bottom: 1px solid var(--outline-variant);
    background: linear-gradient(180deg, #fff, #fafafa);
}

.inventory-table-header h4 {
    font-family: var(--font-headline);
    font-size: 17px;
    font-weight: 900;
    margin: 0;
    color: var(--on-surface);
}

.inventory-table-header p {
    margin: 3px 0 0;
    color: var(--on-surface-variant);
    font-size: 12px;
}

.inventory-view-toggle {
    display: inline-flex;
    border: 1px solid var(--outline-variant);
    background: var(--surface-container-low);
}

.inventory-view-toggle button {
    width: 36px;
    height: 34px;
    border: 0;
    background: transparent;
    color: var(--on-surface-variant);
    cursor: pointer;
}

.inventory-view-toggle button.active {
    background: var(--primary);
    color: var(--on-primary);
}

.inventory-table-scroll {
    overflow-x: auto;
}

.inventory-table-card .data-table thead th {
    background: #fbfbfd;
    color: #635b6d;
    padding-top: 14px;
    padding-bottom: 14px;
}

.inventory-table-card .data-table tbody td {
    padding-top: 14px;
    padding-bottom: 14px;
}

.inventory-table-card .table-avatar {
    width: 42px;
    height: 42px;
    border-radius: 0;
    border: 1px solid var(--outline-variant);
}

.inventory-table-card .table-user-name {
    font-size: 12px;
    line-height: 1.35;
}

.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background:
        linear-gradient(135deg, rgba(103,80,164,0.08), rgba(118,91,0,0.08)),
        var(--surface-container-high);
    color: var(--outline);
}

.product-image-placeholder .material-symbols-outlined {
    font-size: 18px;
}

@media (max-width: 1280px) {
    .inventory-stats-grid {
        grid-template-columns: repeat(3, minmax(180px, 1fr));
    }

    .inventory-filter-row {
        grid-template-columns: 1fr 1fr;
    }

    .inventory-search-field {
        grid-column: 1 / -1;
    }
}

@media (max-width: 720px) {
    .inventory-hero,
    .inventory-table-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .inventory-hero-actions {
        width: 100%;
    }

    .inventory-primary-btn {
        flex: 1;
    }

    .inventory-stats-grid {
        grid-template-columns: 1fr;
    }

    .inventory-filter-row {
        grid-template-columns: 1fr;
    }
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

.inventory-seo-score {
    width: 58px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border-radius: var(--radius-full);
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    border: 1px solid transparent;
}

.inventory-seo-score .material-symbols-outlined {
    font-size: 15px;
}

.inventory-seo-score.seo-good {
    background: var(--success-container);
    color: var(--on-success-container);
}

.inventory-seo-score.seo-warn {
    background: var(--warning-container);
    color: var(--on-warning-container);
}

.inventory-seo-score.seo-poor {
    background: var(--error-container);
    color: var(--on-error-container);
}

.inventory-seo-score:hover {
    border-color: currentColor;
}

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
let currentStockStatus = 'all';
let currentCategory = 'all';
let searchDebounceTimer;

async function fetchCategories() {
    try {
        const response = await fetch('api/v1/categories');
        const result = await response.json();
        if (result.status === 'success') {
            const data = result.data;
            const select = document.getElementById('filter-category');
            let html = '<option value="all">All Collections</option>';

            // Apparel Group
            if (data.apparel && data.apparel.length > 0) {
                html += '<optgroup label="Apparel">';
                data.apparel.forEach(cat => {
                    html += `<option value="garment:${cat.id}">${cat.name} (${cat.count})</option>`;
                });
                html += '</optgroup>';
            }

            // Jewellery Group
            if (data.jewellery && data.jewellery.length > 0) {
                html += '<optgroup label="Jewellery">';
                data.jewellery.forEach(parent => {
                    html += `<option value="jewel_parent:${parent.id}">${parent.name} (${parent.count})</option>`;
                    if (parent.children && parent.children.length > 0) {
                        parent.children.forEach(child => {
                            html += `<option value="jewel_child:${child.id}">— ${child.name} (${child.count})</option>`;
                        });
                    }
                });
                html += '</optgroup>';
            }

            select.innerHTML = html;
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

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
                <div class="stat-card gradient grad-teal animate-fade-in-up">
                    <div class="stat-card-header">
                        <div class="stat-card-icon success"><span class="material-symbols-outlined">workspace_premium</span></div>
                    </div>
                    <p class="stat-label">SEO Optimized</p>
                    <h3 class="stat-value">${Number(stats.seo_optimized || 0).toLocaleString()}</h3>
                </div>
                <div class="stat-card gradient grad-rose animate-fade-in-up">
                    <div class="stat-card-header">
                        <div class="stat-card-icon secondary"><span class="material-symbols-outlined">manage_search</span></div>
                    </div>
                    <p class="stat-label">Needs SEO</p>
                    <h3 class="stat-value">${Number(stats.seo_needs_work || 0).toLocaleString()}</h3>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error fetching stats:', error);
    }
}

function applyFilters() {
    currentStockStatus = document.getElementById('filter-stock').value;
    currentCategory = document.getElementById('filter-category').value;
    currentPage = 1;
    fetchInventory(currentPage, currentSearch, currentCategory, currentStockStatus);
}

async function fetchInventory(page = 1, search = '', category = 'all', stock_status = 'all') {
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
            <td><div class="skeleton-line" style="width:54px;"></div></td>
            <td><div class="skeleton-line" style="width:80px;"></div></td>
            <td><div class="skeleton-line" style="width:40px;"></div></td>
        </tr>
    `).join('');

    try {
        const query = new URLSearchParams({
            page: page,
            search: search,
            category: category,
            stock_status: stock_status,
            limit: 20
        }).toString();
        
        const response = await fetch(`api/v1/products?${query}`);
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
                                ${productImageHtml(p)}
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
                        <a href="?page=product_edit&id=${p.id}&type=${p.type}" class="inventory-seo-score ${getSeoScoreClass(p.seo_score)}" title="Edit product SEO">
                            <span class="material-symbols-outlined">query_stats</span>
                            ${Number(p.seo_score || 0)}
                        </a>
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
        tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:40px;color:var(--error);">Failed to load inventory data.</td></tr>';
    }
}

function getSeoScoreClass(score) {
    score = Number(score || 0);
    if (score >= 80) return 'seo-good';
    if (score >= 50) return 'seo-warn';
    return 'seo-poor';
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[char]));
}

function productImageHtml(product) {
    if (!product.image) {
        return productImagePlaceholderHtml();
    }

    return `<img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" style="width:100%;height:100%;object-fit:cover;" onerror="renderBrokenProductImage(this)">`;
}

function productImagePlaceholderHtml() {
    return '<div class="product-image-placeholder" title="Image unavailable"><span class="material-symbols-outlined">image_not_supported</span></div>';
}

function renderBrokenProductImage(image) {
    image.outerHTML = productImagePlaceholderHtml();
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
    fetchInventory(currentPage, currentSearch, currentCategory, currentStockStatus);
}

function debounceSearch(event) {
    clearTimeout(searchDebounceTimer);
    searchDebounceTimer = setTimeout(() => {
        currentSearch = event.target.value;
        currentPage = 1;
        fetchInventory(currentPage, currentSearch, currentCategory, currentStockStatus);
    }, 500);
}

function exportInventory() {
    showToast('Preparing inventory export...');
    // Implementation for CSV/Excel export would go here
}

// Initial Load
function init() {
    fetchStats();
    fetchCategories();
    fetchInventory(currentPage, currentSearch, currentCategory, currentStockStatus);
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
