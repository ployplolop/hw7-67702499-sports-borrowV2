<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Equipment Borrowing System</title>

    <!-- jQuery + jQuery Confirm -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>

    <!-- SportAlert (reusable) -->
    <link rel="stylesheet" href="views/assets/css/sport-alert.css">
    <script src="views/assets/js/sport-alert.js" defer></script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; color: #333; }

        /* ========== Navbar ========== */
        .navbar {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #e2e8f0; padding: 0 28px; height: 58px;
            display: flex; align-items: center; gap: 0;
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar .brand {
            font-size: 1.2rem; font-weight: 800; letter-spacing: .5px;
            margin-right: 32px; display: flex; align-items: center; gap: 8px;
            color: #fff;
        }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-links a {
            color: #94a3b8; text-decoration: none; font-weight: 500; font-size: .9rem;
            padding: 8px 16px; border-radius: 8px;
            transition: all .2s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: #fff; background: rgba(255,255,255,.1);
        }
        .nav-right {
            margin-left: auto; display: flex; align-items: center; gap: 16px;
        }

        /* ========== Notification Bell ========== */
        .notif-bell {
            position: relative; cursor: pointer; padding: 8px;
            border-radius: 10px; transition: background .2s;
        }
        .notif-bell:hover { background: rgba(255,255,255,.1); }
        .notif-bell svg { width: 22px; height: 22px; color: #94a3b8; }
        .notif-bell:hover svg { color: #fff; }
        .notif-badge {
            position: absolute; top: 4px; right: 4px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #ef4444; color: #fff; font-size: .65rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #1e293b;
            animation: notifPulse 2s ease infinite;
        }
        @keyframes notifPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .notif-badge.hidden { display: none; }

        /* ========== Notification Dropdown ========== */
        .notif-dropdown {
            display: none; position: absolute; top: 46px; right: 0;
            width: 360px; max-height: 420px; overflow-y: auto;
            background: #fff; border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0,0,0,.2);
            z-index: 200; border: 1px solid #e2e8f0;
        }
        .notif-dropdown.show { display: block; }
        .notif-header {
            padding: 16px 18px 10px; font-weight: 700; font-size: 1rem;
            color: #1e293b; border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .notif-item {
            display: flex; align-items: flex-start; gap: 12px; padding: 12px 18px;
            border-bottom: 1px solid #f8fafc; transition: background .15s;
        }
        .notif-item:hover { background: #f8fafc; }
        .notif-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .notif-icon.warn { background: #fef3c7; }
        .notif-icon.danger { background: #fee2e2; }
        .notif-icon.info { background: #dbeafe; }
        .notif-text { flex: 1; }
        .notif-text .title { font-weight: 600; font-size: .85rem; color: #1e293b; }
        .notif-text .desc { font-size: .8rem; color: #6b7280; margin-top: 2px; }
        .notif-text .time { font-size: .72rem; color: #9ca3af; margin-top: 4px; }
        .notif-empty { text-align: center; padding: 30px; color: #9ca3af; font-size: .9rem; }

        .user-pill {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 14px; border-radius: 10px;
            background: rgba(255,255,255,.06);
        }
        .user-pill .name { font-size: .85rem; font-weight: 500; color: #e2e8f0; }
        .user-pill .role { font-size: .7rem; color: #64748b; }
        .logout-btn {
            background: rgba(239,68,68,.15); color: #fca5a5; padding: 7px 16px;
            border-radius: 8px; font-size: .82rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
        }
        .logout-btn:hover { background: #ef4444; color: #fff; }

        /* ========== Content ========== */
        .container { max-width: 1200px; margin: 28px auto; padding: 0 24px; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.06); }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        th { background: #f8fafc; color: #64748b; font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; }
        tr:hover { background: #fafbfc; }
        .btn {
            display: inline-block; padding: 8px 18px; border: none; border-radius: 8px;
            font-size: .85rem; font-weight: 600; cursor: pointer; text-decoration: none; color: #fff;
            transition: all .2s;
        }
        .btn-primary { background: #3b82f6; }
        .btn-success { background: #22c55e; }
        .btn-warning { background: #f59e0b; }
        .btn-danger  { background: #ef4444; }
        .btn:hover { opacity: .85; transform: translateY(-1px); }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: .9rem; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: .95rem;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); outline: none;
        }
        .card { background: #fff; padding: 24px; border-radius: 14px; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 20px; }
        h2 { margin-bottom: 20px; font-size: 1.3rem; color: #1e293b; }
        .actions a, .actions button { margin-right: 6px; }
        .badge {
            padding: 4px 12px; border-radius: 20px; font-size: .75rem; font-weight: 600; color: #fff;
        }
        .badge-borrowed { background: #f59e0b; }
        .badge-returned { background: #22c55e; }
        .badge-overdue  { background: #ef4444; }
    </style>
</head>
<body>
    <?php
    // Fetch notifications (due soon + overdue)
    require_once __DIR__ . '/../../config/database.php';
    $__pdo = Database::connect();
    $__notifs = $__pdo->query("
        SELECT br.id, br.due_date, br.status, u.full_name AS user_name, e.name AS equipment_name,
               DATEDIFF(br.due_date, NOW()) AS days_left
        FROM borrow_records br
        JOIN users u ON u.id = br.user_id
        JOIN equipment e ON e.id = br.equipment_id
        WHERE br.status = 'borrowed' AND br.due_date <= DATE_ADD(NOW(), INTERVAL 7 DAY)
        ORDER BY br.due_date ASC
    ")->fetchAll();
    $__notifCount = count($__notifs);
    ?>
    <nav class="navbar">
        <span class="brand">🏅 Sports Borrow</span>
        <div class="nav-links">
            <a href="index.php" <?= ($_GET['page'] ?? 'dashboard') === 'dashboard' ? 'class="active"' : '' ?>>Dashboard</a>
            <a href="index.php?page=equipment" <?= ($_GET['page'] ?? '') === 'equipment' ? 'class="active"' : '' ?>>Equipment</a>
            <a href="index.php?page=borrows" <?= ($_GET['page'] ?? '') === 'borrows' ? 'class="active"' : '' ?>>Borrows</a>
            <a href="index.php?page=calendar" <?= ($_GET['page'] ?? '') === 'calendar' ? 'class="active"' : '' ?>>Calendar</a>
            <a href="index.php?page=users" <?= ($_GET['page'] ?? '') === 'users' ? 'class="active"' : '' ?>>Users</a>
        </div>
        <div class="nav-right">
            <!-- 🔔 Notification Bell -->
            <div class="notif-bell" id="notifBell">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                </svg>
                <span class="notif-badge <?= $__notifCount === 0 ? 'hidden' : '' ?>"><?= $__notifCount ?></span>

                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <span>🔔 Notifications</span>
                        <span style="font-size:.75rem;color:#94a3b8;font-weight:400;"><?= $__notifCount ?> items</span>
                    </div>
                    <?php if (empty($__notifs)): ?>
                        <div class="notif-empty">🎉 No notifications</div>
                    <?php endif; ?>
                    <?php foreach ($__notifs as $n): ?>
                        <?php
                        $isOverdue = $n['days_left'] < 0;
                        $isUrgent  = $n['days_left'] <= 1 && !$isOverdue;
                        ?>
                        <div class="notif-item">
                            <div class="notif-icon <?= $isOverdue ? 'danger' : ($isUrgent ? 'warn' : 'info') ?>">
                                <?= $isOverdue ? '🚨' : ($isUrgent ? '⚠️' : '📅') ?>
                            </div>
                            <div class="notif-text">
                                <div class="title"><?= htmlspecialchars($n['equipment_name']) ?></div>
                                <div class="desc">
                                    <?php if ($isOverdue): ?>
                                        Overdue <?= abs($n['days_left']) ?> day(s) — <?= htmlspecialchars($n['user_name']) ?>
                                    <?php elseif ($n['days_left'] == 0): ?>
                                        Due today — <?= htmlspecialchars($n['user_name']) ?>
                                    <?php else: ?>
                                        Due in <?= $n['days_left'] ?> day(s) — <?= htmlspecialchars($n['user_name']) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="time"><?= date('d M Y', strtotime($n['due_date'])) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="user-pill">
                <div>
                    <div class="name"><?= htmlspecialchars($_SESSION['full_name'] ?? '') ?></div>
                    <div class="role"><?= ucfirst($_SESSION['role'] ?? 'user') ?></div>
                </div>
            </div>
            <a href="index.php?page=login&action=logout" class="logout-btn">Logout</a>
        </div>
    </nav>

    <script>
    // Toggle notification dropdown
    document.getElementById('notifBell').addEventListener('click', function(e) {
        e.stopPropagation();
        document.getElementById('notifDropdown').classList.toggle('show');
    });
    document.addEventListener('click', function() {
        document.getElementById('notifDropdown').classList.remove('show');
    });
    </script>

    <div class="container">
