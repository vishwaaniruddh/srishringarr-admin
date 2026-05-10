<div class="page-sections">
    <div class="header-actions animate-fade-in-up" style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
        <a href="?page=inventory" class="btn-icon" style="background:var(--surface-container); color:var(--on-surface);">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 style="font-size:24px; font-weight:800; color:var(--on-surface);">Edit Product</h2>
            <p style="font-size:13px; color:var(--on-surface-variant);" id="product-title-tag">Loading product details...</p>
        </div>
        <div style="margin-left:auto; display:flex; gap:12px;">
            <button class="btn btn-secondary" onclick="window.history.back()">Discard</button>
            <button class="btn btn-primary" onclick="saveProduct()">
                <span class="material-symbols-outlined">save</span> Save Changes
            </button>
        </div>
    </div>

    <div class="grid-3" style="align-items: flex-start;">
        <!-- Left: Basic Info -->
        <div class="card col-span-2 animate-fade-in-up">
            <h4 class="card-title" style="margin-bottom:20px;">Basic Information</h4>
            <div class="grid-2" style="gap:20px;">
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Product Name</label>
                    <input type="text" id="p-name" class="search-bar" style="max-width:none; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Product SKU / Code</label>
                    <input type="text" id="p-code" readonly class="search-bar" style="max-width:none; background:var(--surface-container-low); opacity:0.7;">
                </div>
                <div class="form-group col-span-2">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Description</label>
                    <textarea id="p-desc" class="search-bar" style="max-width:none; background:var(--surface-container-high); min-height:120px;"></textarea>
                </div>
            </div>

            <h4 class="card-title" style="margin-top:40px; margin-bottom:20px;">Pricing & Inventory</h4>
            <div class="grid-3" style="gap:20px;">
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Sale Price (₹)</label>
                    <input type="number" id="p-price" class="search-bar" style="max-width:none; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Rent Price (₹)</label>
                    <input type="number" id="p-rent" class="search-bar" style="max-width:none; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Deposit (₹)</label>
                    <input type="number" id="p-deposit" class="search-bar" style="max-width:none; background:var(--surface-container-high);">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">Stock Quantity</label>
                    <input type="number" id="p-stock" readonly class="search-bar" style="max-width:none; background:var(--surface-container-low); opacity:0.7;">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--outline); margin-bottom:8px;">MRP (₹)</label>
                    <input type="number" id="p-mrp" readonly class="search-bar" style="max-width:none; background:var(--surface-container-low); opacity:0.7;">
                </div>
            </div>
        </div>

        <!-- Right: Media & Featured -->
        <div style="display:flex; flex-direction:column; gap:24px;">
            <div class="card animate-fade-in-up" style="animation-delay: 0.1s;">
                <h4 class="card-title" style="margin-bottom:20px;">Primary Media</h4>
                <div id="p-image-preview" style="width:100%; aspect-ratio:1; background:var(--surface-container-high); overflow:hidden; display:flex; align-items:center; justify-content:center; border:2px dashed var(--outline-variant);">
                    <span class="material-symbols-outlined" style="font-size:48px; color:var(--outline-variant);">image</span>
                </div>
                <p style="font-size:11px; color:var(--on-surface-variant); margin-top:12px; text-align:center;">Primary product visual</p>
            </div>

            <div class="card animate-fade-in-up" style="animation-delay: 0.2s;">
                <h4 class="card-title" style="margin-bottom:16px;">Display Settings</h4>
                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:var(--surface-container-low);">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <span class="material-symbols-outlined" style="color:var(--tertiary);">star</span>
                        <span style="font-size:13px; font-weight:600;">Featured Product</span>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="p-featured">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <!-- SEO Optimization Card -->
            <div class="card animate-fade-in-up" style="animation-delay: 0.3s; border-left: 4px solid var(--primary);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h4 class="card-title" style="margin:0;">SEO Optimization</h4>
                    <div id="seo-score-bubble" class="seo-score-badge score-poor" style="width:32px; height:32px; font-size:11px;">0</div>
                </div>
                
                <div class="space-y-4">
                    <div class="form-group">
                        <label style="display:block; font-size:10px; font-weight:700; color:var(--outline); margin-bottom:4px;">Focus Keyword</label>
                        <input type="text" id="seo-keyword" class="search-bar" style="max-width:none; font-size:12px; padding:8px;" placeholder="e.g. bridal set">
                    </div>
                    <div class="form-group">
                        <label style="display:block; font-size:10px; font-weight:700; color:var(--outline); margin-bottom:4px;">SEO Title</label>
                        <input type="text" id="seo-title" class="search-bar" style="max-width:none; font-size:12px; padding:8px;">
                    </div>
                    <div class="form-group">
                        <label style="display:block; font-size:10px; font-weight:700; color:var(--outline); margin-bottom:4px;">Meta Description</label>
                        <textarea id="seo-desc" class="search-bar" style="max-width:none; font-size:12px; padding:8px; min-height:80px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label style="display:block; font-size:10px; font-weight:700; color:var(--outline); margin-bottom:4px;">Meta Keywords</label>
                        <input type="text" id="seo-keywords" class="search-bar" style="max-width:none; font-size:12px; padding:8px;" placeholder="comma-separated search terms">
                    </div>
                </div>
                
                <div id="seo-feedback" style="margin-top:16px; border-top:1px solid var(--outline-variant); padding-top:12px;">
                    <!-- Real-time tips -->
                </div>
                <button type="button" class="btn btn-secondary" onclick="saveProductSeo(true)" style="width:100%; margin-top:14px; justify-content:center;">
                    <span class="material-symbols-outlined">query_stats</span>
                    Save SEO Only
                </button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="css/seo-manager.css">

<style>
.switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--outline-variant); transition: .4s; border-radius: 34px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: var(--primary); }
input:checked + .slider:before { transform: translateX(20px); }
</style>

<script src="js/SeoAnalyzer.js"></script>
<script>
const urlParams = new URLSearchParams(window.location.search);
const productId = urlParams.get('id');
const productType = urlParams.get('type');

async function loadProduct() {
    if (!productId || !productType) return;

    try {
        const response = await fetch(`api/v1/products/view?id=${productId}&type=${productType}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const p = result.data;
            document.getElementById('product-title-tag').textContent = `${p.type.charAt(0).toUpperCase() + p.type.slice(1)} • ${p.code}`;
            document.getElementById('p-name').value = p.name;
            document.getElementById('p-code').value = p.code;
            document.getElementById('p-desc').value = p.description || '';
            document.getElementById('p-price').value = p.price;
            document.getElementById('p-rent').value = p.rent;
            document.getElementById('p-deposit').value = p.deposit;
            document.getElementById('p-stock').value = p.stock || 0;
            document.getElementById('p-mrp').value = p.mrp || 0;
            document.getElementById('p-featured').checked = (p.featured == 1);

            if (p.image) {
                const imgPath = p.image.startsWith('http') ? p.image : `https://srishringarr.com/yn/uploads/${p.image}`;
                document.getElementById('p-image-preview').innerHTML = `<img src="${imgPath}" style="width:100%; height:100%; object-fit:cover;">`;
            }

            // Load SEO data
            const seoRes = await fetch(`api/v1/seo?type=${productType === 'jewellery' ? 'product' : 'garment'}&id=${productId}`);
            const seoData = await seoRes.json();
            if (seoData.status === 'success') {
                const s = seoData.data;
                document.getElementById('seo-title').value = s.meta_title || '';
                document.getElementById('seo-desc').value = s.meta_description || '';
                document.getElementById('seo-keywords').value = s.meta_keywords || '';
                document.getElementById('seo-keyword').value = s.focus_keyword || '';
                
                // Init analyzer
                [document.getElementById('seo-title'), document.getElementById('seo-desc'), document.getElementById('seo-keywords'), document.getElementById('seo-keyword')].forEach(el => {
                    el.addEventListener('input', runSeoAnalysis);
                });
                runSeoAnalysis();
            }
        }
    } catch (error) {
        console.error('Error loading product:', error);
    }
}

function runSeoAnalysis() {
    const data = {
        title: document.getElementById('seo-title').value,
        description: document.getElementById('seo-desc').value,
        focusKeyword: document.getElementById('seo-keyword').value
    };
    const result = NexusSeo.analyze(data);
    const bubble = document.getElementById('seo-score-bubble');
    bubble.innerText = result.score;
    
    let status = 'bad';
    if (result.score >= 90) status = 'best';
    else if (result.score >= 80) status = 'good';
    else if (result.score >= 50) status = 'average';

    bubble.className = `seo-score-badge pill-${status}`;
    
    document.getElementById('seo-feedback').innerHTML = result.checks.map(c => `
        <div class="checklist-item" style="padding:4px; border:none; background:transparent;">
            <span class="material-symbols-outlined text-[14px] check-${c.status}">
                ${c.status === 'pass' ? 'check_circle' : (c.status === 'warning' ? 'error' : 'cancel')}
            </span>
            <span style="font-size:11px;">${c.message}</span>
        </div>
    `).join('');
}

async function saveProduct() {
    const data = {
        name: document.getElementById('p-name').value,
        description: document.getElementById('p-desc').value,
        price: document.getElementById('p-price').value,
        rent: document.getElementById('p-rent').value,
        deposit: document.getElementById('p-deposit').value,
        featured: document.getElementById('p-featured').checked ? 1 : 0
    };

    try {
        const response = await fetch(`api/v1/products/update?id=${productId}&type=${productType}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            await saveProductSeo(false);
            showToast('Product and SEO updated successfully');
            setTimeout(() => window.location.href = '?page=inventory', 1000);
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Error saving product:', error);
        showToast('Connection error', 'error');
    }
}

async function saveProductSeo(showMessage = true) {
    const seoData = {
        page_type: productType === 'jewellery' ? 'product' : 'garment',
        entity_id: productId,
        meta_title: document.getElementById('seo-title').value,
        meta_description: document.getElementById('seo-desc').value,
        meta_keywords: document.getElementById('seo-keywords').value,
        focus_keyword: document.getElementById('seo-keyword').value,
        seo_score: document.getElementById('seo-score-bubble').innerText
    };

    const response = await fetch('api/v1/seo/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(seoData)
    });
    const result = await response.json();

    if (showMessage) {
        showToast(result.status === 'success' ? 'Product SEO updated successfully' : (result.message || 'Failed to save SEO'), result.status === 'success' ? 'success' : 'error');
    }

    return result.status === 'success';
}

document.addEventListener('DOMContentLoaded', loadProduct);
</script>
