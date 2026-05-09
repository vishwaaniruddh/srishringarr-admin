<?php
$orderId = $_GET['id'] ?? 0;
?>
<div class="page-sections" id="order-view-container">
    <!-- Header with Back Button -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:16px;">
            <a href="?page=orders" class="btn btn-icon" style="background:var(--surface-container-high);">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 style="font-size:24px;font-weight:900;letter-spacing:-0.02em;">Order #ORD-<?php echo $orderId; ?></h1>
                <p id="order-date-subtitle" style="font-size:13px;color:var(--outline);margin-top:2px;">Loading details...</p>
            </div>
        </div>
            <div style="display:flex;gap:8px;" id="order-actions-top">
                <button class="btn btn-secondary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">print</span> Print Invoice</button>
                <button class="btn btn-primary btn-sm"><span class="material-symbols-outlined" style="font-size:16px;">local_shipping</span> Update Status</button>
            </div>
        </div>

    <div class="grid-3" style="align-items: flex-start;">
        <!-- Left Column: Items & Timeline -->
        <div class="col-span-2" style="display:flex;flex-direction:column;gap:24px;">
            <!-- Order Items -->
            <div class="card animate-fade-in-up">
                <div class="card-header">
                    <h4 class="card-title">Order Items</h4>
                    <span id="items-count-badge" class="chip" style="background:var(--surface-container-high);color:var(--on-surface-variant);">0 Items</span>
                </div>
                <div id="order-items-list" style="display:flex;flex-direction:column;gap:16px;">
                    <!-- Items injected here -->
                    <div style="padding:40px;text-align:center;"><span class="material-symbols-outlined animate-spin">sync</span></div>
                </div>
                
                <!-- Totals Section -->
                <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--outline-variant);display:flex;flex-direction:column;gap:12px;">
                    <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--on-surface-variant);">
                        <span>Subtotal</span>
                        <span id="order-subtotal">₹0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--on-surface-variant);">
                        <span>Shipping & Handling</span>
                        <span>₹0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--on-surface-variant);">
                        <span>Estimated Tax (GST)</span>
                        <span>₹0.00</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:900;margin-top:4px;">
                        <span>Order Total</span>
                        <span style="color:var(--primary);" id="order-total-amount">₹0.00</span>
                    </div>
                </div>
            </div>

            <!-- Shipping & Delivery Details -->
            <div class="card animate-fade-in-up delay-2">
                <div class="card-header">
                    <h4 class="card-title">Delivery Information</h4>
                    <button class="btn btn-ghost btn-sm">Edit Address</button>
                </div>
                <div class="grid-2">
                    <div>
                        <p style="font-size:11px;text-transform:uppercase;font-weight:700;color:var(--outline);letter-spacing:0.05em;margin-bottom:8px;">Shipping Method</p>
                        <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface-container-low);border-radius:var(--radius-lg);">
                            <span class="material-symbols-outlined" style="color:var(--primary);">local_shipping</span>
                            <div>
                                <p style="font-size:14px;font-weight:700;">Standard Express</p>
                                <p style="font-size:12px;color:var(--outline);">Expected within 3-5 days</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p style="font-size:11px;text-transform:uppercase;font-weight:700;color:var(--outline);letter-spacing:0.05em;margin-bottom:8px;">Tracking Number</p>
                        <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--surface-container-low);border-radius:var(--radius-lg);">
                            <span class="material-symbols-outlined" style="color:var(--tertiary);">barcode_scanner</span>
                            <div>
                                <p style="font-size:14px;font-weight:700;" id="tracking-number">Not Assigned</p>
                                <p style="font-size:12px;color:var(--outline);">Pending shipment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer & Payment -->
        <div style="display:flex;flex-direction:column;gap:24px;">
            <!-- Customer Info -->
            <div class="card animate-fade-in-up delay-1">
                <h4 class="card-title" style="margin-bottom:20px;">Customer Details</h4>
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;">
                    <div id="cust-initials" style="width:56px;height:56px;border-radius:50%;background:var(--primary-container);color:var(--on-primary-container);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:900;">-</div>
                    <div>
                        <h4 style="font-size:16px;font-weight:700;" id="cust-name-full">Loading...</h4>
                        <p style="font-size:12px;color:var(--outline);" id="cust-id-tag">ID: #CUST-000</p>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:16px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:32px;height:32px;border-radius:var(--radius-md);background:var(--surface-container-high);display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined" style="font-size:18px;color:var(--on-surface-variant);">mail</span>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:11px;color:var(--outline);text-transform:uppercase;font-weight:700;letter-spacing:0.05em;">Email</p>
                            <p style="font-size:13px;font-weight:500;" id="cust-email">-</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:32px;height:32px;border-radius:var(--radius-md);background:var(--surface-container-high);display:flex;align-items:center;justify-content:center;">
                            <span class="material-symbols-outlined" style="font-size:18px;color:var(--on-surface-variant);">phone</span>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:11px;color:var(--outline);text-transform:uppercase;font-weight:700;letter-spacing:0.05em;">Phone</p>
                            <p style="font-size:13px;font-weight:500;" id="cust-phone">-</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:flex-start;gap:12px;">
                        <div style="width:32px;height:32px;border-radius:var(--radius-md);background:var(--surface-container-high);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span class="material-symbols-outlined" style="font-size:18px;color:var(--on-surface-variant);">location_on</span>
                        </div>
                        <div style="flex:1;">
                            <p style="font-size:11px;color:var(--outline);text-transform:uppercase;font-weight:700;letter-spacing:0.05em;">Shipping Address</p>
                            <p style="font-size:13px;font-weight:500;line-height:1.6;" id="cust-address">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card animate-fade-in-up delay-3">
                <h4 class="card-title" style="margin-bottom:20px;">Payment Information</h4>
                <div style="padding:16px;background:var(--surface-container-low);border-radius:var(--radius-xl);border:1px solid var(--outline-variant);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                        <span id="payment-status-chip" class="chip active"><span class="chip-dot"></span>Paid</span>
                        <span id="payment-method" style="font-size:12px;font-weight:700;color:var(--primary);">RAZORPAY</span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;justify-content:space-between;font-size:12px;">
                            <span style="color:var(--outline);">Transaction ID</span>
                            <span style="font-weight:700;font-family:var(--font-label);" id="txn-id">-</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:12px;">
                            <span style="color:var(--outline);">Payment ID</span>
                            <span style="font-weight:700;font-family:var(--font-label);" id="pay-id">-</span>
                        </div>
                    </div>
                </div>
                <div style="margin-top:20px;">
                    <p style="font-size:11px;text-transform:uppercase;font-weight:700;color:var(--outline);letter-spacing:0.05em;margin-bottom:8px;">Invoice Summary</p>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        <div style="display:flex;justify-content:space-between;font-size:13px;">
                            <span>Items Total</span>
                            <span id="items-total-summary">₹0.00</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:900;padding-top:12px;border-top:1px dashed var(--outline-variant);">
                            <span>Paid Amount</span>
                            <span style="color:var(--success);" id="paid-amount">₹0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-item-row { display: flex; align-items: center; gap: 16px; padding: 16px; background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); transition: all var(--transition-base); }
.order-item-row:hover { border-color: var(--primary); box-shadow: var(--shadow-md); transform: translateX(4px); }
.order-item-thumb { width: 72px; height: 72px; border-radius: var(--radius-lg); object-fit: cover; background: var(--surface-container-highest); }
</style>

<script>
async function fetchOrderFullDetails() {
    const orderId = <?php echo $orderId; ?>;
    if (!orderId) return;

    try {
        const response = await fetch(`api/v1/orders/show?id=${orderId}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const data = result.data;
            const o = data.order;
            const items = data.items;

            // Update Header & Subtitle
            document.getElementById('order-date-subtitle').innerText = `Placed on ${o.formatted_date} • 3 Items total`;
            
            // Update Totals
            document.getElementById('order-subtotal').innerText = o.formatted_amount;
            document.getElementById('order-total-amount').innerText = o.formatted_amount;
            document.getElementById('items-total-summary').innerText = o.formatted_amount;
            document.getElementById('paid-amount').innerText = o.formatted_amount;
            document.getElementById('items-count-badge').innerText = `${items.length} Items`;

            // Update Items List
            const itemsList = document.getElementById('order-items-list');
            itemsList.innerHTML = items.map(item => `
                <div class="order-item-row animate-fade-in-up">
                    <img src="${item.image_url}" class="order-item-thumb" onerror="this.src='https://placehold.co/100x100?text=Product'">
                    <div style="flex:1;">
                        <h4 style="font-size:14px;font-weight:700;margin-bottom:4px;">${item.product_name || 'Standard Item'}</h4>
                        <p style="font-size:12px;color:var(--outline);display:flex;align-items:center;gap:4px;">
                            <span class="material-symbols-outlined" style="font-size:14px;">label</span> ${item.product_type ? item.product_type.toUpperCase() : 'N/A'} • SKU: #${item.sku || item.product_id}
                        </p>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:15px;font-weight:900;color:var(--on-surface);">${item.formatted_price}</p>
                        <p style="font-size:11px;color:var(--outline);margin-top:2px;">Qty: ${item.quantity || 1}</p>
                    </div>
                </div>
            `).join('');

            // Update Customer Info
            const initials = strtoupper((o.first_name || 'U').substring(0,1) + (o.last_name || 'U').substring(0,1));
            document.getElementById('cust-initials').innerText = initials;
            document.getElementById('cust-name-full').innerText = `${o.first_name} ${o.last_name}`;
            document.getElementById('cust-id-tag').innerText = `ID: #CUST-${o.user_id || 'GUEST'}`;
            document.getElementById('cust-email').innerText = o.email || 'N/A';
            document.getElementById('cust-phone').innerText = o.phone || 'N/A';
            document.getElementById('cust-address').innerText = `${o.address || ''} ${o.landmark || ''}, ${o.city || ''}, ${o.state || ''} - ${o.pincode || ''}`;

            // Update Payment Details
            document.getElementById('txn-id').innerText = o.razorpay_order_id || 'PAY_OFFLINE';
            document.getElementById('pay-id').innerText = o.razorpay_payment_id || 'PENDING';
            
            const pStatusChip = document.getElementById('payment-status-chip');
            if (o.status.toLowerCase() === 'paid' || o.status.toLowerCase() === 'completed') {
                pStatusChip.className = 'chip active';
                pStatusChip.innerHTML = '<span class="chip-dot"></span>Paid';
            } else {
                pStatusChip.className = 'chip pending';
                pStatusChip.innerHTML = '<span class="chip-dot"></span>Pending';
            }

        }
    } catch (error) {
        console.error('Error loading order details:', error);
    }
}

function strtoupper(str) {
    return str.toUpperCase();
}

document.addEventListener('DOMContentLoaded', fetchOrderFullDetails);
</script>
