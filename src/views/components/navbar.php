<?php
/**
 * Navbar Component
 * Required: $_SESSION for user info
 */
require_once __DIR__ . '/../../config/database.php';
$__navPdo = Database::connect();

// Notifications: due soon + overdue
$__navNotifs = $__navPdo->query("
    SELECT br.id, br.due_date, br.status, u.full_name AS user_name, e.name AS equipment_name,
           DATEDIFF(br.due_date, NOW()) AS days_left
    FROM borrow_records br
    JOIN users u ON u.id = br.user_id
    JOIN equipment e ON e.id = br.equipment_id
    WHERE br.status = 'borrowed' AND br.due_date <= DATE_ADD(NOW(), INTERVAL 7 DAY)
    ORDER BY br.due_date ASC
")->fetchAll();
$__navNotifCount = count($__navNotifs);

$pageTitles = [
    'dashboard' => 'Dashboard',
    'equipment' => 'Equipment',
    'borrows'   => 'Borrows',
    'calendar'  => 'Calendar',
    'users'     => 'Users',
];
$curPage = $_GET['page'] ?? 'dashboard';
$curAction = $_GET['action'] ?? 'index';
$pageTitle = $pageTitles[$curPage] ?? 'Dashboard';
if ($curAction === 'create') $pageTitle .= ' / Add New';
if ($curAction === 'edit') $pageTitle .= ' / Edit';
?>
<header class="top-navbar">
    <!-- Hamburger (mobile) -->
    <button class="navbar-hamburger" id="sidebarToggle">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
    </button>

    <!-- Breadcrumb / Page Title -->
    <div style="font-size:0.88rem;color:var(--color-text-secondary);">
        <span style="font-weight:700;color:var(--color-text-primary);"><?= $pageTitle ?></span>
    </div>

    <!-- Right -->
    <div class="navbar-right">
        <!-- Notifications -->
        <div class="navbar-notif" id="notifBtn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
            </svg>
            <span class="notif-dot <?= $__navNotifCount === 0 ? 'hidden' : '' ?>"></span>

            <!-- Notification Dropdown -->
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <span>Notifications</span>
                    <span class="count"><?= $__navNotifCount ?> items</span>
                </div>
                <?php if (empty($__navNotifs)): ?>
                    <div class="notif-empty">🎉 No notifications</div>
                <?php endif; ?>
                <?php foreach ($__navNotifs as $n): ?>
                    <?php
                    $isOverdue = $n['days_left'] < 0;
                    $isUrgent  = $n['days_left'] <= 1 && !$isOverdue;
                    ?>
                    <a href="index.php?page=borrows" class="notif-item" style="text-decoration:none;color:inherit;">
                        <div class="notif-icon-wrap <?= $isOverdue ? 'danger' : ($isUrgent ? 'warn' : 'info') ?>">
                            <?= $isOverdue ? '🚨' : ($isUrgent ? '⚠️' : '📅') ?>
                        </div>
                        <div class="notif-content">
                            <div class="notif-title"><?= htmlspecialchars($n['equipment_name']) ?></div>
                            <div class="notif-desc">
                                <?php if ($isOverdue): ?>
                                    Overdue <?= abs($n['days_left']) ?> day(s) — <?= htmlspecialchars($n['user_name']) ?>
                                <?php elseif ($n['days_left'] == 0): ?>
                                    Due today — <?= htmlspecialchars($n['user_name']) ?>
                                <?php else: ?>
                                    Due in <?= $n['days_left'] ?> day(s) — <?= htmlspecialchars($n['user_name']) ?>
                                <?php endif; ?>
                            </div>
                            <div class="notif-time"><?= date('d M Y', strtotime($n['due_date'])) ?></div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- User Menu -->
        <div class="navbar-user" id="userMenuBtn">
            <div class="navbar-user-avatar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <div class="navbar-user-name"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></div>
                <div class="navbar-user-role"><?= ucfirst($_SESSION['role'] ?? 'user') ?></div>
            </div>
            <svg class="caret" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>

            <!-- User Dropdown -->
            <div class="user-dropdown" id="userDropdown">
                <a href="index.php">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                    Dashboard
                </a>
                <div class="divider"></div>
                <a href="index.php?page=login&action=logout" class="logout-link">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>
