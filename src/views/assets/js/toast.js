/**
 * Toast Notification System
 * Usage:
 *   Toast.success('Title', 'Message');
 *   Toast.error('Title', 'Message');
 *   Toast.warning('Title', 'Message');
 *   Toast.info('Title', 'Message');
 */
var Toast = (function() {
    var container = null;

    var icons = {
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
    };

    var colors = {
        success: { bg: '#f0fdf4', border: '#86efac', icon: '#16a34a', bar: '#16a34a' },
        error:   { bg: '#fef2f2', border: '#fca5a5', icon: '#ef4444', bar: '#ef4444' },
        warning: { bg: '#fffbeb', border: '#fcd34d', icon: '#f59e0b', bar: '#f59e0b' },
        info:    { bg: '#eff6ff', border: '#93c5fd', icon: '#2563eb', bar: '#2563eb' }
    };

    function getContainer() {
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:400px;width:100%;pointer-events:none;';
            document.body.appendChild(container);
        }
        return container;
    }

    function show(type, title, message, duration) {
        duration = duration || 4000;
        var c = colors[type] || colors.info;

        var toast = document.createElement('div');
        toast.style.cssText = 'pointer-events:auto;background:' + c.bg + ';border:1px solid ' + c.border + ';border-radius:12px;padding:14px 16px;display:flex;gap:12px;align-items:flex-start;box-shadow:0 10px 25px rgba(0,0,0,0.1);transform:translateX(120%);transition:transform 0.35s cubic-bezier(0.16,1,0.3,1),opacity 0.3s ease;opacity:0;position:relative;overflow:hidden;';

        var iconEl = document.createElement('div');
        iconEl.style.cssText = 'width:22px;height:22px;flex-shrink:0;color:' + c.icon + ';margin-top:1px;';
        iconEl.innerHTML = icons[type] || icons.info;

        var content = document.createElement('div');
        content.style.cssText = 'flex:1;min-width:0;';
        content.innerHTML = '<div style="font-weight:700;font-size:0.88rem;color:#111827;margin-bottom:2px;">' + title + '</div>' +
                           (message ? '<div style="font-size:0.8rem;color:#6b7280;line-height:1.5;">' + message + '</div>' : '');

        var closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = 'background:none;border:none;font-size:1.2rem;color:#9ca3af;cursor:pointer;padding:0;line-height:1;flex-shrink:0;';
        closeBtn.onclick = function() { dismiss(toast); };

        var progressBar = document.createElement('div');
        progressBar.style.cssText = 'position:absolute;bottom:0;left:0;height:3px;background:' + c.bar + ';border-radius:0 0 0 12px;transition:width linear;width:100%;';

        toast.appendChild(iconEl);
        toast.appendChild(content);
        toast.appendChild(closeBtn);
        toast.appendChild(progressBar);

        getContainer().appendChild(toast);

        // Animate in
        requestAnimationFrame(function() {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
            // Start progress bar
            progressBar.style.transitionDuration = duration + 'ms';
            requestAnimationFrame(function() {
                progressBar.style.width = '0%';
            });
        });

        // Auto dismiss
        var timer = setTimeout(function() { dismiss(toast); }, duration);
        toast._timer = timer;
    }

    function dismiss(toast) {
        if (toast._dismissed) return;
        toast._dismissed = true;
        clearTimeout(toast._timer);
        toast.style.transform = 'translateX(120%)';
        toast.style.opacity = '0';
        setTimeout(function() {
            if (toast.parentNode) toast.parentNode.removeChild(toast);
        }, 350);
    }

    return {
        success: function(title, msg, dur) { show('success', title, msg, dur); },
        error:   function(title, msg, dur) { show('error',   title, msg, dur); },
        warning: function(title, msg, dur) { show('warning', title, msg, dur); },
        info:    function(title, msg, dur) { show('info',    title, msg, dur); }
    };
})();
