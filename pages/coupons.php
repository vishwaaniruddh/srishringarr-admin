<div class="page-sections">
    <!-- Stats Row -->
    <div class="grid-4" style="margin-bottom:24px;">
        <div class="stat-card gradient grad-purple animate-fade-in-up">
            <div class="stat-card-header">
                <div class="stat-card-icon primary"><span class="material-symbols-outlined">sell</span></div>
            </div>
            <p class="stat-label">Active Coupons</p>
            <h3 class="stat-value" id="active-coupons-count">0</h3>
        </div>
        <div class="stat-card gradient grad-teal animate-fade-in-up">
            <div class="stat-card-header">
                <div class="stat-card-icon success"><span class="material-symbols-outlined">analytics</span></div>
            </div>
            <p class="stat-label">Total Redemptions</p>
            <h3 class="stat-value" id="total-redemptions">0</h3>
        </div>
    </div>

    <div class="card animate-fade-in-up" style="margin-bottom:24px; padding:0; overflow:hidden; border:1px solid var(--outline-variant); background:white;">
        <div style="display:flex; align-items:center; height:48px;">
            <!-- Search -->
            <div style="flex:1; display:flex; align-items:center; padding:0 16px; gap:12px;">
                <span class="material-symbols-outlined" style="color:var(--outline); font-size:20px;">search</span>
                <input type="text" id="coupon-search" placeholder="Search coupons..." onkeyup="fetchCoupons()" 
                       style="border:none; outline:none; font-size:13px; width:100%; background:transparent;">
            </div>

            <!-- Separator -->
            <div style="width:1px; height:24px; background:var(--outline-variant); opacity:0.5;"></div>

            <!-- Type Filter -->
            <div style="display:flex; align-items:center; padding:0 16px; gap:8px;">
                <select id="filter-type" onchange="fetchCoupons()" style="border:none; outline:none; font-size:13px; background:transparent; cursor:pointer; color:var(--on-surface-variant);">
                    <option value="">All Types</option>
                    <option value="percent">Percentage</option>
                    <option value="fixed_cart">Fixed Cart</option>
                    <option value="fixed_product">Fixed Product</option>
                </select>
            </div>

            <!-- Separator -->
            <div style="width:1px; height:24px; background:var(--outline-variant); opacity:0.5;"></div>

            <!-- Status Filter -->
            <div style="display:flex; align-items:center; padding:0 16px; gap:8px;">
                <select id="filter-status" onchange="fetchCoupons()" style="border:none; outline:none; font-size:13px; background:transparent; cursor:pointer; color:var(--on-surface-variant);">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="disabled">Disabled</option>
                </select>
            </div>

            <!-- Separator -->
            <div style="width:1px; height:24px; background:var(--outline-variant); opacity:0.5;"></div>

            <!-- Actions -->
            <div style="display:flex; align-items:center; padding:0 12px; gap:4px;">
                <button class="btn-icon" onclick="fetchCoupons()" title="Refresh" style="width:40px; height:40px; color:var(--outline);">
                    <span class="material-symbols-outlined" style="font-size:20px;">refresh</span>
                </button>
                <button class="btn-icon" onclick="exportCoupons()" title="Export" style="width:40px; height:40px; color:var(--outline);">
                    <span class="material-symbols-outlined">download</span>
                </button>
                <button class="btn btn-primary" onclick="window.location.href='?page=coupon_add'" style="height:36px; padding:0 16px; font-size:12px; margin-left:8px; border-radius:0;">
                    <span class="material-symbols-outlined" style="font-size:18px;">add</span> Add Coupon
                </button>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="card animate-fade-in-up">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Coupon Code</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Usage</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="coupon-table-body">
                    <tr>
                        <td colspan="9" style="text-align:center; padding:40px;">Loading coupons...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Coupon Modal -->
<div id="coupon-modal" class="modal-overlay">
    <div class="modal-content" style="max-width: 600px;">
        <h3 id="modal-title" style="margin-bottom:20px;">Create New Coupon</h3>
        <form id="coupon-form" onsubmit="saveCoupon(event)">
            <input type="hidden" id="coupon-id">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div class="form-group">
                    <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Coupon Code *</label>
                    <input type="text" id="f-code" required class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Discount Type</label>
                    <select id="f-type" class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high);">
                        <option value="percent">Percentage Discount</option>
                        <option value="fixed_cart">Fixed Cart Discount</option>
                        <option value="fixed_product">Fixed Product Discount</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Description</label>
                <textarea id="f-desc" class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high); min-height:80px;"></textarea>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div class="form-group">
                    <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Coupon Amount *</label>
                    <input type="number" step="0.01" id="f-amount" required class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Expiry Date</label>
                    <input type="date" id="f-expiry" class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high);">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:24px;">
                <div class="form-group">
                    <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Usage Limit</label>
                    <input type="number" id="f-limit" placeholder="Unlimited" class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:12px; font-weight:700; margin-bottom:4px;">Status</label>
                    <select id="f-status" class="search-bar" style="max-width:none; padding:8px 12px; font-size:14px; background:var(--surface-container-high);">
                        <option value="active">Active</option>
                        <option value="disabled">Disabled</option>
                    </select>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <button type="button" class="btn btn-secondary" onclick="closeCouponModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Coupon</button>
            </div>
        </form>
    </div>
</div>

<style>
.stat-card.gradient { border: none; }
.stat-card-header { margin-bottom: 12px; }
.stat-card-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
.stat-card-icon.primary { background: rgba(255,255,255,0.2); color: white; }
.stat-card-icon.success { background: rgba(255,255,255,0.2); color: white; }

.coupon-code-badge { 
    background: var(--primary-container); 
    color: var(--on-primary-container); 
    padding: 4px 10px; 
    border-radius: 0; 
    font-family: 'Geist Mono', monospace; 
    font-weight: 700; 
    font-size: 12px;
}
</style>

<script>
async function fetchCoupons() {
    const tableBody = document.getElementById('coupon-table-body');
    const search = document.getElementById('coupon-search').value;
    const type = document.getElementById('filter-type').value;
    const status = document.getElementById('filter-status').value;
    
    try {
        const query = new URLSearchParams({
            search: search,
            type: type,
            status: status
        }).toString();
        
        const response = await fetch(`api/v1/coupons?${query}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const coupons = result.data;
            document.getElementById('active-coupons-count').textContent = coupons.filter(c => c.status === 'active').length;
            document.getElementById('total-redemptions').textContent = coupons.reduce((acc, c) => acc + (parseInt(c.usage_count) || 0), 0);
            
            if (coupons.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center; padding:40px;">No coupons found.</td></tr>';
                return;
            }

            tableBody.innerHTML = coupons.map((c, i) => `
                <tr class="animate-fade-in-up" style="animation-delay: ${i * 0.05}s">
                    <td><span style="font-size:11px; color:var(--outline);">${i + 1}</span></td>
                    <td><span class="coupon-code-badge">${c.code}</span></td>
                    <td><div style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="${c.description}">${c.description || 'No description'}</div></td>
                    <td><span style="text-transform:capitalize;">${c.discount_type.replace('_', ' ')}</span></td>
                    <td><div style="font-weight:700;">${c.discount_type === 'percent' ? c.coupon_amount + '%' : '₹' + c.coupon_amount}</div></td>
                    <td>
                        <div style="font-size:11px;">
                            <strong>${c.usage_count}</strong> / ${c.usage_limit || '∞'}
                        </div>
                    </td>
                    <td><div style="font-size:11px;">${c.expiry_date || 'No Expiry'}</div></td>
                    <td>
                        <span class="status-badge ${c.status === 'active' ? 'status-paid' : 'status-pending'}">
                            ${c.status}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:8px; justify-content:flex-end;">
                            <button class="btn-icon" onclick="editCoupon(${c.id})" title="Edit">
                                <span class="material-symbols-outlined">edit</span>
                            </button>
                            <button class="btn-icon text-error" onclick="deleteCoupon(${c.id}, '${c.code}')" title="Delete">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function openCouponModal() {
    document.getElementById('modal-title').textContent = 'Create New Coupon';
    document.getElementById('coupon-id').value = '';
    document.getElementById('coupon-form').reset();
    document.getElementById('coupon-modal').classList.add('open');
}

function closeCouponModal() {
    document.getElementById('coupon-modal').classList.remove('open');
}

async function editCoupon(id) {
    try {
        const response = await fetch(`api/v1/coupons/${id}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const c = result.data;
            document.getElementById('modal-title').textContent = 'Edit Coupon';
            document.getElementById('coupon-id').value = c.id;
            document.getElementById('f-code').value = c.code;
            document.getElementById('f-type').value = c.discount_type;
            document.getElementById('f-desc').value = c.description;
            document.getElementById('f-amount').value = c.coupon_amount;
            document.getElementById('f-expiry').value = c.expiry_date;
            document.getElementById('f-limit').value = c.usage_limit;
            document.getElementById('f-status').value = c.status;
            
            document.getElementById('coupon-modal').classList.add('open');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function saveCoupon(e) {
    e.preventDefault();
    const id = document.getElementById('coupon-id').value;
    const data = {
        code: document.getElementById('f-code').value,
        discount_type: document.getElementById('f-type').value,
        description: document.getElementById('f-desc').value,
        coupon_amount: document.getElementById('f-amount').value,
        expiry_date: document.getElementById('f-expiry').value || null,
        usage_limit: document.getElementById('f-limit').value || null,
        status: document.getElementById('f-status').value
    };

    const url = id ? `api/v1/coupons/${id}` : 'api/v1/coupons';
    const method = id ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            showToast(result.message);
            closeCouponModal();
            fetchCoupons();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function deleteCoupon(id, code) {
    showConfirm({
        title: 'Delete Coupon?',
        message: `Are you sure you want to delete coupon "${code}"? This action cannot be undone.`,
        confirmText: 'Delete Now',
        type: 'error',
        onConfirm: async () => {
            try {
                const response = await fetch(`api/v1/coupons/${id}`, { method: 'DELETE' });
                const result = await response.json();
                if (result.status === 'success') {
                    showToast(result.message);
                    fetchCoupons();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    });
}

function exportCoupons() {
    showToast('Preparing coupon export...');
    // Implementation for CSV/Excel export would go here
}

document.addEventListener('DOMContentLoaded', fetchCoupons);
</script>
