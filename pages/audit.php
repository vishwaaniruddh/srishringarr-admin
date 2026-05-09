<div class="page-header">
    <div class="header-content">
        <h1>System Audit Logs</h1>
        <p>Monitor system events, errors, and background tasks</p>
    </div>
    <div class="header-actions">
        <button class="btn btn-secondary" onclick="clearLogs()">
            <span class="material-symbols-outlined">delete_sweep</span>
            Clear Logs
        </button>
        <button class="btn btn-primary" onclick="loadLogs()">
            <span class="material-symbols-outlined">refresh</span>
            Refresh
        </button>
    </div>
</div>

<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(var(--primary-rgb), 0.1); color: var(--primary);">
            <span class="material-symbols-outlined">error</span>
        </div>
        <div class="stat-info">
            <h3 id="error-count">0</h3>
            <p>Errors (Last 100)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
            <span class="material-symbols-outlined">info</span>
        </div>
        <div class="stat-info">
            <h3 id="info-count">0</h3>
            <p>Info Messages</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Live Event Stream</h2>
        <div class="header-tools">
            <input type="text" id="log-search" class="form-control form-control-sm" placeholder="Filter logs...">
        </div>
    </div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Level</th>
                    <th>Source</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody id="logs-body">
                <tr>
                    <td colspan="4" class="text-center">Loading logs...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
.log-level {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.level-info { background: #e0f2fe; color: #0369a1; }
.level-error { background: #fee2e2; color: #b91c1c; }
.level-warning { background: #fef3c7; color: #b45309; }

.log-source {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
    color: #64748b;
}

.log-message {
    font-size: 13px;
    word-break: break-all;
}
</style>

<script>
async function loadLogs() {
    try {
        const response = await fetch('api/v1/audit/logs');
        const data = await response.json();
        
        if (data.status === 'success') {
            renderLogs(data.data);
        }
    } catch (error) {
        console.error('Failed to load logs:', error);
    }
}

function renderLogs(logs) {
    const body = document.getElementById('logs-body');
    if (!logs || logs.length === 0) {
        body.innerHTML = '<tr><td colspan="4" class="text-center">No logs found</td></tr>';
        return;
    }

    let errorCount = 0;
    let infoCount = 0;

    body.innerHTML = logs.map(line => {
        // Parse: [2026-05-09 04:23:02] [ERROR] [ProductController.php:70] Message
        const match = line.match(/\[(.*?)\]\s*\[(.*?)\]\s*\[(.*?)\]\s*(.*)/);
        if (!match) return `<tr><td colspan="4" class="text-muted" style="font-size: 12px;">${line}</td></tr>`;

        const [_, time, level, source, message] = match;
        
        if (level === 'ERROR') errorCount++;
        else infoCount++;

        return `
            <tr>
                <td style="white-space: nowrap; font-size: 12px;">${time}</td>
                <td><span class="log-level level-${level.toLowerCase()}">${level}</span></td>
                <td class="log-source">${source}</td>
                <td class="log-message">${message}</td>
            </tr>
        `;
    }).join('');

    document.getElementById('error-count').textContent = errorCount;
    document.getElementById('info-count').textContent = infoCount;
}

async function clearLogs() {
    if (!confirm('Are you sure you want to clear all logs?')) return;
    
    try {
        const response = await fetch('api/v1/audit/clear', { method: 'POST' });
        if (response.ok) {
            loadLogs();
        }
    } catch (error) {
        console.error('Failed to clear logs:', error);
    }
}

// Initial load
loadLogs();
// Auto refresh every 30s
setInterval(loadLogs, 30000);

// Search filter
document.getElementById('log-search').addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#logs-body tr');
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
    });
});
</script>
