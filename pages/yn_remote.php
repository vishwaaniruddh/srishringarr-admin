<div class="page-sections">
    <div class="header-actions animate-fade-in-up" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="font-size:24px; font-weight:800; color:var(--on-surface);">YN Remote Management</h2>
            <p style="font-size:13px; color:var(--on-surface-variant);">Manage and sync products from WooCommerce (yosshitaneha.com)</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-secondary" onclick="openUploadModal()">
                <span class="material-symbols-outlined">upload_file</span> Upload SKU List
            </button>
            <button class="btn btn-primary" onclick="exportAll()">
                <span class="material-symbols-outlined">download</span> Export All
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid-4" style="margin-bottom:24px;">
        <div class="stat-card gradient grad-indigo animate-fade-in-up">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">sync</span></div>
            </div>
            <p class="stat-label">Connection Status</p>
            <h3 class="stat-value" id="connection-status">Checking...</h3>
        </div>
        <div class="stat-card gradient grad-emerald animate-fade-in-up">
            <div class="stat-card-header">
                <div class="stat-card-icon success"><span class="material-symbols-outlined">inventory_2</span></div>
            </div>
            <p class="stat-label">Total Remote Items</p>
            <h3 class="stat-value" id="total-remote-count">0</h3>
        </div>
        <div class="stat-card gradient grad-amber animate-fade-in-up">
            <div class="stat-card-header">
                <div class="stat-card-icon tertiary"><span class="material-symbols-outlined">update</span></div>
            </div>
            <p class="stat-label">Last Sync</p>
            <h3 class="stat-value">Just Now</h3>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card animate-fade-in-up" style="margin-bottom:24px; padding:16px;">
        <div style="display:flex; gap:16px; align-items:center;">
            <div class="search-bar" style="flex:1; max-width: none;">
                <span class="material-symbols-outlined">search</span>
                <input type="text" id="woo-search" placeholder="Search by name or SKU..." onkeyup="handleSearch(event)">
            </div>
            <button class="btn btn-primary" onclick="fetchWooProducts()">Search</button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card animate-fade-in-up">
        <div class="table-container">
            <table class="data-table" id="woo-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:80px;">Image</th>
                        <th>Product Details</th>
                        <th>Remote ID</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Inventory</th>
                        <th>Price</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="woo-table-body">
                    <tr>
                        <td colspan="9" style="text-align:center; padding:40px;">
                            <span class="material-symbols-outlined animate-spin" style="font-size:32px;">sync</span>
                            <p>Fetching remote products...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-pagination" style="display:flex; align-items:center; justify-content:space-between; margin-top:24px; padding:16px; border-top:1px solid var(--outline-variant);">
            <div class="pagination-info" id="pagination-info" style="font-size:12px; color:var(--on-surface-variant);">Showing 0 of 0 products</div>
            <div class="pagination-controls" id="pagination-controls" style="display:flex; gap:4px;"></div>
        </div>
    </div>
</div>

<style>
.stat-card.gradient { border: none; }
.stat-card-header { margin-bottom: 12px; }
.stat-card-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.stat-card-icon.primary { background: rgba(255,255,255,0.2); color: white; }
.stat-card-icon.success { background: rgba(255,255,255,0.2); color: white; }
.stat-card-icon.tertiary { background: rgba(255,255,255,0.2); color: white; }

.remote-id-badge { background: var(--surface-container-highest); color: var(--on-surface); padding: 2px 8px; border-radius: 4px; font-family: monospace; font-size: 11px; }
.sku-text { font-weight: 700; color: var(--primary); font-family: 'Geist Mono', monospace; }

.pagination-controls .btn-icon.active { background: var(--primary); color: var(--on-primary); }
</style>

<script>
let currentWooPage = 1;
let wooSearchQuery = '';

async function fetchWooProducts() {
    const tableBody = document.getElementById('woo-table-body');
    const searchInput = document.getElementById('woo-search');
    wooSearchQuery = searchInput.value;

    try {
        const response = await fetch(`api/v1/wooproducts?page=${currentWooPage}&search=${encodeURIComponent(wooSearchQuery)}`);
        const result = await response.json();

        if (result.status === 'success') {
            document.getElementById('connection-status').textContent = 'Connected';
            document.getElementById('total-remote-count').textContent = result.data.total;
            
            renderWooTable(result.data.products, result.data.page, result.data.limit);
            renderPagination(result.data);
        } else {
            document.getElementById('connection-status').textContent = 'Disconnected';
            tableBody.innerHTML = `<tr><td colspan="9" style="text-align:center; padding:40px; color:var(--error);">
                <span class="material-symbols-outlined">error</span>
                <p>${result.message}</p>
            </td></tr>`;
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderWooTable(products, page, limit) {
    const tableBody = document.getElementById('woo-table-body');
    if (!products || products.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center; padding:40px;">No products found remotely.</td></tr>';
        return;
    }

    const startIdx = (page - 1) * limit;

    tableBody.innerHTML = products.map((p, i) => `
        <tr>
            <td><span style="font-size:12px; color:var(--outline);">${startIdx + i + 1}</span></td>
            <td>
                <div style="width:50px; height:50px; border-radius:8px; overflow:hidden; background:var(--surface-container-low);">
                    <img src="${p.image_url || 'https://via.placeholder.com/50'}" style="width:100%; height:100%; object-fit:cover;" onerror="this.src='https://via.placeholder.com/50'">
                </div>
            </td>
            <td>
                <div style="font-weight:700; color:var(--on-surface);">${p.name}</div>
                <div style="font-size:11px; color:var(--on-surface-variant);">${p.slug}</div>
            </td>
            <td><span class="remote-id-badge">#${p.ID}</span></td>
            <td><span class="sku-text">${p.sku || 'N/A'}</span></td>
            <td><span style="font-size:12px; color:var(--on-surface-variant);">${p.categories || 'Uncategorized'}</span></td>
            <td>
                <span class="status-badge ${parseInt(p.stock) > 0 ? 'status-paid' : 'status-pending'}">
                    ${p.stock || 0} in stock
                </span>
            </td>
            <td><div style="font-weight:800; color:var(--on-surface);">₹${p.price || 0}</div></td>
            <td style="text-align:right;">
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <a href="https://yosshitaneha.com/?p=${p.ID}" target="_blank" class="btn-icon" title="View on Store">
                        <span class="material-symbols-outlined">open_in_new</span>
                    </a>
                    <button class="btn-icon" title="Sync Details">
                        <span class="material-symbols-outlined">sync</span>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPagination(data) {
    const info = document.getElementById('pagination-info');
    const controls = document.getElementById('pagination-controls');
    
    const start = (data.page - 1) * data.limit + 1;
    const end = Math.min(data.page * data.limit, data.total);
    
    info.textContent = `Showing ${start}-${end} of ${data.total} products`;
    
    let html = `
        <button class="btn btn-ghost btn-sm ${data.page === 1 ? 'disabled' : ''}" onclick="changePage(${data.page - 1})">Previous</button>
    `;
    
    // Modern pagination buttons
    const maxVisible = 5;
    let startPage = Math.max(1, data.page - 2);
    let endPage = Math.min(data.total_pages, startPage + maxVisible - 1);
    
    if (endPage - startPage < maxVisible - 1) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        html += `<button class="btn btn-sm ${data.page === i ? 'active' : ''}" style="${data.page === i ? 'background:var(--primary); color:var(--on-primary);' : 'background:transparent;'} border-radius:var(--radius-full);" onclick="changePage(${i})">${i}</button>`;
    }
    
    html += `
        <button class="btn btn-ghost btn-sm ${data.page === data.total_pages ? 'disabled' : ''}" onclick="changePage(${data.page + 1})">Next</button>
    `;
    
    controls.innerHTML = html;
}

function changePage(page) {
    currentWooPage = page;
    fetchWooProducts();
}

function handleSearch(e) {
    if (e.key === 'Enter') {
        currentWooPage = 1;
        fetchWooProducts();
    }
}

function exportAll() {
    window.location.href = 'api/v1/export-wooproducts';
}

function openUploadModal() {
    alert('SKU Upload feature coming soon!');
}

document.addEventListener('DOMContentLoaded', fetchWooProducts);
</script>
