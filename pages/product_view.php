<?php
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'jewellery';

if (!$id) {
    echo "<div class='alert alert-error'>Product ID missing.</div>";
    return;
}
?>

<div class="page-sections animate-fade-in" id="view-product-page" style="display:none;">
    <!-- Breadcrumbs / Action Bar -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <a href="?page=inventory" class="btn btn-ghost btn-sm" style="padding:8px;">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div style="display:flex; flex-direction:column;">
                <span style="font-size:12px; color:var(--on-surface-variant);">Inventory / <span id="breadcrumb-type">...</span></span>
                <span style="font-size:14px; font-weight:700;" id="page-sku">...</span>
            </div>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-secondary btn-sm"><span class="material-symbols-outlined" style="font-size:18px;">share</span> Share</button>
            <a href="?page=product_edit&id=<?php echo $id; ?>&type=<?php echo $type; ?>" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">edit</span> Edit Product
            </a>
        </div>
    </div>

    <div class="grid-3" style="grid-template-columns: 1fr 2fr;">
        <!-- Product Image Gallery -->
        <div class="animate-fade-in-up">
            <div class="card" style="padding:12px;">
                <div style="aspect-ratio: 1; border-radius:var(--radius-xl); overflow:hidden; background:var(--surface-variant);">
                    <img id="main-product-image" src="" style="width:100%;height:100%;object-fit:cover; display:none;" onerror="this.onerror=null; this.src='https://srishringarr.com/static/images/default.jpg';">
                    <div id="image-placeholder" style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:var(--outline);">
                        <span class="material-symbols-outlined" style="font-size:64px;">image</span>
                    </div>
                </div>
                <!-- Thumbnails -->
                <div id="thumbnail-grid" style="display:grid; grid-template-columns: repeat(4, 1fr); gap:8px; margin-top:12px;">
                    <!-- Thumbs will be loaded here -->
                </div>
            </div>

            <!-- Recent Bookings Section -->
            <div class="card" style="margin-top:24px; padding:20px;">
                <h4 style="font-size:14px; font-weight:700; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                    <span class="material-symbols-outlined" style="font-size:18px; color:var(--primary);">history</span> Recent Bookings
                </h4>
                <div id="bookings-container" style="display:flex; flex-direction:column; gap:12px;">
                    <!-- Bookings will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div style="display:flex; flex-direction:column; gap:24px;">
            <!-- Pricing Card -->
            <div class="card animate-fade-in-up delay-1" style="padding:32px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                            <span id="badge-type" class="chip active" style="font-size:10px;">...</span>
                            <span id="badge-featured" class="chip success" style="font-size:10px; display:none;">FEATURED</span>
                        </div>
                        <h2 id="view-name" style="font-size:28px; font-weight:900; color:var(--on-surface); margin-bottom:4px;">...</h2>
                        <p style="color:var(--outline); font-family:var(--font-mono); font-size:14px;">SKU: <span id="view-sku">...</span></p>
                    </div>
                </div>

                <!-- Price Breakdown Grid -->
                <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:16px; margin-top:32px; padding:24px; background:var(--surface-container-lowest); border-radius:var(--radius-xl); border:1px solid var(--outline-variant);">
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <span style="font-size:11px; color:var(--outline); text-transform:uppercase; font-weight:700;">MRP</span>
                        <h3 id="view-mrp" style="font-size:18px; font-weight:900; color:var(--on-surface);">₹0</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <span style="font-size:11px; color:var(--outline); text-transform:uppercase; font-weight:700;">Selling</span>
                        <h3 id="view-price" style="font-size:18px; font-weight:900; color:var(--primary);">₹0</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <span style="font-size:11px; color:var(--outline); text-transform:uppercase; font-weight:700;">Rent</span>
                        <h3 id="view-rent" style="font-size:18px; font-weight:900; color:var(--secondary);">₹0</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:4px;">
                        <span style="font-size:11px; color:var(--outline); text-transform:uppercase; font-weight:700;">Deposit</span>
                        <h3 id="view-deposit" style="font-size:18px; font-weight:900; color:var(--tertiary);">₹0</h3>
                    </div>
                </div>

                <div style="margin-top:32px; padding-top:24px; border-top:1px solid var(--outline-variant);">
                    <h4 style="font-size:14px; font-weight:700; margin-bottom:12px;">Inventory Status</h4>
                    <div style="display:flex; align-items:center; gap:24px;">
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                <span style="font-size:13px;">Current Stock</span>
                                <span style="font-weight:700;"><span id="view-stock">0</span> Units</span>
                            </div>
                            <div class="stat-progress" style="height:8px;"><div id="stock-progress-bar" class="stat-progress-bar primary" style="width:0%"></div></div>
                        </div>
                        <div id="availability-badge" style="padding:12px 24px; background:var(--success-container); color:var(--on-success-container); border-radius:var(--radius-lg); text-align:center;">
                            <span style="display:block; font-size:10px; font-weight:700; text-transform:uppercase;">Availability</span>
                            <span id="availability-text" style="font-weight:900; font-size:16px;">AVAILABLE</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:32px; padding-top:24px; border-top:1px solid var(--outline-variant);">
                    <h4 style="font-size:14px; font-weight:700; margin-bottom:12px;">Product Description</h4>
                    <p id="view-description" style="font-size:14px; line-height:1.6; color:var(--on-surface-variant);">
                        Loading description...
                    </p>
                </div>
            </div>

            <!-- Quick Stats Row -->
            <div class="grid-2">
                <div class="card gradient grad-indigo" style="padding:20px; display:flex; align-items:center; gap:16px;">
                    <div class="stat-card-icon primary" style="background:rgba(255,255,255,0.2);"><span class="material-symbols-outlined" style="color:white;">event_repeat</span></div>
                    <div>
                        <p style="font-size:12px; color:rgba(255,255,255,0.8);">Total Bookings</p>
                        <h4 style="font-size:18px; font-weight:700; color:white;"><span id="total-bookings-count">0</span> Lifetime</h4>
                    </div>
                </div>
                <div class="card gradient grad-emerald" style="padding:20px; display:flex; align-items:center; gap:16px;">
                    <div class="stat-card-icon success" style="background:rgba(255,255,255,0.2);"><span class="material-symbols-outlined" style="color:white;">monetization_on</span></div>
                    <div>
                        <p style="font-size:12px; color:rgba(255,255,255,0.8);">Rental Yield</p>
                        <h4 style="font-size:18px; font-weight:700; color:white;">₹<span id="total-rental-yield">0</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading State -->
<div id="loading-state" style="padding:100px; text-align:center;">
    <span class="material-symbols-outlined animate-spin" style="font-size:48px; color:var(--primary);">sync</span>
    <p style="margin-top:16px; color:var(--outline);">Loading product details...</p>
</div>

<script>
const PRODUCT_ID = '<?php echo $id; ?>';
const PRODUCT_TYPE = '<?php echo $type; ?>';

function resolveImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    let cleanPath = path.replace(/^\/+/, '');
    if (cleanPath.startsWith('yn/')) cleanPath = cleanPath.substring(3);
    if (cleanPath.startsWith('uploads/')) return 'https://srishringarr.com/yn/' + cleanPath;
    return 'https://srishringarr.com/yn/uploads/' + cleanPath;
}

async function fetchProduct() {
    try {
        const response = await fetch(`api/v1/products/show?id=${PRODUCT_ID}&type=${PRODUCT_TYPE}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const p = result.data;
            
            // Basic Info
            document.getElementById('breadcrumb-type').innerText = p.type.charAt(0).toUpperCase() + p.type.slice(1);
            document.getElementById('page-sku').innerText = p.code;
            document.getElementById('view-name').innerText = p.name;
            document.getElementById('view-sku').innerText = p.code;
            document.getElementById('badge-type').innerText = p.type.toUpperCase();
            document.getElementById('badge-type').className = 'chip ' + (p.type === 'jewellery' ? 'active' : 'pending');
            
            if (p.featured == 1) document.getElementById('badge-featured').style.display = 'inline-flex';
            
            // Prices
            const formatCurrency = (val) => '₹' + Number(val || 0).toLocaleString();
            document.getElementById('view-mrp').innerText = formatCurrency(p.mrp);
            document.getElementById('view-price').innerText = formatCurrency(p.price);
            document.getElementById('view-rent').innerText = formatCurrency(p.rent);
            document.getElementById('view-deposit').innerText = formatCurrency(p.deposit);
            
            // Stock
            const stock = Number(p.stock || 0);
            document.getElementById('view-stock').innerText = stock;
            document.getElementById('stock-progress-bar').style.width = Math.min(100, stock * 5) + '%';
            if (stock <= 0) {
                document.getElementById('availability-badge').style.background = 'var(--error-container)';
                document.getElementById('availability-badge').style.color = 'var(--on-error-container)';
                document.getElementById('availability-text').innerText = 'OUT OF STOCK';
            }
            
            // Images
            const thumbnailGrid = document.getElementById('thumbnail-grid');
            thumbnailGrid.innerHTML = '';
            
            if (p.image) {
                const imgUrl = resolveImageUrl(p.image);
                document.getElementById('main-product-image').src = imgUrl;
                document.getElementById('main-product-image').style.display = 'block';
                document.getElementById('image-placeholder').style.display = 'none';
                
                thumbnailGrid.innerHTML += `
                    <div onclick="document.getElementById('main-product-image').src='${imgUrl}'" style="aspect-ratio:1; background:var(--surface-container); border-radius:var(--radius-md); border:2px solid var(--primary); cursor:pointer; overflow:hidden;">
                        <img src="${imgUrl}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://srishringarr.com/static/images/default.jpg'">
                    </div>
                `;
            }
            
            if (p.extra_images && p.extra_images.length > 0) {
                p.extra_images.forEach(eImg => {
                    const eUrl = resolveImageUrl(eImg);
                    thumbnailGrid.innerHTML += `
                        <div onclick="document.getElementById('main-product-image').src='${eUrl}'" style="aspect-ratio:1; background:var(--surface-container); border-radius:var(--radius-md); cursor:pointer; overflow:hidden;">
                            <img src="${eUrl}" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://srishringarr.com/static/images/default.jpg'">
                        </div>
                    `;
                });
            }

            // Description
            document.getElementById('view-description').innerText = `This premium ${p.name} is part of our curated ${p.type} collection. It features high-quality finish and has been a popular choice for weddings and special events.`;

            // Bookings
            const bookings = p.recent_bookings || [];
            document.getElementById('total-bookings-count').innerText = bookings.length;
            let totalYield = 0;
            
            const bookingsContainer = document.getElementById('bookings-container');
            if (bookings.length === 0) {
                bookingsContainer.innerHTML = '<p style="font-size:12px; color:var(--outline); text-align:center; padding:12px;">No recent bookings found.</p>';
            } else {
                bookingsContainer.innerHTML = bookings.map(b => {
                    totalYield += Number(b.rent_amount || 0);
                    let statusLabel = 'Booked', statusClass = 'pending';
                    if (b.status == 1) { statusLabel = 'Picked Up'; statusClass = 'active'; }
                    else if (b.status == 2 || b.status == 0) { statusLabel = 'Returned'; statusClass = 'success'; }
                    
                    const dateStr = b.bill_date ? new Date(b.bill_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
                    
                    return `
                        <div style="padding:10px; border-radius:var(--radius-md); background:var(--surface-container-low); border:1px solid var(--outline-variant);">
                            <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                <span style="font-weight:700; font-size:12px;">#${b.bill_id}</span>
                                <span style="font-size:11px; color:var(--outline);">${dateStr}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-size:13px; font-weight:600; color:var(--primary);">₹${Number(b.rent_amount).toLocaleString()}</span>
                                <span class="chip ${statusClass}" style="font-size:9px; height:18px; padding:0 6px;">${statusLabel}</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }
            document.getElementById('total-rental-yield').innerText = totalYield.toLocaleString();

            // Show page
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('view-product-page').style.display = 'block';
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        alert('Failed to load product details.');
    }
}

document.addEventListener('DOMContentLoaded', fetchProduct);
</script>
