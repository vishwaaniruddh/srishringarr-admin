/**
 * Nexus CRM - Application JavaScript
 * Handles theme toggle, command palette, sidebar, and interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initCommandPalette();
    initSidebar();
    initChartToggles();
    initPillTabs();
    initAnimations();
});

/* ================================
   THEME TOGGLE
   ================================ */
function initTheme() {
    const saved = localStorage.getItem('nexus-theme') || 'light';
    if (saved === 'dark') {
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    }
    updateThemeIcon();
}

function toggleTheme() {
    const html = document.documentElement;
    html.classList.toggle('dark');
    html.classList.toggle('light');
    const isDark = html.classList.contains('dark');
    localStorage.setItem('nexus-theme', isDark ? 'dark' : 'light');
    updateThemeIcon();
}

function updateThemeIcon() {
    const icon = document.getElementById('theme-icon');
    if (icon) {
        icon.textContent = document.documentElement.classList.contains('dark') ? 'light_mode' : 'dark_mode';
    }
}

/* ================================
   COMMAND PALETTE (CMD+K)
   ================================ */
function initCommandPalette() {
    const overlay = document.getElementById('command-palette');
    const input = document.getElementById('command-input');
    const results = document.getElementById('command-results');

    // Keyboard shortcut
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            openCommandPalette();
        }
        if (e.key === 'Escape') {
            closeCommandPalette();
        }
        // Number shortcuts
        if ((e.metaKey || e.ctrlKey) && e.key >= '1' && e.key <= '6') {
            e.preventDefault();
            const pages = ['dashboard', 'inventory', 'customers', 'orders', 'rentals', 'finance'];
            const idx = parseInt(e.key) - 1;
            if (pages[idx]) window.location = '?page=' + pages[idx];
        }
        // CMD+D for dark mode
        if ((e.metaKey || e.ctrlKey) && e.key === 'd') {
            e.preventDefault();
            toggleTheme();
        }
    });

    // Close on overlay click
    if (overlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeCommandPalette();
        });
    }

    // Filter results
    if (input) {
        input.addEventListener('input', () => {
            const query = input.value.toLowerCase();
            const items = results.querySelectorAll('.command-result-item');
            items.forEach(item => {
                const text = item.querySelector('.command-result-text').textContent.toLowerCase();
                item.style.display = text.includes(query) ? 'flex' : 'none';
            });
        });
    }
}

function openCommandPalette() {
    const overlay = document.getElementById('command-palette');
    const input = document.getElementById('command-input');
    if (overlay) {
        overlay.classList.add('open');
        if (input) {
            input.value = '';
            setTimeout(() => input.focus(), 100);
        }
        // Reset filtered results
        const items = document.querySelectorAll('#command-results .command-result-item');
        items.forEach(item => item.style.display = 'flex');
    }
}

function closeCommandPalette() {
    const overlay = document.getElementById('command-palette');
    if (overlay) overlay.classList.remove('open');
}

/* ================================
   NOTIFICATIONS & MODALS
   ================================ */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    let icon = 'check_circle';
    if (type === 'error') icon = 'error';
    if (type === 'info') icon = 'info';

    toast.innerHTML = `
        <span class="material-symbols-outlined">${icon}</span>
        <span style="font-size:13px; font-weight:500;">${message}</span>
    `;

    container.appendChild(toast);

    // Auto remove
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function showConfirm(options) {
    const modal = document.getElementById('confirm-modal');
    const title = document.getElementById('confirm-title');
    const message = document.getElementById('confirm-message');
    const confirmBtn = document.getElementById('confirm-btn');

    if (!modal) return;

    title.innerText = options.title || 'Are you sure?';
    message.innerText = options.message || 'This action cannot be undone.';
    confirmBtn.innerText = options.confirmText || 'Confirm';
    confirmBtn.className = `btn ${options.type === 'error' ? 'btn-error' : 'btn-primary'}`;

    modal.classList.add('open');

    // New click handler to avoid multiple listeners
    confirmBtn.onclick = async () => {
        const originalText = confirmBtn.innerText;
        confirmBtn.disabled = true;
        confirmBtn.innerText = 'Processing...';
        
        try {
            await options.onConfirm();
            closeConfirmModal();
        } catch (error) {
            console.error('Confirm error:', error);
            showToast('An unexpected error occurred', 'error');
        } finally {
            confirmBtn.disabled = false;
            confirmBtn.innerText = originalText;
        }
    };
}

function closeConfirmModal() {
    const modal = document.getElementById('confirm-modal');
    if (modal) modal.classList.remove('open');
}

// Add these to global scope for easy access
window.showToast = showToast;
window.showConfirm = showConfirm;
window.closeConfirmModal = closeConfirmModal;

/* ================================
   SIDEBAR
   ================================ */
function initSidebar() {
    // Add tooltip on collapsed sidebar
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    // Double-click to collapse/expand
    const brand = sidebar.querySelector('.sidebar-brand');
    if (brand) {
        brand.addEventListener('dblclick', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('nexus-sidebar', sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded');
        });
    }

    // Restore state
    const savedState = localStorage.getItem('nexus-sidebar');
    if (savedState === 'collapsed') {
        sidebar.classList.add('collapsed');
    }
}

/* ================================
   CHART TOGGLES
   ================================ */
function initChartToggles() {
    document.querySelectorAll('.chart-toggle-group').forEach(group => {
        const toggles = group.querySelectorAll('.chart-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                toggles.forEach(t => t.classList.remove('active'));
                toggle.classList.add('active');
            });
        });
    });
}

/* ================================
   PILL TABS
   ================================ */
function initPillTabs() {
    document.querySelectorAll('.pill-tabs').forEach(tabGroup => {
        const tabs = tabGroup.querySelectorAll('.pill-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
            });
        });
    });
}

/* ================================
   SCROLL ANIMATIONS
   ================================ */
function initAnimations() {
    // Intersection Observer for scroll-triggered animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    // Observe stat cards and cards
    document.querySelectorAll('.stat-card, .card, .recommendation-card').forEach(el => {
        observer.observe(el);
    });

    // Animate stat values with count-up effect
    animateStatValues();
}

function animateStatValues() {
    document.querySelectorAll('.stat-value').forEach(el => {
        const text = el.textContent;
        el.style.opacity = '0';
        el.style.transform = 'translateY(8px)';
        
        setTimeout(() => {
            el.style.transition = 'all 0.5s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 300);
    });
}
