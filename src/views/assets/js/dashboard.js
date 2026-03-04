/**
 * Dashboard — Page-specific interactions
 */
document.addEventListener('DOMContentLoaded', function() {

    // ---- Table Search ----
    var searchInputs = document.querySelectorAll('.table-search input');
    searchInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            var query = this.value.toLowerCase();
            var table = this.closest('.data-table-wrapper').querySelector('.data-table tbody');
            if (!table) return;
            var rows = table.querySelectorAll('tr');
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });

    // ---- Filter Selects ----
    var filterSelects = document.querySelectorAll('.table-filter select');
    filterSelects.forEach(function(sel) {
        sel.addEventListener('change', function() {
            var val = this.value.toLowerCase();
            var colIndex = parseInt(this.dataset.col || '0');
            var table = this.closest('.data-table-wrapper').querySelector('.data-table tbody');
            if (!table) return;
            var rows = table.querySelectorAll('tr');
            rows.forEach(function(row) {
                if (!val) { row.style.display = ''; return; }
                var cell = row.querySelectorAll('td')[colIndex];
                if (cell) {
                    var text = cell.textContent.toLowerCase();
                    row.style.display = text.includes(val) ? '' : 'none';
                }
            });
        });
    });

    // ---- Confirm Delete with Modal ----
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('href');
            var msg = this.dataset.confirm || 'Are you sure?';
            var type = this.dataset.confirmType || 'danger';
            Modal.confirm({
                title: msg,
                message: 'This action cannot be undone.',
                type: type,
                confirmText: 'Yes, proceed',
                onConfirm: function() {
                    window.location.href = href;
                }
            });
        });
    });

    // ---- Sidebar Toggle (mobile) ----
    var hamburger = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');

    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            if (overlay) overlay.classList.toggle('active');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }

    // ---- Notification Toggle ----
    var notifBtn = document.getElementById('notifBtn');
    var notifDrop = document.getElementById('notifDropdown');
    if (notifBtn && notifDrop) {
        // Clicking inside dropdown should not toggle (let <a> links navigate)
        notifDrop.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        notifBtn.addEventListener('click', function(e) {
            if (e.target.closest('.notif-dropdown')) return;
            e.stopPropagation();
            notifDrop.classList.toggle('show');
            // Close user dropdown
            var ud = document.getElementById('userDropdown');
            if (ud) ud.classList.remove('show');
        });
    }

    // ---- User Dropdown Toggle ----
    var userBtn = document.getElementById('userMenuBtn');
    var userDrop = document.getElementById('userDropdown');
    if (userBtn && userDrop) {
        // Clicking inside dropdown should not toggle (let <a> links navigate)
        userDrop.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        userBtn.addEventListener('click', function(e) {
            if (e.target.closest('.user-dropdown')) return;
            e.stopPropagation();
            userDrop.classList.toggle('show');
            // Close notif dropdown
            if (notifDrop) notifDrop.classList.remove('show');
        });
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function() {
        if (notifDrop) notifDrop.classList.remove('show');
        if (userDrop) userDrop.classList.remove('show');
    });

    // ---- Animate KPI cards on load ----
    var kpis = document.querySelectorAll('.kpi-card');
    kpis.forEach(function(card, i) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(12px)';
        setTimeout(function() {
            card.style.transition = 'all 0.4s cubic-bezier(0.16,1,0.3,1)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 80 * i);
    });

    // ---- Animate progress bars ----
    var bars = document.querySelectorAll('.kpi-progress-bar');
    bars.forEach(function(bar) {
        var w = bar.dataset.width || '0%';
        bar.style.width = '0%';
        setTimeout(function() { bar.style.width = w; }, 400);
    });
});
