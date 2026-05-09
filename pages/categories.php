<div class="page-sections">
    <div class="header-actions animate-fade-in-up" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="font-size:24px; font-weight:800; color:var(--on-surface);">Collection Taxonomy</h2>
            <p style="font-size:13px; color:var(--on-surface-variant);">Manage product categories and sub-collections</p>
        </div>
        <button class="btn btn-primary"><span class="material-symbols-outlined">add</span> New Category</button>
    </div>

    <div class="grid-2" style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
        <!-- Garments Column -->
        <div class="card animate-fade-in-up delay-1">
            <div class="card-header" style="border-bottom: 2px solid var(--primary-container);">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:40px; height:40px; background:var(--primary-container); color:var(--on-primary-container); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <span class="material-symbols-outlined">apparel</span>
                    </div>
                    <div>
                        <h4 class="card-title">Garment Collections</h4>
                        <p style="font-size:11px; color:var(--outline);">Primary category group</p>
                    </div>
                </div>
            </div>
            <div id="garment-list" class="taxonomy-list" style="padding:16px;">
                <div class="loading-state" style="padding:40px; text-align:center;"><span class="material-symbols-outlined animate-spin">sync</span></div>
            </div>
        </div>

        <!-- Jewellery Column -->
        <div class="card animate-fade-in-up delay-2">
            <div class="card-header" style="border-bottom: 2px solid var(--tertiary-container);">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:40px; height:40px; background:var(--tertiary-container); color:var(--on-tertiary-container); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <span class="material-symbols-outlined">diamond</span>
                    </div>
                    <div>
                        <h4 class="card-title">Jewellery Collections</h4>
                        <p style="font-size:11px; color:var(--outline);">Primary category group</p>
                    </div>
                </div>
            </div>
            <div id="jewellery-list" class="taxonomy-list" style="padding:16px;">
                <div class="loading-state" style="padding:40px; text-align:center;"><span class="material-symbols-outlined animate-spin">sync</span></div>
            </div>
        </div>
    </div>
</div>

<style>
.taxonomy-list { display: flex; flex-direction: column; gap: 8px; }
.category-group { 
    border: 1px solid var(--outline-variant); 
    border-radius: 12px; 
    overflow: hidden; 
    background: var(--surface-container-lowest);
    transition: all 0.3s ease;
}
.category-header { 
    padding: 12px 16px; 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    cursor: pointer;
    background: var(--surface-container-low);
}
.category-header:hover { background: var(--surface-container-high); }
.category-name { font-weight: 700; font-size: 14px; color: var(--on-surface); }
.sub-count { font-size: 11px; color: var(--outline); font-weight: 600; padding: 2px 8px; background: var(--surface-container-highest); border-radius: 100px; }

.subcategory-list { 
    display: none; 
    padding: 8px 16px 16px 44px; 
    flex-direction: column; 
    gap: 8px;
    border-top: 1px solid var(--outline-variant);
}
.category-group.open .subcategory-list { display: flex; }
.category-group.open .expand-icon { transform: rotate(180deg); }

.subcategory-item { 
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed var(--outline-variant);
    font-size: 13px;
    color: var(--on-surface-variant);
}
.subcategory-item:last-child { border-bottom: none; }
.subcategory-item:hover { color: var(--primary); }

.expand-icon { transition: transform 0.3s; color: var(--outline); }
</style>

<script>
async function fetchCategories() {
    try {
        const response = await fetch('api/v1/categories');
        const result = await response.json();
        
        if (result.status === 'success') {
            renderList('garment-list', result.data.garments);
            renderList('jewellery-list', result.data.jewellery);
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

function renderList(containerId, categories) {
    const container = document.getElementById(containerId);
    if (!categories || categories.length === 0) {
        container.innerHTML = '<div style="padding:20px; text-align:center; color:var(--outline);">No categories found.</div>';
        return;
    }

    container.innerHTML = categories.map(cat => `
        <div class="category-group" id="cat-${cat.id}">
            <div class="category-header" onclick="this.parentElement.classList.toggle('open')">
                <div style="display:flex; align-items:center; gap:12px;">
                    <span class="material-symbols-outlined" style="font-size:20px; color:var(--primary);">folder</span>
                    <span class="category-name">${cat.name}</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <span class="sub-count">${cat.subcategories.length} Sub</span>
                    <span class="material-symbols-outlined expand-icon">expand_more</span>
                </div>
            </div>
            <div class="subcategory-list">
                ${cat.subcategories.map(sub => `
                    <div class="subcategory-item">
                        <span>${sub.name}</span>
                        <div style="display:flex; gap:4px;">
                            <button class="btn-icon" style="width:24px; height:24px;"><span class="material-symbols-outlined" style="font-size:16px;">edit</span></button>
                            <button class="btn-icon" style="width:24px; height:24px; color:var(--error);"><span class="material-symbols-outlined" style="font-size:16px;">delete</span></button>
                        </div>
                    </div>
                `).join('')}
                ${cat.subcategories.length === 0 ? '<div style="font-size:12px; color:var(--outline); font-style:italic;">No subcategories</div>' : ''}
            </div>
        </div>
    `).join('');
}

document.addEventListener('DOMContentLoaded', fetchCategories);
</script>
