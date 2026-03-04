/**
 * SportAlert — Reusable Alert System (jQuery Confirm Custom Theme)
 *
 * 4 Alert Levels:
 *   SportAlert.info(title, message, callback)
 *   SportAlert.success(title, message, callback)
 *   SportAlert.danger(title, message, callback)
 *   SportAlert.warning(title, message, callback)
 *
 * Requires: jQuery + jquery-confirm
 */
var SportAlert = (function () {

    var _icons = {
        danger:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:52px;height:52px;margin:0 auto 14px;display:block" class="sa-icon sa-icon-danger"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:52px;height:52px;margin:0 auto 14px;display:block" class="sa-icon sa-icon-warning"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:52px;height:52px;margin:0 auto 14px;display:block" class="sa-icon sa-icon-info"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:52px;height:52px;margin:0 auto 14px;display:block" class="sa-icon sa-icon-success"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
    };

    var _colors = {
        danger:  { type: 'red',    gradient: 'linear-gradient(90deg, #ef4444, #f87171)' },
        warning: { type: 'orange', gradient: 'linear-gradient(90deg, #f59e0b, #fbbf24)' },
        info:    { type: 'blue',   gradient: 'linear-gradient(90deg, #2563eb, #60a5fa)' },
        success: { type: 'green',  gradient: 'linear-gradient(90deg, #16a34a, #4ade80)' }
    };

    function show(level, title, message, callback) {
        var c = _colors[level] || _colors.info;
        $.confirm({
            title: '',
            content: '<div style="height:4px;border-radius:2px;margin-bottom:18px;background:' + c.gradient + '"></div>'
                   + _icons[level]
                   + '<div style="text-align:center;padding:8px 0">'
                   + '<div style="font-size:1.25rem;font-weight:700;margin-bottom:8px;color:#1f2937">' + title + '</div>'
                   + '<div style="font-size:1rem;color:#6b7280;line-height:1.7">' + message + '</div>'
                   + '</div>',
            type: c.type,
            theme: 'modern',
            columnClass: 'col-md-6 col-md-offset-3',
            typeAnimated: true,
            animateFromElement: false,
            animation: 'zoom',
            closeAnimation: 'scale',
            animationSpeed: 400,
            backgroundDismiss: true,
            boxWidth: '480px',
            useBootstrap: false,
            buttons: {
                ok: {
                    text: 'ตกลง',
                    btnClass: 'btn-' + c.type,
                    keys: ['enter'],
                    action: function () { if (typeof callback === 'function') callback(); }
                }
            }
        });
    }

    return {
        show:    show,
        danger:  function (t, m, cb) { show('danger',  t, m, cb); },
        warning: function (t, m, cb) { show('warning', t, m, cb); },
        info:    function (t, m, cb) { show('info',    t, m, cb); },
        success: function (t, m, cb) { show('success', t, m, cb); }
    };
})();
