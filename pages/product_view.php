<?php
$id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? 'jewellery';

if (!$id) {
    echo "<div class='alert alert-error'>Product ID missing.</div>";
    return;
}
?>

<div id="loading-state" class="product-loading-state">
    <span class="material-symbols-outlined animate-spin">sync</span>
    <p>Loading product details...</p>
</div>

<div class="product-detail-page animate-fade-in" id="view-product-page" style="display:none;">
    <div class="product-detail-hero">
        <div class="product-crumb">
            <a href="?page=inventory" class="product-back-btn" aria-label="Back to inventory">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <div class="product-eyebrow">
                    Inventory / <span id="breadcrumb-type">...</span>
                </div>
                <h2 id="view-name">...</h2>
                <div class="product-subline">
                    <span>SKU <strong id="view-sku">...</strong></span>
                    <span id="view-category">...</span>
                </div>
            </div>
        </div>
        <div class="product-hero-actions">
            <button type="button" class="product-icon-btn" onclick="copyProductLink()" title="Copy link">
                <span class="material-symbols-outlined">link</span>
            </button>
            <a href="?page=product_edit&id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>&type=<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>" class="product-primary-btn">
                <span class="material-symbols-outlined">edit</span>
                Edit Product
            </a>
        </div>
    </div>

    <div class="product-detail-layout">
        <aside class="product-media-column">
            <section class="product-panel product-media-panel">
                <div class="product-image-frame">
                    <img id="main-product-image" src="" alt="" style="display:none;" onerror="showMainImagePlaceholder()">
                    <div id="image-placeholder" class="product-image-placeholder">
                        <span class="material-symbols-outlined">image_not_supported</span>
                        <p>Image unavailable</p>
                    </div>
                </div>
                <div id="thumbnail-grid" class="product-thumbnail-grid"></div>
            </section>

            <section class="product-panel">
                <div class="product-panel-title">
                    <span class="material-symbols-outlined">history</span>
                    Recent Bookings
                </div>
                <div id="bookings-container" class="booking-list"></div>
            </section>
        </aside>

        <main class="product-info-column">
            <section class="product-panel product-summary-panel">
                <div class="product-title-row">
                    <div class="product-badges">
                        <span id="badge-type" class="product-badge">...</span>
                        <span id="badge-featured" class="product-badge featured" style="display:none;">Featured</span>
                    </div>
                    <div id="availability-badge" class="availability-badge">
                        <span>Availability</span>
                        <strong id="availability-text">Available</strong>
                    </div>
                </div>

                <div class="price-metrics">
                    <div class="price-metric">
                        <span>MRP</span>
                        <strong id="view-mrp">₹0</strong>
                    </div>
                    <div class="price-metric primary">
                        <span>Selling</span>
                        <strong id="view-price">₹0</strong>
                    </div>
                    <div class="price-metric">
                        <span>Rent</span>
                        <strong id="view-rent">₹0</strong>
                    </div>
                    <div class="price-metric">
                        <span>Deposit</span>
                        <strong id="view-deposit">₹0</strong>
                    </div>
                </div>

                <div class="stock-section">
                    <div>
                        <h4>Inventory Status</h4>
                        <p><span id="view-stock">0</span> units currently available</p>
                    </div>
                    <div class="stock-meter">
                        <div id="stock-progress-bar"></div>
                    </div>
                </div>
            </section>

            <section class="product-insight-grid">
                <div class="product-insight-card">
                    <span class="material-symbols-outlined">event_repeat</span>
                    <div>
                        <p>Total Bookings</p>
                        <strong><span id="total-bookings-count">0</span> Lifetime</strong>
                    </div>
                </div>
                <div class="product-insight-card">
                    <span class="material-symbols-outlined">payments</span>
                    <div>
                        <p>Rental Yield</p>
                        <strong>₹<span id="total-rental-yield">0</span></strong>
                    </div>
                </div>
                <a id="seo-edit-link" class="product-insight-card seo-card" href="?page=product_edit&id=<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>&type=<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="material-symbols-outlined">query_stats</span>
                    <div>
                        <p>SEO Score</p>
                        <strong><span id="seo-score">0</span>/100</strong>
                    </div>
                </a>
            </section>

            <section class="product-panel">
                <div class="product-panel-title">
                    <span class="material-symbols-outlined">description</span>
                    Product Description
                </div>
                <p id="view-description" class="product-description">Loading description...</p>
            </section>

            <section class="product-panel">
                <div class="product-panel-title">
                    <span class="material-symbols-outlined">search_insights</span>
                    SEO Snapshot
                </div>
                <div class="seo-snapshot">
                    <div>
                        <span>Focus Keyword</span>
                        <strong id="seo-focus-keyword">Not set</strong>
                    </div>
                    <div>
                        <span>Meta Title</span>
                        <strong id="seo-meta-title">Not set</strong>
                    </div>
                    <div>
                        <span>Meta Description</span>
                        <strong id="seo-meta-description">Not set</strong>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<style>
.product-loading-state {
    padding: 96px;
    text-align: center;
    color: var(--outline);
}
.product-loading-state .material-symbols-outlined {
    font-size: 48px;
    color: var(--primary);
}
.product-detail-page {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.product-detail-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    padding: 22px 24px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 58%, #fff7ed 100%);
    border: 1px solid rgba(203,196,210,0.65);
    box-shadow: 0 12px 34px rgba(30,41,59,0.06);
}
.product-crumb {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.product-back-btn,
.product-icon-btn,
.product-primary-btn {
    height: 40px;
    border: 1px solid var(--outline-variant);
    background: #fff;
    color: var(--on-surface);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
}
.product-back-btn,
.product-icon-btn {
    width: 40px;
}
.product-primary-btn {
    gap: 8px;
    padding: 0 16px;
    background: var(--primary);
    color: var(--on-primary);
    border-color: var(--primary);
    font-size: 12px;
    font-weight: 800;
}
.product-eyebrow {
    color: var(--primary);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.product-detail-hero h2 {
    margin: 0;
    max-width: 900px;
    color: var(--on-surface);
    font-family: var(--font-headline);
    font-size: 26px;
    font-weight: 900;
    line-height: 1.18;
    letter-spacing: 0;
}
.product-subline {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 8px;
    color: var(--on-surface-variant);
    font-size: 12px;
}
.product-subline strong {
    color: var(--on-surface);
}
.product-hero-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
.product-detail-layout {
    display: grid;
    grid-template-columns: minmax(280px, 360px) minmax(0, 1fr);
    gap: 20px;
    align-items: start;
}
.product-media-column,
.product-info-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.product-panel {
    background: #fff;
    border: 1px solid rgba(203,196,210,0.72);
    box-shadow: 0 12px 30px rgba(30,41,59,0.05);
    padding: 18px;
}
.product-media-panel {
    padding: 12px;
}
.product-image-frame {
    aspect-ratio: 1;
    background: var(--surface-container-low);
    border: 1px solid var(--outline-variant);
    overflow: hidden;
}
.product-image-frame img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    color: var(--outline);
    background: linear-gradient(135deg, rgba(103,80,164,0.08), rgba(118,91,0,0.08)), var(--surface-container-high);
}
.product-image-placeholder .material-symbols-outlined {
    font-size: 46px;
}
.product-image-placeholder p {
    margin: 0;
    font-size: 12px;
    font-weight: 700;
}
.product-thumbnail-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 10px;
}
.product-thumbnail {
    aspect-ratio: 1;
    border: 1px solid var(--outline-variant);
    background: var(--surface-container);
    overflow: hidden;
    cursor: pointer;
}
.product-thumbnail.active {
    border-color: var(--primary);
    box-shadow: inset 0 0 0 1px var(--primary);
}
.product-thumbnail img,
.product-thumbnail-placeholder {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.product-thumbnail-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--outline);
}
.product-panel-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    color: var(--on-surface);
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.product-panel-title .material-symbols-outlined {
    color: var(--primary);
    font-size: 18px;
}
.product-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}
.product-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.product-badge {
    display: inline-flex;
    align-items: center;
    height: 26px;
    padding: 0 10px;
    background: var(--primary);
    color: var(--on-primary);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.product-badge.featured {
    background: var(--tertiary);
    color: var(--on-tertiary);
}
.availability-badge {
    min-width: 128px;
    padding: 10px 14px;
    background: var(--success-container);
    color: var(--on-success-container);
    text-align: center;
}
.availability-badge span {
    display: block;
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}
.availability-badge strong {
    display: block;
    margin-top: 2px;
    font-size: 14px;
}
.price-metrics {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 24px;
}
.price-metric {
    padding: 16px;
    background: var(--surface-container-low);
    border: 1px solid var(--outline-variant);
}
.price-metric span {
    display: block;
    color: var(--outline);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}
.price-metric strong {
    display: block;
    margin-top: 6px;
    color: var(--on-surface);
    font-size: 18px;
    font-weight: 900;
}
.price-metric.primary strong {
    color: var(--primary);
}
.stock-section {
    display: grid;
    grid-template-columns: 220px 1fr;
    gap: 20px;
    align-items: center;
    margin-top: 22px;
    padding-top: 20px;
    border-top: 1px solid var(--outline-variant);
}
.stock-section h4,
.stock-section p {
    margin: 0;
}
.stock-section h4 {
    font-size: 13px;
    font-weight: 900;
}
.stock-section p {
    margin-top: 4px;
    color: var(--on-surface-variant);
    font-size: 12px;
}
.stock-meter {
    height: 9px;
    background: var(--surface-container-high);
    overflow: hidden;
}
.stock-meter div {
    height: 100%;
    width: 0;
    background: var(--primary);
    transition: width 0.5s ease;
}
.product-insight-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.product-insight-card {
    min-height: 86px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fff;
    border: 1px solid rgba(203,196,210,0.72);
    box-shadow: 0 10px 24px rgba(30,41,59,0.05);
    color: var(--on-surface);
    text-decoration: none;
}
.product-insight-card .material-symbols-outlined {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface-container-low);
    color: var(--primary);
}
.product-insight-card p {
    margin: 0;
    color: var(--on-surface-variant);
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}
.product-insight-card strong {
    display: block;
    margin-top: 3px;
    font-size: 16px;
    font-weight: 900;
}
.product-description {
    margin: 0;
    color: var(--on-surface-variant);
    font-size: 14px;
    line-height: 1.7;
}
.seo-snapshot {
    display: grid;
    gap: 10px;
}
.seo-snapshot div {
    padding: 12px;
    background: var(--surface-container-low);
    border: 1px solid var(--outline-variant);
}
.seo-snapshot span {
    display: block;
    color: var(--outline);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
}
.seo-snapshot strong {
    display: block;
    margin-top: 5px;
    color: var(--on-surface);
    font-size: 12px;
    line-height: 1.45;
}
.booking-list {
    display: grid;
    gap: 10px;
}
.booking-empty,
.booking-item {
    padding: 12px;
    background: var(--surface-container-low);
    border: 1px solid var(--outline-variant);
}
.booking-empty {
    color: var(--outline);
    text-align: center;
    font-size: 12px;
}
.booking-item-top,
.booking-item-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.booking-item-top strong {
    font-size: 12px;
}
.booking-item-top span {
    color: var(--outline);
    font-size: 11px;
}
.booking-item-bottom {
    margin-top: 7px;
}
.booking-item-bottom strong {
    color: var(--primary);
    font-size: 13px;
}
@media (max-width: 1180px) {
    .product-detail-layout {
        grid-template-columns: 1fr;
    }
    .product-media-column {
        display: grid;
        grid-template-columns: minmax(280px, 420px) 1fr;
    }
}
@media (max-width: 820px) {
    .product-detail-hero,
    .product-title-row {
        align-items: flex-start;
        flex-direction: column;
    }
    .product-media-column,
    .price-metrics,
    .product-insight-grid,
    .stock-section {
        grid-template-columns: 1fr;
    }
    .product-hero-actions {
        width: 100%;
    }
    .product-primary-btn {
        flex: 1;
    }
}
</style>

<script>
const PRODUCT_ID = '<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>';
const PRODUCT_TYPE = '<?php echo htmlspecialchars($type, ENT_QUOTES, 'UTF-8'); ?>';

function resolveImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    let cleanPath = path.replace(/^\/+/, '');
    if (cleanPath.startsWith('yn/')) cleanPath = cleanPath.substring(3);
    if (cleanPath.startsWith('uploads/')) return 'https://srishringarr.com/yn/' + cleanPath;
    return 'https://srishringarr.com/yn/uploads/' + cleanPath;
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[char]));
}

function formatCurrency(value) {
    return '₹' + Number(value || 0).toLocaleString('en-IN');
}

function showMainImagePlaceholder() {
    document.getElementById('main-product-image').style.display = 'none';
    document.getElementById('image-placeholder').style.display = 'flex';
}

function setMainImage(url) {
    if (!url) {
        showMainImagePlaceholder();
        return;
    }

    const image = document.getElementById('main-product-image');
    image.src = url;
    image.style.display = 'block';
    document.getElementById('image-placeholder').style.display = 'none';
}

function thumbnailHtml(url, active = false) {
    if (!url) {
        return `<div class="product-thumbnail"><div class="product-thumbnail-placeholder"><span class="material-symbols-outlined">image_not_supported</span></div></div>`;
    }
    return `
        <button type="button" class="product-thumbnail ${active ? 'active' : ''}" onclick="setMainImage('${escapeHtml(url)}')">
            <img src="${escapeHtml(url)}" alt="" onerror="this.parentElement.innerHTML='<div class=&quot;product-thumbnail-placeholder&quot;><span class=&quot;material-symbols-outlined&quot;>image_not_supported</span></div>'">
        </button>
    `;
}

function copyProductLink() {
    const url = window.location.href;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => showToast('Product link copied'));
    }
}

async function fetchProduct() {
    try {
        const response = await fetch(`api/v1/products/show?id=${PRODUCT_ID}&type=${PRODUCT_TYPE}`);
        const result = await response.json();
        
        if (result.status !== 'success') {
            showToast(result.message || 'Failed to load product', 'error');
            return;
        }

        const p = result.data;
        const typeLabel = p.type.charAt(0).toUpperCase() + p.type.slice(1);
        const stock = Number(p.stock || 0);
        const seo = p.seo || {};
        const imageUrl = resolveImageUrl(p.image || '');
        const bookings = p.recent_bookings || [];

        document.getElementById('breadcrumb-type').innerText = typeLabel;
        document.getElementById('view-name').innerText = p.name || 'Untitled Product';
        document.getElementById('view-sku').innerText = p.code || '-';
        document.getElementById('view-category').innerText = [p.category, p.sub_category].filter(Boolean).join(' / ') || 'Uncategorized';
        document.getElementById('badge-type').innerText = p.type;
        if (p.featured == 1) document.getElementById('badge-featured').style.display = 'inline-flex';

        document.getElementById('view-mrp').innerText = formatCurrency(p.mrp);
        document.getElementById('view-price').innerText = formatCurrency(p.price);
        document.getElementById('view-rent').innerText = formatCurrency(p.rent);
        document.getElementById('view-deposit').innerText = formatCurrency(p.deposit);

        document.getElementById('view-stock').innerText = stock.toLocaleString('en-IN');
        document.getElementById('stock-progress-bar').style.width = Math.min(100, stock * 5) + '%';
        if (stock <= 0) {
            document.getElementById('availability-badge').style.background = 'var(--error-container)';
            document.getElementById('availability-badge').style.color = 'var(--on-error-container)';
            document.getElementById('availability-text').innerText = 'Out of Stock';
        } else if (stock <= 5) {
            document.getElementById('availability-badge').style.background = 'var(--warning-container)';
            document.getElementById('availability-badge').style.color = 'var(--on-warning-container)';
            document.getElementById('availability-text').innerText = 'Low Stock';
        }

        setMainImage(imageUrl);
        document.getElementById('main-product-image').alt = p.name || '';
        document.getElementById('thumbnail-grid').innerHTML = thumbnailHtml(imageUrl, true);

        document.getElementById('view-description').innerText = p.description || 'No product description has been added yet.';
        document.getElementById('seo-score').innerText = Number(seo.score || 0);
        document.getElementById('seo-focus-keyword').innerText = seo.focus_keyword || 'Not set';
        document.getElementById('seo-meta-title').innerText = seo.meta_title || 'Not set';
        document.getElementById('seo-meta-description').innerText = seo.meta_description || 'Not set';

        document.getElementById('total-bookings-count').innerText = bookings.length;
        let totalYield = 0;
        const bookingsContainer = document.getElementById('bookings-container');
        if (bookings.length === 0) {
            bookingsContainer.innerHTML = '<div class="booking-empty">No recent bookings found.</div>';
        } else {
            bookingsContainer.innerHTML = bookings.map(booking => {
                totalYield += Number(booking.rent_amount || 0);
                const date = booking.bill_date ? new Date(booking.bill_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';
                return `
                    <div class="booking-item">
                        <div class="booking-item-top">
                            <strong>#${escapeHtml(booking.bill_id || '-')}</strong>
                            <span>${escapeHtml(date)}</span>
                        </div>
                        <div class="booking-item-bottom">
                            <strong>${formatCurrency(booking.rent_amount)}</strong>
                            <span class="product-badge">${escapeHtml(booking.status_label || 'Booked')}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }
        document.getElementById('total-rental-yield').innerText = totalYield.toLocaleString('en-IN');

        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('view-product-page').style.display = 'flex';
    } catch (error) {
        console.error('Fetch error:', error);
        showToast('Failed to load product details', 'error');
    }
}

document.addEventListener('DOMContentLoaded', fetchProduct);
</script>
