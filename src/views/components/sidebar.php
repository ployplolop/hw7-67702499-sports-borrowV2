<?php
/**
 * Sidebar Component
 * Required: $_GET['page'] for active state, $_SESSION for user info
 */
$currentPage = $_GET['page'] ?? 'dashboard';

// Notification count for sidebar badge
require_once __DIR__ . '/../../config/database.php';
$__sidebarPdo = Database::connect();
$__overdueCount = (int) $__sidebarPdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'borrowed' AND due_date < NOW()")->fetchColumn();
?>
<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">🏅</div>
        <div>
            <div class="brand-text">Sports Borrow</div>
            <div class="brand-sub">Equipment System</div>
        </div>
    </div>

    <!-- Menu -->
    <nav class="sidebar-menu">
        <div class="sidebar-menu-label">Main Menu</div>

        <a href="index.php" class="sidebar-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            </svg>
            Dashboard
        </a>

        <a href="index.php?page=equipment" class="sidebar-item <?= $currentPage === 'equipment' ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Equipment
        </a>

        <a href="index.php?page=borrows" class="sidebar-item <?= $currentPage === 'borrows' ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V19.5a2.25 2.25 0 002.25 2.25h.75"/>
            </svg>
            Borrows
            <?php if ($__overdueCount > 0): ?>
                <span class="item-badge"><?= $__overdueCount ?></span>
            <?php endif; ?>
        </a>

        <a href="index.php?page=calendar" class="sidebar-item <?= $currentPage === 'calendar' ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            Calendar
        </a>

        <div class="sidebar-menu-label" style="margin-top:16px;">Management</div>

        <a href="index.php?page=users" class="sidebar-item <?= $currentPage === 'users' ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
            Users
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="sidebar-user-info">
                <div class="name"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></div>
                <div class="role"><?= ucfirst($_SESSION['role'] ?? 'user') ?></div>
            </div>
        </div>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
