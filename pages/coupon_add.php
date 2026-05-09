<?php
/**
 * Add New Coupon Page
 */
?>
<div class="page-sections">
    <!-- Breadcrumbs & Header -->
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
        <div>
            <div style="display:flex; align-items:center; gap:8px; color:var(--on-surface-variant); font-size:12px; margin-bottom:8px;">
                <a href="?page=coupons" style="color:inherit; text-decoration:none;">Coupons</a>
                <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
                <span style="color:var(--primary); font-weight:600;">Add New Coupon</span>
            </div>
            <h1 style="font-size:28px; font-weight:800; letter-spacing:-0.5px;">Add New Coupon</h1>
            <p style="color:var(--on-surface-variant); font-size:14px; margin-top:4px;">Configure a new promotional campaign or discount code.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-secondary" onclick="window.location.href='?page=coupons'">Cancel</button>
            <button class="btn btn-primary" onclick="saveCoupon()">
                <span class="material-symbols-outlined">publish</span> Publish Coupon
            </button>
        </div>
    </div>

    <div class="grid-12" style="gap:24px;">
        <!-- Left Column: Main Configuration -->
        <div class="col-span-8" style="display:flex; flex-direction:column; gap:24px;">
            <!-- Primary Details -->
            <div class="card" style="padding:24px;">
                <div class="form-group" style="margin-bottom:24px;">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Coupon Code</label>
                    <div style="display:flex; gap:12px;">
                        <input type="text" id="c-code" placeholder="e.g. SUMMER2024" style="flex:1; font-family:'Geist Mono', monospace; font-weight:700; font-size:16px; text-transform:uppercase;">
                        <button class="btn btn-secondary" onclick="generateCode()" style="white-space:nowrap;">
                            <span class="material-symbols-outlined">autostop</span> Generate
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Description (Optional)</label>
                    <textarea id="c-desc" placeholder="Describe this coupon for internal reference..." style="width:100%; min-height:100px; padding:12px; border:1px solid var(--outline-variant); background:var(--surface-container-low); resize:vertical;"></textarea>
                </div>
            </div>

            <!-- Tabbed Configuration -->
            <div class="card" style="padding:0; overflow:hidden;">
                <!-- Tabs Header -->
                <div style="display:flex; background:var(--surface-container-low); border-bottom:1px solid var(--outline-variant);">
                    <button class="tab-btn active" onclick="switchTab(event, 'tab-general')">
                        <span class="material-symbols-outlined">settings</span> General
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'tab-restrictions')">
                        <span class="material-symbols-outlined">block</span> Usage Restriction
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'tab-limits')">
                        <span class="material-symbols-outlined">history</span> Usage Limits
                    </button>
                </div>

                <!-- Tabs Content -->
                <div class="tab-content-container" style="padding:24px;">
                    <!-- General Tab -->
                    <div id="tab-general" class="tab-pane active">
                        <div class="grid-2" style="gap:24px;">
                            <div class="form-group">
                                <label class="field-label">Discount Type</label>
                                <select id="c-type" class="form-select">
                                    <option value="percent">Percentage discount</option>
                                    <option value="fixed_cart">Fixed cart discount</option>
                                    <option value="fixed_product">Fixed product discount</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="field-label">Coupon Amount</label>
                                <input type="number" id="c-amount" placeholder="0.00" step="0.01">
                            </div>
                            <div class="form-group">
                                <label class="field-label">Coupon Expiry Date</label>
                                <input type="date" id="c-expiry">
                            </div>
                        </div>
                    </div>

                    <!-- Restrictions Tab -->
                    <div id="tab-restrictions" class="tab-pane">
                        <div class="grid-2" style="gap:24px; margin-bottom:24px;">
                            <div class="form-group">
                                <label class="field-label">Minimum Spend</label>
                                <input type="number" id="c-min-spend" placeholder="No minimum" step="0.01">
                            </div>
                            <div class="form-group">
                                <label class="field-label">Maximum Spend</label>
                                <input type="number" id="c-max-spend" placeholder="No maximum" step="0.01">
                            </div>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
                                <input type="checkbox" id="c-individual" style="width:18px; height:18px;">
                                <div>
                                    <span style="display:block; font-size:14px; font-weight:600;">Individual use only</span>
                                    <span style="display:block; font-size:12px; color:var(--on-surface-variant);">Check this box if the coupon cannot be used in conjunction with other coupons.</span>
                                </div>
                            </label>
                            <label style="display:flex; align-items:center; gap:12px; cursor:pointer;">
                                <input type="checkbox" id="c-exclude-sale" style="width:18px; height:18px;">
                                <div>
                                    <span style="display:block; font-size:14px; font-weight:600;">Exclude sale items</span>
                                    <span style="display:block; font-size:12px; color:var(--on-surface-variant);">Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Limits Tab -->
                    <div id="tab-limits" class="tab-pane">
                        <div class="grid-2" style="gap:24px;">
                            <div class="form-group">
                                <label class="field-label">Usage limit per coupon</label>
                                <input type="number" id="c-limit-total" placeholder="Unlimited usage">
                            </div>
                            <div class="form-group">
                                <label class="field-label">Usage limit per user</label>
                                <input type="number" id="c-limit-user" placeholder="Unlimited usage">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Meta & Sidebar -->
        <div class="col-span-4" style="display:flex; flex-direction:column; gap:24px;">
            <!-- Status Card -->
            <div class="card" style="padding:24px;">
                <h4 style="font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:16px;">Publish Status</h4>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <label class="radio-label active" style="display:flex; align-items:center; gap:12px; padding:12px; background:var(--surface-container-low); border:1px solid var(--primary); cursor:pointer;">
                        <input type="radio" name="status" value="active" checked style="accent-color:var(--primary);">
                        <div>
                            <span style="display:block; font-size:14px; font-weight:600;">Active</span>
                            <span style="display:block; font-size:12px; color:var(--on-surface-variant);">Coupon is live and usable.</span>
                        </div>
                    </label>
                    <label class="radio-label" style="display:flex; align-items:center; gap:12px; padding:12px; background:var(--surface-container-low); border:1px solid var(--outline-variant); cursor:pointer;">
                        <input type="radio" name="status" value="disabled" style="accent-color:var(--primary);">
                        <div>
                            <span style="display:block; font-size:14px; font-weight:600;">Disabled</span>
                            <span style="display:block; font-size:12px; color:var(--on-surface-variant);">Coupon is hidden and inactive.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Pro Tip Card -->
            <div class="card gradient grad-purple" style="padding:24px; color:white;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                    <span class="material-symbols-outlined">lightbulb</span>
                    <h4 style="font-size:14px; font-weight:700;">Pro Tip</h4>
                </div>
                <p style="font-size:13px; opacity:0.9; line-height:1.6;">
                    Generated coupons usually perform better when they are short and memorable. Try using prefix + year for best tracking results.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.tab-btn {
    padding: 16px 24px;
    border: none;
    background: transparent;
    color: var(--on-surface-variant);
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
}
.tab-btn:hover {
    background: var(--surface-container-high);
}
.tab-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: white;
}
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
    animation: fade-in 0.3s ease;
}
.field-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--outline);
    margin-bottom: 8px;
}
.form-select, input[type="text"], input[type="number"], input[type="date"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--outline-variant);
    background: var(--surface-container-low);
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}
.form-select:focus, input:focus {
    border-color: var(--primary);
}
.radio-label {
    transition: all 0.2s;
}
.radio-label:hover {
    background: var(--surface-container-high) !important;
}
</style>

<script>
function switchTab(event, tabId) {
    const tabs = document.querySelectorAll('.tab-pane');
    const buttons = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(tab => tab.classList.remove('active'));
    buttons.forEach(btn => btn.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = '';
    for (let i = 0; i < 8; i++) {
        code += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('c-code').value = 'NEX-' + code;
}

async function saveCoupon() {
    const code = document.getElementById('c-code').value;
    if (!code) {
        showToast('Please enter a coupon code', 'error');
        return;
    }

    const data = {
        code: code,
        description: document.getElementById('c-desc').value,
        discount_type: document.getElementById('c-type').value,
        coupon_amount: document.getElementById('c-amount').value || 0,
        expiry_date: document.getElementById('c-expiry').value || null,
        minimum_amount: document.getElementById('c-min-spend').value || null,
        maximum_amount: document.getElementById('c-max-spend').value || null,
        individual_use: document.getElementById('c-individual').checked ? 1 : 0,
        exclude_sale_items: document.getElementById('c-exclude-sale').checked ? 1 : 0,
        usage_limit: document.getElementById('c-limit-total').value || null,
        usage_limit_per_user: document.getElementById('c-limit-user').value || null,
        status: document.querySelector('input[name="status"]:checked').value
    };

    try {
        const response = await fetch('api/v1/coupons', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            showToast('Coupon published successfully');
            setTimeout(() => window.location.href = '?page=coupons', 1000);
        } else {
            showToast(result.message || 'Failed to save coupon', 'error');
        }
    } catch (error) {
        console.error('Error saving coupon:', error);
        showToast('An error occurred while saving', 'error');
    }
}
</script>
