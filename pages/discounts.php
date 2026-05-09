<?php
/**
 * Discount Management Page
 */
?>
<div class="page-sections">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
        <div>
            <h1 style="font-size:28px; font-weight:800; letter-spacing:-0.5px;">Discount Architect</h1>
            <p style="color:var(--on-surface-variant); font-size:14px; margin-top:4px;">Manage dynamic pricing rules and automated promotional campaigns.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <button class="btn btn-secondary" onclick="exportDiscounts()" title="Export Rules">
                <span class="material-symbols-outlined">download</span> Export
            </button>
            <button class="btn btn-primary" onclick="toggleArchitectForm()">
                <span class="material-symbols-outlined">add</span> Create New Rule
            </button>
        </div>
    </div>

    <!-- Architect Form (Collapsible) -->
    <div id="architect-form" class="card animate-fade-in-up" style="margin-bottom:32px; display:none; border:2px solid var(--primary-container);">
        <div style="padding:24px; border-bottom:1px solid var(--outline-variant); display:flex; justify-content:space-between; align-items:center;">
            <h3 id="form-title" style="font-size:16px; font-weight:700;">Configure New Rule</h3>
            <button class="btn-icon" onclick="toggleArchitectForm()"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div style="padding:24px;">
            <form id="rule-form" onsubmit="saveRule(event)">
                <input type="hidden" id="rule-id">
                <div class="grid-12" style="gap:24px;">
                    <!-- Scope & Target -->
                    <div class="col-span-6">
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="field-label">Rule Scope</label>
                            <select id="rule-scope" class="form-select" onchange="handleScopeChange()">
                                <option value="global">Global (All Products)</option>
                                <option value="product">Product Specific</option>
                                <option value="category">Category Specific</option>
                            </select>
                        </div>
                        <div id="target-selection-container" class="form-group" style="display:none;">
                            <label class="field-label">Target Entities</label>
                            <div class="target-search-wrapper">
                                <div id="selected-targets" class="chip-container"></div>
                                <input type="text" id="target-search" placeholder="Search to add products/categories..." autocomplete="off">
                                <div id="search-results" class="search-dropdown"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Rule Logic -->
                    <div class="col-span-6">
                        <div class="grid-2" style="gap:20px;">
                            <div class="form-group">
                                <label class="field-label">Discount Type</label>
                                <select id="rule-type" class="form-select">
                                    <option value="percent">Percentage (%)</option>
                                    <option value="fixed">Fixed Amount</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="field-label">Value</label>
                                <input type="number" id="rule-value" step="0.01" required placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label class="field-label">Priority Weight</label>
                                <input type="number" id="rule-weight" value="0" placeholder="Higher = Priority">
                            </div>
                            <div class="form-group">
                                <label class="field-label">Min Spend</label>
                                <input type="number" id="rule-threshold" step="0.01" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:24px; display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" class="btn btn-secondary" onclick="toggleArchitectForm()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-btn">Create Rule</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card animate-fade-in-up" style="margin-bottom:24px; padding:0; overflow:hidden; border:1px solid var(--outline-variant); background:white;">
        <div style="display:flex; align-items:center; height:48px;">
            <div style="flex:1; display:flex; align-items:center; padding:0 16px; gap:12px;">
                <span class="material-symbols-outlined" style="color:var(--outline); font-size:20px;">search</span>
                <input type="text" id="rule-search" placeholder="Search rules or targets..." onkeyup="fetchRules()" 
                       style="border:none; outline:none; font-size:13px; width:100%; background:transparent;">
            </div>
            <div style="width:1px; height:24px; background:var(--outline-variant); opacity:0.5;"></div>
            <div style="display:flex; align-items:center; padding:0 16px; gap:8px;">
                <select id="filter-scope" onchange="fetchRules()" style="border:none; outline:none; font-size:13px; background:transparent; cursor:pointer; color:var(--on-surface-variant);">
                    <option value="">All Scopes</option>
                    <option value="global">Global</option>
                    <option value="product">Product</option>
                    <option value="category">Category</option>
                </select>
            </div>
            <div style="width:1px; height:24px; background:var(--outline-variant); opacity:0.5;"></div>
            <div style="display:flex; align-items:center; padding:0 12px;">
                <button class="btn-icon" onclick="fetchRules()" title="Refresh" style="width:40px; height:40px; color:var(--outline);">
                    <span class="material-symbols-outlined" style="font-size:20px;">refresh</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Rules Table -->
    <div class="card animate-fade-in-up" style="overflow:hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:120px;">Scope</th>
                    <th>Target Details</th>
                    <th style="width:150px;">Rule Logic</th>
                    <th style="width:120px;">Weight</th>
                    <th style="width:180px;">Conditions</th>
                    <th style="width:100px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody id="rules-table-body">
                <!-- Data injected by JS -->
            </tbody>
        </table>
    </div>
</div>

<style>
.field-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--outline); margin-bottom: 8px; }
.form-select, input[type="text"], input[type="number"] { width: 100%; padding: 10px 12px; border: 1px solid var(--outline-variant); background: var(--surface-container-low); font-size: 14px; outline: none; border-radius: 5px; }
.form-select:focus, input:focus { border-color: var(--primary); }

.target-search-wrapper { position: relative; border: 1px solid var(--outline-variant); background: var(--surface-container-low); padding: 4px; border-radius: 5px; min-height: 42px; display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
.target-search-wrapper input { border: none; background: transparent; padding: 6px; flex: 1; min-width: 120px; }
.chip-container { display: flex; flex-wrap: wrap; gap: 6px; }
.target-chip { display: flex; align-items: center; gap: 6px; background: var(--primary-container); color: var(--on-primary-container); padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 4px; }
.target-chip .close { cursor: pointer; opacity: 0.7; }
.target-chip .close:hover { opacity: 1; }

.search-dropdown { position: absolute; top: 100%; left: 0; width: 100%; background: white; border: 1px solid var(--outline-variant); box-shadow: var(--shadow-lg); z-index: 100; display: none; margin-top: 4px; border-radius: 5px; }
.search-item { padding: 10px 16px; cursor: pointer; font-size: 13px; border-bottom: 1px solid var(--surface-container-low); }
.search-item:hover { background: var(--surface-container-low); color: var(--primary); }
.search-item:last-child { border: none; }

.rule-badge { font-family: 'Geist Mono', monospace; font-weight: 700; font-size: 12px; padding: 4px 8px; background: var(--secondary-container); color: var(--on-secondary-container); border-radius: 4px; }
.weight-badge { display: inline-flex; align-items: center; gap: 4px; background: var(--tertiary-container); color: var(--on-tertiary-container); padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700; }
</style>

<script>
let selectedTargets = [];
let searchTimeout = null;

function toggleArchitectForm(reset = true) {
    const form = document.getElementById('architect-form');
    if (reset) {
        document.getElementById('rule-form').reset();
        document.getElementById('rule-id').value = '';
        document.getElementById('save-btn').textContent = 'Create Rule';
        document.getElementById('form-title').textContent = 'Configure New Rule';
        selectedTargets = [];
        renderChips();
    }
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    if (form.style.display === 'block') form.scrollIntoView({ behavior: 'smooth' });
}

function handleScopeChange() {
    const scope = document.getElementById('rule-scope').value;
    const container = document.getElementById('target-selection-container');
    container.style.display = scope === 'global' ? 'none' : 'block';
    if (scope === 'global') selectedTargets = [];
    renderChips();
}

document.getElementById('target-search').addEventListener('input', function(e) {
    const term = e.target.value;
    const type = document.getElementById('rule-scope').value;
    const dropdown = document.getElementById('search-results');

    if (term.length < 2) {
        dropdown.style.display = 'none';
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(async () => {
        try {
            const response = await fetch(`api/v1/discounts/search-targets?type=${type}&term=${encodeURIComponent(term)}`);
            const result = await response.json();
            
            if (result.status === 'success' && result.data.length > 0) {
                dropdown.innerHTML = result.data.map(item => `
                    <div class="search-item" onclick="addTarget('${item.id}', '${item.text.replace(/'/g, "\\'")}')">
                        ${item.text}
                    </div>
                `).join('');
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        } catch (err) {
            console.error('Search error:', err);
        }
    }, 300);
});

function addTarget(id, text) {
    if (!selectedTargets.some(t => t.id === id)) {
        selectedTargets.push({ id, text });
        renderChips();
    }
    document.getElementById('target-search').value = '';
    document.getElementById('search-results').style.display = 'none';
}

function removeTarget(id) {
    selectedTargets = selectedTargets.filter(t => t.id !== id);
    renderChips();
}

function renderChips() {
    const container = document.getElementById('selected-targets');
    container.innerHTML = selectedTargets.map(t => `
        <div class="target-chip">
            ${t.text}
            <span class="material-symbols-outlined close" onclick="removeTarget('${t.id}')" style="font-size:14px;">close</span>
        </div>
    `).join('');
}

async function fetchRules() {
    const tableBody = document.getElementById('rules-table-body');
    const search = document.getElementById('rule-search').value;
    const scope = document.getElementById('filter-scope').value;
    
    try {
        const response = await fetch(`api/v1/discounts?search=${encodeURIComponent(search)}&scope=${scope}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            tableBody.innerHTML = result.data.map((rule, index) => `
                <tr class="animate-fade-in-up" style="animation-delay: ${index * 0.05}s">
                    <td style="color:var(--outline); font-weight:600;">#${index + 1}</td>
                    <td>
                        <span class="status-badge status-${rule.scope === 'global' ? 'active' : 'pending'}" style="text-transform:capitalize;">
                            ${rule.scope}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:600; color:var(--on-surface); font-size:13px;">${rule.target_display}</div>
                        <div style="font-size:11px; color:var(--outline); margin-top:2px;">ID: DIS-${rule.id.toString().padStart(4, '0')}</div>
                    </td>
                    <td>
                        <span class="rule-badge">${rule.value}${rule.type === 'percent' ? '%' : ' OFF'}</span>
                    </td>
                    <td>
                        <div class="weight-badge">
                            <span class="material-symbols-outlined" style="font-size:14px;">priority_high</span>
                            ${rule.weight}
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px;">Min Spend: <span style="font-weight:600;">₹${parseFloat(rule.threshold || 0).toLocaleString()}</span></div>
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:8px;">
                            <button class="btn-icon" onclick="editRule(${rule.id})" title="Edit Rule"><span class="material-symbols-outlined">edit</span></button>
                            <button class="btn-icon" onclick="deleteRule(${rule.id})" title="Delete Rule" style="color:var(--error);"><span class="material-symbols-outlined">delete</span></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
    } catch (err) {
        console.error('Fetch error:', err);
    }
}

async function saveRule(e) {
    e.preventDefault();
    const id = document.getElementById('rule-id').value;
    const scope = document.getElementById('rule-scope').value;
    
    const data = {
        scope: scope,
        target: scope === 'global' ? 'all' : selectedTargets.map(t => t.id).join(','),
        type: document.getElementById('rule-type').value,
        value: document.getElementById('rule-value').value,
        weight: document.getElementById('rule-weight').value || 0,
        threshold: document.getElementById('rule-threshold').value || 0
    };

    try {
        const url = id ? `api/v1/discounts/${id}` : 'api/v1/discounts';
        const method = id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        if (result.status === 'success') {
            showToast(id ? 'Rule updated successfully' : 'Rule created successfully');
            toggleArchitectForm();
            fetchRules();
        } else {
            showToast(result.message || 'Error saving rule', 'error');
        }
    } catch (err) {
        showToast('Connection error', 'error');
    }
}

async function editRule(id) {
    try {
        const response = await fetch(`api/v1/discounts/${id}`);
        const result = await response.json();
        
        if (result.status === 'success') {
            const rule = result.data;
            document.getElementById('rule-id').value = rule.id;
            document.getElementById('rule-scope').value = rule.scope;
            document.getElementById('rule-type').value = rule.type;
            document.getElementById('rule-value').value = rule.value;
            document.getElementById('rule-weight').value = rule.weight;
            document.getElementById('rule-threshold').value = rule.threshold;
            
            selectedTargets = rule.target_objects || [];
            handleScopeChange();
            
            document.getElementById('save-btn').textContent = 'Update Rule';
            document.getElementById('form-title').textContent = 'Edit Discount Rule';
            
            if (document.getElementById('architect-form').style.display === 'none') {
                toggleArchitectForm(false);
            }
        }
    } catch (err) {
        showToast('Error loading rule', 'error');
    }
}

function deleteRule(id) {
    showConfirmModal('Delete Rule', 'Are you sure you want to remove this discount rule?', async () => {
        try {
            const response = await fetch(`api/v1/discounts/${id}`, { method: 'DELETE' });
            const result = await response.json();
            if (result.status === 'success') {
                showToast('Rule deleted successfully');
                fetchRules();
            }
        } catch (err) {
            showToast('Error deleting rule', 'error');
        }
    });
}

function exportDiscounts() {
    showToast('Preparing discount rules export...');
}

document.addEventListener('DOMContentLoaded', fetchRules);
</script>
