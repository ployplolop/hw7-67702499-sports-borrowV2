<?php
/**
 * Login page — Modern Glassmorphism UI + Floating Sports Icons
 * Tailwind CSS + jQuery Confirm (Custom Theme)
 */
$error   = $error ?? '';
$alertType = $alertType ?? '';  // 'danger' | 'warning' | 'info' | 'success'
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ — Sports Borrow</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'Noto Sans Thai', 'sans-serif'] },
            }
        }
    }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery + jQuery Confirm -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link  rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>

    <style>
        /* ========== Soft Background ========== */
        .bg-animated {
            background: linear-gradient(180deg, #f8fafc, #e2e8f0);
            min-height: 100vh;
        }

        /* ========== Floating Sports Icons ========== */
        .floating-icons {
            position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0;
        }
        .floating-icon {
            position: absolute;
            font-size: 2.5rem;
            opacity: 0.03;
            animation: floatUp linear infinite;
            filter: blur(1px);
        }
        @keyframes floatUp {
            0%   { transform: translateY(100vh) rotate(0deg) scale(1); opacity: 0; }
            10%  { opacity: 0.03; }
            90%  { opacity: 0.03; }
            100% { transform: translateY(-10vh) rotate(360deg) scale(1.2); opacity: 0; }
        }

        /* ========== Clean White Card ========== */
        .glass-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* ========== Clean Input Style ========== */
        .input-glass {
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            color: #1f2937;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-glass::placeholder { color: #9ca3af; }
        .input-glass:focus {
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            outline: none;
        }

        /* ========== Primary Blue Button ========== */
        .btn-glow {
            background: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }
        .btn-glow:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }
        .btn-glow:active { transform: translateY(0); }
        .btn-glow span { position: relative; z-index: 1; }

        /* ========== Logo Ring ========== */
        .logo-ring {
            animation: pulse-ring 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.2); }
            50%      { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
        }

        /* ========== Slide-in Animation ========== */
        .slide-up {
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ========== jQuery Confirm Custom Theme ========== */
        .jconfirm.jconfirm-modern .jconfirm-box {
            border-radius: 16px !important;
            padding: 0 !important;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.15) !important;
            border: none !important;
        }
        .jconfirm.jconfirm-modern .jconfirm-title-c {
            padding: 24px 28px 8px !important;
            font-weight: 700 !important;
            font-size: 1.15rem !important;
        }
        .jconfirm.jconfirm-modern .jconfirm-content-pane {
            padding: 0 28px !important;
        }
        .jconfirm.jconfirm-modern .jconfirm-content {
            font-size: 0.95rem !important;
            color: #555 !important;
            line-height: 1.6 !important;
        }
        .jconfirm.jconfirm-modern .jconfirm-buttons {
            padding: 16px 28px 24px !important;
        }
        .jconfirm.jconfirm-modern .jconfirm-buttons button {
            border-radius: 10px !important;
            padding: 10px 28px !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
            letter-spacing: 0.3px;
            transition: all 0.2s !important;
        }

        /* Alert level colors */
        .alert-icon-danger  { color: #ef4444; }
        .alert-icon-warning { color: #f59e0b; }
        .alert-icon-info    { color: #2563eb; }
        .alert-icon-success { color: #16a34a; }

        .alert-bar {
            height: 4px; border-radius: 2px; margin-bottom: 16px;
        }
        .alert-bar-danger  { background: linear-gradient(90deg, #ef4444, #f87171); }
        .alert-bar-warning { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .alert-bar-info    { background: linear-gradient(90deg, #2563eb, #60a5fa); }
        .alert-bar-success { background: linear-gradient(90deg, #16a34a, #4ade80); }

        /* Particle dots — subtle on light bg */
        .particle {
            position: fixed; width: 3px; height: 3px; border-radius: 50%;
            background: rgba(0, 0, 0, 0.04);
            animation: drift linear infinite;
            pointer-events: none;
        }
        @keyframes drift {
            0%   { transform: translate(0, 0); opacity: 0.04; }
            50%  { opacity: 0.07; }
            100% { transform: translate(var(--dx), var(--dy)); opacity: 0; }
        }
    </style>
</head>

<body class="bg-animated min-h-screen flex items-center justify-center font-sans overflow-hidden">

    <!-- ========== Floating Sports Icons ========== -->
    <div class="floating-icons" id="floatingIcons"></div>

    <!-- ========== Particles ========== -->
    <div id="particles"></div>

    <!-- ========== Login Card ========== -->
    <div class="w-full max-w-[420px] px-5 relative z-10 slide-up">

        <div class="glass-card rounded-3xl overflow-hidden">

            <!-- ---- Logo Area ---- -->
            <div class="text-center pt-10 pb-6 px-8">
                <div class="inline-flex items-center justify-center w-[72px] h-[72px] rounded-full bg-blue-50 logo-ring mb-5">
                    <svg class="w-9 h-9 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10" stroke-dasharray="4 3"/>
                        <path d="M2 12h20M12 2c2.8 3.1 4.4 7.3 4.4 10s-1.6 6.9-4.4 10c-2.8-3.1-4.4-7.3-4.4-10S9.2 5.1 12 2z"/>
                    </svg>
                </div>
                <h1 class="text-[1.65rem] font-bold tracking-tight" style="color:#1f2937">Sports Borrow</h1>
                <p class="text-sm mt-1.5 font-light" style="color:#6b7280">ระบบยืม-คืนอุปกรณ์กีฬา</p>
            </div>

            <!-- ---- Divider ---- -->
            <div class="mx-8 h-px" style="background:#e5e7eb"></div>

            <!-- ---- Form ---- -->
            <form id="loginForm" method="POST" action="index.php?page=login&action=authenticate" class="px-8 pt-7 pb-4 space-y-5">

                <!-- Username -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:#6b7280">
                        Username
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4" style="color:#9ca3af">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0116.5 0"/>
                            </svg>
                        </span>
                        <input type="text" id="username" name="username" required autocomplete="username"
                               class="input-glass w-full pl-11 pr-4 py-3 text-sm font-medium"
                               placeholder="กรอกชื่อผู้ใช้">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color:#6b7280">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4" style="color:#9ca3af">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="input-glass w-full pl-11 pr-12 py-3 text-sm font-medium"
                               placeholder="กรอกรหัสผ่าน">
                        <!-- Toggle eye -->
                        <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 transition" style="color:#9ca3af">
                            <svg id="eyeOff" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                            </svg>
                            <svg id="eyeOn" class="w-[18px] h-[18px] hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-glow w-full text-white font-semibold py-3.5 rounded-xl mt-2" style="border-radius:8px">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        เข้าสู่ระบบ
                    </span>
                </button>
            </form>

            <!-- ---- Footer ---- -->
            <div class="px-8 pb-7 pt-2 text-center">
                <p class="text-[11px]" style="color:#9ca3af">&copy; <?= date('Y') ?> Sports Equipment Borrowing System</p>
            </div>
        </div>
    </div>

    <!-- ========== Scripts ========== -->
    <script>
    // ---------- Floating Sports Icons ----------
    (function () {
        var icons = ['⚽','🏀','🏈','🎾','🏐','🏸','🏓','⛳','🎯','🏋️','🧘','🎿','🏊','🚴'];
        var container = document.getElementById('floatingIcons');
        for (var i = 0; i < 18; i++) {
            var el = document.createElement('span');
            el.className = 'floating-icon';
            el.textContent = icons[Math.floor(Math.random() * icons.length)];
            el.style.left = Math.random() * 100 + '%';
            el.style.fontSize = (1.5 + Math.random() * 2.5) + 'rem';
            el.style.animationDuration = (12 + Math.random() * 18) + 's';
            el.style.animationDelay = (Math.random() * 15) + 's';
            container.appendChild(el);
        }
    })();

    // ---------- Particle Dots ----------
    (function () {
        var container = document.getElementById('particles');
        for (var i = 0; i < 30; i++) {
            var p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.top  = Math.random() * 100 + 'vh';
            p.style.setProperty('--dx', (Math.random() * 200 - 100) + 'px');
            p.style.setProperty('--dy', (Math.random() * 200 - 100) + 'px');
            p.style.animationDuration = (4 + Math.random() * 8) + 's';
            p.style.animationDelay    = (Math.random() * 5) + 's';
            container.appendChild(p);
        }
    })();

    // ---------- Toggle Password Visibility ----------
    document.getElementById('togglePassword').addEventListener('click', function () {
        var input  = document.getElementById('password');
        var eyeOff = document.getElementById('eyeOff');
        var eyeOn  = document.getElementById('eyeOn');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOff.classList.add('hidden');
            eyeOn.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOff.classList.remove('hidden');
            eyeOn.classList.add('hidden');
        }
    });

    // ============================================================
    //  SportAlert — Custom jQuery Confirm Alert System
    //  4 Levels: info | success | danger | warning
    // ============================================================
    var SportAlert = {
        _icons: {
            danger:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:48px;height:48px;margin:0 auto 12px;display:block" class="alert-icon-danger"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:48px;height:48px;margin:0 auto 12px;display:block" class="alert-icon-warning"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
            info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:48px;height:48px;margin:0 auto 12px;display:block" class="alert-icon-info"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
            success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:48px;height:48px;margin:0 auto 12px;display:block" class="alert-icon-success"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        },
        _colors: {
            danger:  { btn: '#ef4444', type: 'red' },
            warning: { btn: '#f59e0b', type: 'orange' },
            info:    { btn: '#2563eb', type: 'blue' },
            success: { btn: '#16a34a', type: 'green' }
        },

        show: function (level, title, message, callback) {
            var c = this._colors[level] || this._colors.info;
            $.confirm({
                title: '',
                content: '<div class="alert-bar alert-bar-' + level + '"></div>'
                       + this._icons[level]
                       + '<div style="text-align:center;padding:8px 0">'
                       + '<div style="font-size:1.25rem;font-weight:700;margin-bottom:8px;color:#1f2937">' + title + '</div>'
                       + '<div style="font-size:1rem;color:#6b7280;line-height:1.7">' + message + '</div>'
                       + '</div>',
                type: c.type,
                theme: 'modern',
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
                        action: function () { if (callback) callback(); }
                    }
                }
            });
        },

        danger:  function (title, msg, cb) { this.show('danger',  title, msg, cb); },
        warning: function (title, msg, cb) { this.show('warning', title, msg, cb); },
        info:    function (title, msg, cb) { this.show('info',    title, msg, cb); },
        success: function (title, msg, cb) { this.show('success', title, msg, cb); }
    };

    // ---------- Document Ready ----------
    $(document).ready(function () {

        // ---- Server-side error popup ----
        <?php if (!empty($error)): ?>
        SportAlert.danger(
            'เข้าสู่ระบบไม่สำเร็จ',
            '<?= addslashes($error) ?>'
        );
        <?php endif; ?>

        // ---- Client-side validation ----
        $('#loginForm').on('submit', function (e) {
            var u = $('#username').val().trim();
            var p = $('#password').val().trim();

            if (u === '' || p === '') {
                e.preventDefault();
                SportAlert.warning(
                    'ข้อมูลไม่ครบถ้วน',
                    'กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน'
                );
                return false;
            }

            if (p.length < 3) {
                e.preventDefault();
                SportAlert.info(
                    'รหัสผ่านสั้นเกินไป',
                    'รหัสผ่านควรมีอย่างน้อย 3 ตัวอักษร'
                );
                return false;
            }
        });
    });
    </script>
</body>
</html>
