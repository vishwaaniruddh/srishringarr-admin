<?php
/**
 * SEO Manager
 */
?>
<link rel="stylesheet" href="css/seo-manager.css">

<div class="seo-dashboard">
    <div class="seo-header-enhanced">
        <div class="header-content">
            <div class="header-icon-wrapper">
                <span class="material-symbols-outlined header-icon">search_insights</span>
            </div>
            <div>
                <h2 class="header-title">SEO Intelligence Centre</h2>
                <p class="header-subtitle">Manage metadata, focus keywords, and search previews from one place.</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn-icon" onclick="loadPages()" title="Refresh">
                <span class="material-symbols-outlined">refresh</span>
            </button>
            <button class="btn-primary-action" onclick="openPageModal()">
                <span class="material-symbols-outlined">add</span>
                Add Page
            </button>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><span class="material-symbols-outlined">description</span></div>
            <div class="stat-content">
                <div class="stat-label">Tracked Pages</div>
                <div class="stat-value" id="stat-total-pages">0</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><span class="material-symbols-outlined">trending_up</span></div>
            <div class="stat-content">
                <div class="stat-label">Average Score</div>
                <div class="stat-value stat-value-green" id="stat-avg-score">0%</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon stat-icon-orange"><span class="material-symbols-outlined">warning</span></div>
            <div class="stat-content">
                <div class="stat-label">Needs Attention</div>
                <div class="stat-value stat-value-orange" id="stat-issues">0</div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="enhanced-table">
            <thead>
                <tr>
                    <th><div class="th-content"><span class="material-symbols-outlined th-icon">article</span>Page</div></th>
                    <th><div class="th-content"><span class="material-symbols-outlined th-icon">link</span>URL Path</div></th>
                    <th><div class="th-content"><span class="material-symbols-outlined th-icon">key</span>Focus Keyword</div></th>
                    <th><div class="th-content"><span class="material-symbols-outlined th-icon">analytics</span>Score</div></th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="pages-list">
                <tr><td colspan="5" style="padding:32px;text-align:center;color:var(--text-secondary);">Loading SEO pages...</td></tr>
            </tbody>
        </table>
    </div>

    <div class="settings-section">
        <div class="settings-header">
            <div class="settings-icon-wrapper"><span class="material-symbols-outlined">settings</span></div>
            <div>
                <h3 class="settings-title">Site-Wide SEO Configuration</h3>
                <p class="settings-subtitle">Used as the brand suffix and fallback settings for search metadata.</p>
            </div>
        </div>
        <form id="seo-settings-form" class="settings-form">
            <div class="form-group-enhanced">
                <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">business</span>Website Brand Name</label>
                <input type="text" name="site_name" class="form-input-enhanced" placeholder="Sri Shringarr">
            </div>
            <div class="form-group-enhanced">
                <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">more_vert</span>Title Separator</label>
                <input type="text" name="title_separator" class="form-input-enhanced" placeholder="|" maxlength="3">
            </div>
            <div class="form-group-enhanced">
                <button type="submit" class="btn-primary-full"><span class="material-symbols-outlined">save</span>Save Settings</button>
            </div>
        </form>
    </div>
</div>

<div id="nx-seo-meta-modal" class="nx-seo-overlay hidden" onclick="closePageModal(event)">
    <div class="seo-modal-container" onclick="event.stopPropagation()">
        <div class="seo-modal-header">
            <div class="flex items-center gap-3">
                <div class="modal-icon"><span class="material-symbols-outlined">search</span></div>
                <div>
                    <h3 class="modal-title" id="nx-modal-title">Page Meta Information</h3>
                    <p class="modal-subtitle">Tune the title, description, keywords, and preview.</p>
                </div>
            </div>
            <button class="modal-close-btn" onclick="closePageModal(null)" title="Close">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form id="nx-seo-page-form" class="seo-modal-body">
            <input type="hidden" name="page_type" value="page">
            <input type="hidden" id="nx-edit-id">

            <div id="nx-path-input-group" class="form-group-enhanced hidden">
                <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">link</span>Target Page File</label>
                <input type="text" name="url_slug" id="nx-url-slug" class="form-input-enhanced" placeholder="about_us.php">
                <p class="form-help-text">Use the root website PHP filename, such as index.php or contact_us.php.</p>
            </div>

            <div class="form-group-enhanced">
                <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">title</span>SEO Title Tag</label>
                <input type="text" name="meta_title" id="nx-meta-title" class="form-input-enhanced" maxlength="70" placeholder="An engaging search result title">
                <div class="form-meta-row">
                    <span id="nx-title-hint" class="form-help-text">0 / 60 characters optimal</span>
                    <span id="nx-title-status" class="status-indicator"></span>
                </div>
            </div>

            <div class="form-group-enhanced">
                <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">description</span>Meta Description</label>
                <textarea name="meta_description" id="nx-meta-desc" class="form-input-enhanced" rows="4" maxlength="200" placeholder="A concise summary that encourages clicks from search results."></textarea>
                <div class="form-meta-row">
                    <span id="nx-desc-hint" class="form-help-text">0 / 160 characters optimal</span>
                    <span id="nx-desc-status" class="status-indicator"></span>
                </div>
            </div>

            <div class="form-group-enhanced">
                <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">sell</span>Meta Keywords</label>
                <input type="text" name="meta_keywords" id="nx-meta-keywords" class="form-input-enhanced" placeholder="bridal jewellery, rentals, designer apparel">
            </div>

            <div class="form-row-2col">
                <div class="form-group-enhanced">
                    <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">key</span>Focus Keyword</label>
                    <input type="text" name="focus_keyword" id="nx-focus-keyword" class="form-input-enhanced" placeholder="bridal jewellery rental">
                </div>
                <div class="form-group-enhanced">
                    <label class="form-label-enhanced"><span class="material-symbols-outlined label-icon">speed</span>SEO Score</label>
                    <div id="nx-score-display" class="score-display score-poor">
                        <div id="nx-modal-score" class="score-value">0</div>
                        <div class="score-label">/ 100</div>
                    </div>
                </div>
            </div>

            <div class="google-preview">
                <div class="preview-label">Google Preview</div>
                <div id="snippet-url" class="snippet-url">https://srishringarr.com/</div>
                <div id="snippet-title" class="snippet-title">SEO title preview</div>
                <div id="snippet-desc" class="snippet-desc">Meta description preview will appear here as you type.</div>
            </div>

            <div id="seo-analysis-summary" class="seo-summary-box">
                <div class="summary-header">
                    <span class="material-symbols-outlined">checklist</span>
                    <span>Optimization Checklist</span>
                </div>
                <ul id="seo-suggestions" class="suggestion-list"></ul>
            </div>
        </form>

        <div class="seo-modal-footer">
            <button type="button" class="btn-secondary" onclick="closePageModal(null)">
                <span class="material-symbols-outlined">close</span>Cancel
            </button>
            <button type="submit" form="nx-seo-page-form" class="btn-primary">
                <span class="material-symbols-outlined">save</span>Save Meta Information
            </button>
        </div>
    </div>
</div>

<script src="js/SeoAnalyzer.js"></script>
<script>
const defaultPages = [
    { name: 'Home Page', path: 'index.php' },
    { name: 'About Us', path: 'about_us.php' },
    { name: 'Contact Us', path: 'contact_us.php' },
    { name: 'FAQ Support', path: 'faq.php' }
];
let currentSlug = '';

document.addEventListener('DOMContentLoaded', () => {
    loadSettings();
    loadPages();

    ['nx-url-slug', 'nx-meta-title', 'nx-meta-desc', 'nx-meta-keywords', 'nx-focus-keyword'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', runAnalysis);
    });

    document.getElementById('nx-seo-page-form').addEventListener('submit', savePageSeo);
    document.getElementById('seo-settings-form').addEventListener('submit', saveSettings);
});

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, char => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    }[char]));
}

function getScoreClass(score) {
    if (score >= 80) return 'score-pass';
    if (score >= 50) return 'score-warn';
    return 'score-fail';
}

async function loadSettings() {
    const res = await fetch('api/v1/seo/settings').then(r => r.json()).catch(() => null);
    if (!res || res.status !== 'success') return;
    const form = document.getElementById('seo-settings-form');
    Object.keys(res.data || {}).forEach(key => {
        const input = form.querySelector(`[name="${key}"]`);
        if (input) input.value = res.data[key];
    });
}

async function loadPages() {
    const list = document.getElementById('pages-list');
    list.innerHTML = '<tr><td colspan="5" style="padding:32px;text-align:center;color:var(--text-secondary);">Loading SEO pages...</td></tr>';

    const res = await fetch('api/v1/seo?type=page').then(r => r.json()).catch(() => null);
    if (!res || res.status !== 'success') {
        list.innerHTML = '<tr><td colspan="5" style="padding:32px;text-align:center;color:var(--danger-red);">Unable to load SEO data.</td></tr>';
        return;
    }

    const storedPages = (res.data || []).map(row => ({
        name: row.url_slug ? row.url_slug.replace('.php', '').replace(/_/g, ' ') : 'Custom Page',
        path: row.url_slug,
        meta: row
    }));
    const byPath = new Map(storedPages.filter(p => p.path).map(p => [p.path, p]));
    const pages = defaultPages.map(page => ({
        ...page,
        meta: byPath.get(page.path)?.meta || {}
    }));
    storedPages.forEach(page => {
        if (!defaultPages.some(defaultPage => defaultPage.path === page.path)) pages.push(page);
    });

    let totalScore = 0;
    let issues = 0;
    list.innerHTML = pages.map(page => {
        const score = Number(page.meta?.seo_score || 0);
        totalScore += score;
        if (score < 80) issues++;

        return `
            <tr class="table-row-hover">
                <td>
                    <div class="page-name-cell">
                        <span class="material-symbols-outlined page-icon">article</span>
                        <span class="page-name">${escapeHtml(page.name)}</span>
                    </div>
                </td>
                <td><code class="url-path">${escapeHtml(page.path || '')}</code></td>
                <td>${escapeHtml(page.meta?.focus_keyword || '-')}</td>
                <td><div class="score-badge ${getScoreClass(score)}"><span class="material-symbols-outlined score-icon">analytics</span>${score}/100</div></td>
                <td class="text-right">
                    <button class="btn-action" onclick='editPage(${JSON.stringify({ name: page.name, path: page.path, id: page.meta?.id || null }).replace(/'/g, '&#039;')})'>
                        <span class="material-symbols-outlined">edit</span>Edit
                    </button>
                </td>
            </tr>
        `;
    }).join('');

    document.getElementById('stat-total-pages').innerText = pages.length;
    document.getElementById('stat-avg-score').innerText = pages.length ? `${Math.round(totalScore / pages.length)}%` : '0%';
    document.getElementById('stat-issues').innerText = issues;
}

function openPageModal() {
    currentSlug = '';
    document.getElementById('nx-modal-title').innerText = 'Add Page Meta';
    document.getElementById('nx-seo-page-form').reset();
    document.getElementById('nx-edit-id').value = '';
    document.getElementById('nx-path-input-group').classList.remove('hidden');
    document.getElementById('nx-seo-meta-modal').classList.remove('hidden');
    runAnalysis();
}

async function editPage(page) {
    currentSlug = page.path || '';
    document.getElementById('nx-modal-title').innerText = `Editing Meta: ${page.name}`;
    document.getElementById('nx-edit-id').value = page.id || '';
    document.getElementById('nx-url-slug').value = currentSlug;
    document.getElementById('nx-path-input-group').classList.add('hidden');

    const res = await fetch(`api/v1/seo?type=page&slug=${encodeURIComponent(page.path || '')}`).then(r => r.json()).catch(() => null);
    const data = res && res.status === 'success' ? (res.data || {}) : {};

    document.getElementById('nx-meta-title').value = data.meta_title || '';
    document.getElementById('nx-meta-desc').value = data.meta_description || '';
    document.getElementById('nx-meta-keywords').value = data.meta_keywords || '';
    document.getElementById('nx-focus-keyword').value = data.focus_keyword || '';
    document.getElementById('nx-seo-meta-modal').classList.remove('hidden');
    runAnalysis();
}

function closePageModal(event) {
    if (event && event.target !== document.getElementById('nx-seo-meta-modal')) return;
    document.getElementById('nx-seo-meta-modal').classList.add('hidden');
}

function setLengthStatus(id, length, min, max) {
    const el = document.getElementById(id);
    if (!el) return;
    if (!length) {
        el.className = 'status-indicator status-warning';
        el.innerText = 'Missing';
    } else if (length >= min && length <= max) {
        el.className = 'status-indicator status-good';
        el.innerText = 'Optimal';
    } else {
        el.className = 'status-indicator status-warning';
        el.innerText = length < min ? 'Too short' : 'Too long';
    }
}

function runAnalysis() {
    const title = document.getElementById('nx-meta-title').value.trim();
    const description = document.getElementById('nx-meta-desc').value.trim();
    const keyword = document.getElementById('nx-focus-keyword').value.trim();
    const slug = document.getElementById('nx-url-slug').value.trim() || currentSlug || 'index.php';
    const result = NexusSeo.analyze({ title, description, focusKeyword: keyword });

    document.getElementById('nx-modal-score').innerText = result.score;
    const scoreDisplay = document.getElementById('nx-score-display');
    scoreDisplay.className = `score-display ${result.score >= 80 ? 'score-excellent' : (result.score >= 50 ? 'score-good' : 'score-poor')}`;

    document.getElementById('nx-title-hint').innerText = `${title.length} / 60 characters optimal`;
    document.getElementById('nx-desc-hint').innerText = `${description.length} / 160 characters optimal`;
    setLengthStatus('nx-title-status', title.length, 50, 60);
    setLengthStatus('nx-desc-status', description.length, 120, 160);

    document.getElementById('snippet-url').innerText = `https://srishringarr.com/${slug}`;
    document.getElementById('snippet-title').innerText = title || 'SEO title preview';
    document.getElementById('snippet-desc').innerText = description || 'Meta description preview will appear here as you type.';
    document.getElementById('seo-suggestions').innerHTML = result.checks.map(check => `
        <li class="suggestion-${check.status}">
            <span class="material-symbols-outlined">${check.status === 'pass' ? 'check_circle' : (check.status === 'warning' ? 'info' : 'cancel')}</span>
            <span>${escapeHtml(check.message)}</span>
        </li>
    `).join('');
}

async function savePageSeo(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    data.url_slug = (data.url_slug || currentSlug || '').trim();
    data.seo_score = Number(document.getElementById('nx-modal-score').innerText || 0);

    if (!data.url_slug) {
        showToast('Target page file is required', 'error');
        return;
    }

    const res = await fetch('api/v1/seo/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(r => r.json()).catch(error => ({ status: 'error', message: error.message }));

    if (res.status === 'success') {
        showToast('SEO meta updated');
        closePageModal(null);
        loadPages();
    } else {
        showToast(res.message || 'Failed to update SEO meta', 'error');
    }
}

async function saveSettings(event) {
    event.preventDefault();
    const data = Object.fromEntries(new FormData(event.target).entries());
    const res = await fetch('api/v1/seo/settings/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(r => r.json()).catch(error => ({ status: 'error', message: error.message }));

    showToast(res.status === 'success' ? 'Settings saved' : (res.message || 'Failed to save settings'), res.status === 'success' ? 'success' : 'error');
}
</script>
