<?php require __DIR__ . '/../views/layout/header.php'; ?>

<?php
require_once __DIR__ . '/../config/database.php';
$pdo = Database::connect();

$totalEquip   = $pdo->query('SELECT COUNT(*) FROM equipment')->fetchColumn();
$totalBorrowed = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'borrowed'")->fetchColumn();
$totalOverdue  = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'borrowed' AND due_date < NOW()")->fetchColumn();
$totalAvailable = $pdo->query('SELECT SUM(available_qty) FROM equipment')->fetchColumn() ?: 0;
$userCount     = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$returnCount   = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'returned'")->fetchColumn();

// Recent borrow records
$recentBorrows = $pdo->query("
    SELECT br.*, u.full_name AS user_name, e.name AS equipment_name
    FROM borrow_records br
    JOIN users u ON u.id = br.user_id
    JOIN equipment e ON e.id = br.equipment_id
    ORDER BY br.id DESC LIMIT 5
")->fetchAll();

// Due soon (within 3 days)
$dueSoon = $pdo->query("
    SELECT br.*, u.full_name AS user_name, e.name AS equipment_name,
           DATEDIFF(br.due_date, NOW()) AS days_left
    FROM borrow_records br
    JOIN users u ON u.id = br.user_id
    JOIN equipment e ON e.id = br.equipment_id
    WHERE br.status = 'borrowed' AND br.due_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 DAY)
    ORDER BY br.due_date ASC
")->fetchAll();
?>

<style>
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 24px 28px;
        display: flex; align-items: center; gap: 18px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border-left: 4px solid transparent;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,.1); }
    .stat-card .icon {
        width: 56px; height: 56px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .stat-card .info h3 { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-card .info p  { font-size: .85rem; color: #7f8c8d; margin-top: 4px; }

    .stat-equip    { border-color: #3b82f6; }
    .stat-equip .icon { background: rgba(59,130,246,.1); color: #3b82f6; }
    .stat-borrowed { border-color: #f59e0b; }
    .stat-borrowed .icon { background: rgba(245,158,11,.1); color: #f59e0b; }
    .stat-overdue  { border-color: #ef4444; }
    .stat-overdue .icon { background: rgba(239,68,68,.1); color: #ef4444; }
    .stat-avail    { border-color: #22c55e; }
    .stat-avail .icon { background: rgba(34,197,94,.1); color: #22c55e; }

    .section-title {
        font-size: 1.1rem; font-weight: 700; color: #2c3e50; margin-bottom: 16px;
        display: flex; align-items: center; gap: 8px;
    }
    .section-title svg { width: 20px; height: 20px; }

    .recent-table { width: 100%; border-collapse: collapse; }
    .recent-table th { background: #f8fafc; color: #64748b; font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; padding: 10px 14px; text-align: left; }
    .recent-table td { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; font-size: .9rem; }

    .due-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px; border-radius: 10px; margin-bottom: 8px;
        background: #fffbeb; border: 1px solid #fde68a;
    }
    .due-item.urgent { background: #fef2f2; border-color: #fecaca; }
    .due-days {
        font-weight: 700; font-size: .85rem; padding: 4px 12px;
        border-radius: 20px; color: #fff;
    }
    .due-days.warn { background: #f59e0b; }
    .due-days.crit { background: #ef4444; }

    .quick-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .quick-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; border-radius: 10px; font-size: .9rem; font-weight: 600;
        color: #fff; text-decoration: none;
        transition: transform .15s, box-shadow .15s;
    }
    .quick-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
    .quick-btn svg { width: 18px; height: 18px; }
    .qb-borrow  { background: linear-gradient(135deg, #3b82f6, #6366f1); }
    .qb-equip   { background: linear-gradient(135deg, #22c55e, #16a34a); }
    .qb-user    { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .qb-cal     { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
</style>

<!-- ===== Stats Cards ===== -->
<h2 style="display:flex;align-items:center;gap:10px;">
    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
    Dashboard
</h2>

<div class="stat-grid">
    <div class="stat-card stat-equip">
        <div class="icon">📦</div>
        <div class="info">
            <h3><?= $totalEquip ?></h3>
            <p>Total Equipment</p>
        </div>
    </div>
    <div class="stat-card stat-borrowed">
        <div class="icon">📋</div>
        <div class="info">
            <h3><?= $totalBorrowed ?></h3>
            <p>Borrowed</p>
        </div>
    </div>
    <div class="stat-card stat-overdue">
        <div class="icon">⏰</div>
        <div class="info">
            <h3><?= $totalOverdue ?></h3>
            <p>Overdue</p>
        </div>
    </div>
    <div class="stat-card stat-avail">
        <div class="icon">✅</div>
        <div class="info">
            <h3><?= $totalAvailable ?></h3>
            <p>Available</p>
        </div>
    </div>
</div>

<!-- ===== Quick Actions ===== -->
<div class="card" style="margin-bottom:24px;">
    <div class="section-title">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Quick Actions
    </div>
    <div class="quick-actions">
        <a href="index.php?page=borrows&action=create" class="quick-btn qb-borrow">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Borrow
        </a>
        <a href="index.php?page=equipment&action=create" class="quick-btn qb-equip">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Equipment
        </a>
        <a href="index.php?page=users&action=create" class="quick-btn qb-user">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add User
        </a>
        <a href="index.php?page=calendar" class="quick-btn qb-cal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Borrow Calendar
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap: 20px;">

    <!-- ===== Recent Borrows ===== -->
    <div class="card">
        <div class="section-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Recent Borrow Records
        </div>
        <table class="recent-table">
            <thead>
                <tr><th>User</th><th>Equipment</th><th>Qty</th><th>Due Date</th><th>Status</th></tr>
            </thead>
            <tbody>
            <?php foreach ($recentBorrows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['user_name']) ?></td>
                    <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                    <td><?= $r['quantity'] ?></td>
                    <td><?= date('d M Y', strtotime($r['due_date'])) ?></td>
                    <td><span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($recentBorrows)): ?>
                <tr><td colspan="5" style="text-align:center;color:#aaa;padding:20px;">No records yet</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== Due Soon ===== -->
    <div class="card">
        <div class="section-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19H19a2 2 0 001.75-2.97L13.75 4.16a2 2 0 00-3.5 0L3.32 16.03A2 2 0 005.07 19z"/></svg>
            Due Soon (3 Days)
        </div>
        <?php if (empty($dueSoon)): ?>
            <p style="text-align:center;color:#aaa;padding:20px;">🎉 No upcoming dues</p>
        <?php endif; ?>
        <?php foreach ($dueSoon as $d): ?>
            <div class="due-item <?= ($d['days_left'] <= 1) ? 'urgent' : '' ?>">
                <div>
                    <div style="font-weight:600;font-size:.9rem;"><?= htmlspecialchars($d['equipment_name']) ?></div>
                    <div style="font-size:.8rem;color:#6b7280;"><?= htmlspecialchars($d['user_name']) ?> · Due <?= date('d M', strtotime($d['due_date'])) ?></div>
                </div>
                <span class="due-days <?= ($d['days_left'] <= 1) ? 'crit' : 'warn' ?>">
                    <?= $d['days_left'] <= 0 ? 'Today' : $d['days_left'] . 'd' ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php require __DIR__ . '/../views/layout/footer.php'; ?>
