/**
 * Modal System — Reusable modals
 *
 * Usage:
 *   Modal.open('modalId');
 *   Modal.close('modalId');
 *   Modal.confirm({ title, message, type, onConfirm });
 */
var Modal = (function() {

    // Open modal by ID
    function open(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close modal by ID
    function close(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var modals = document.querySelectorAll('.modal-overlay.active');
            modals.forEach(function(m) {
                m.classList.remove('active');
            });
            document.body.style.overflow = '';
        }
    });

    // Confirm dialog
    function confirm(opts) {
        opts = opts || {};
        var type = opts.type || 'warning';
        var typeIcons = {
            danger:  '🗑️',
            warning: '⚠️',
            success: '✅',
            info:    'ℹ️'
        };

        var overlay = document.createElement('div');
        overlay.className = 'modal-overlay active';

        var box = document.createElement('div');
        box.className = 'modal-box';
        box.style.width = '400px';

        box.innerHTML =
            '<div class="modal-body" style="padding:32px 28px 20px;text-align:center;">' +
                '<div class="modal-confirm-icon ' + type + '">' + (typeIcons[type] || '⚠️') + '</div>' +
                '<div class="modal-confirm-title">' + (opts.title || 'Are you sure?') + '</div>' +
                '<div class="modal-confirm-message">' + (opts.message || '') + '</div>' +
            '</div>' +
            '<div class="modal-footer" style="justify-content:center;gap:12px;">' +
                '<button class="btn btn-outline modal-cancel-btn">Cancel</button>' +
                '<button class="btn btn-' + (type === 'danger' ? 'danger' : 'primary') + ' modal-confirm-btn">' + (opts.confirmText || 'Confirm') + '</button>' +
            '</div>';

        overlay.appendChild(box);
        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        // Events
        box.querySelector('.modal-cancel-btn').onclick = function() {
            document.body.removeChild(overlay);
            document.body.style.overflow = '';
            if (opts.onCancel) opts.onCancel();
        };
        box.querySelector('.modal-confirm-btn').onclick = function() {
            document.body.removeChild(overlay);
            document.body.style.overflow = '';
            if (opts.onConfirm) opts.onConfirm();
        };
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                document.body.removeChild(overlay);
                document.body.style.overflow = '';
            }
        });
    }

    return { open: open, close: close, confirm: confirm };
})();
