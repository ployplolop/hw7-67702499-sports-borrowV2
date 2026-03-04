<?php require __DIR__ . '/../views/layout/header.php'; ?>

<?php
require_once __DIR__ . '/../config/database.php';
$pdo = Database::connect();

$totalEquip    = $pdo->query('SELECT COUNT(*) FROM equipment')->fetchColumn();
$totalBorrowed = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'borrowed'")->fetchColumn();
$totalOverdue  = $pdo->query("SELECT COUNT(*) FROM borrow_records WHERE status = 'borrowed' AND due_date < NOW()")->fetchColumn();
$totalAvailable = $pdo->query('SELECT SUM(available_qty) FROM equipment')->fetchColumn() ?: 0;
$totalEquipQty  = $pdo->query('SELECT SUM(total_quantity) FROM equipment')->fetchColumn() ?: 1;

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

// Chart data: Borrow activity last 7 days
$borrowActivity = ['labels' => [], 'values' => []];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrow_records WHERE DATE(borrow_date) = ?");
    $stmt->execute([$date]);
    $borrowActivity['labels'][] = date('d M', strtotime($date));
    $borrowActivity['values'][] = (int)$stmt->fetchColumn();
}

// Chart data: Top borrowed equipment
$topEquipRows = $pdo->query("
    SELECT e.name, COUNT(*) as cnt
    FROM borrow_records br JOIN equipment e ON e.id = br.equipment_id
    GROUP BY br.equipment_id, e.name ORDER BY cnt DESC LIMIT 5
")->fetchAll();
$topEquipment = ['labels' => [], 'values' => []];
foreach ($topEquipRows as $row) {
    $topEquipment['labels'][] = $row['name'];
    $topEquipment['values'][] = (int)$row['cnt'];
}

// Chart data: Status distribution
$statusDist = $pdo->query("
    SELECT
        SUM(CASE WHEN status = 'borrowed' AND due_date >= NOW() THEN 1 ELSE 0 END) as borrowed,
        SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned,
        SUM(CASE WHEN status = 'borrowed' AND due_date < NOW() THEN 1 ELSE 0 END) as overdue
    FROM borrow_records
")->fetch();

$availablePercent = $totalEquipQty > 0 ? round(($totalAvailable / $totalEquipQty) * 100) : 0;
$borrowedPercent  = $totalEquipQty > 0 ? round(($totalBorrowed / max($totalEquipQty, 1)) * 100) : 0;
$overduePercent   = $totalBorrowed > 0 ? round(($totalOverdue / max($totalBorrowed, 1)) * 100) : 0;

// Data for quick-action modals
$allUsers     = $pdo->query("SELECT id, username, full_name FROM users ORDER BY full_name")->fetchAll();
$allEquipment = $pdo->query("SELECT id, name, available_qty FROM equipment ORDER BY name")->fetchAll();
?>

<!-- ===== KPI Cards ===== -->
<div class="kpi-grid">
    <div class="kpi-card kpi-blue card-hover">
        <div class="kpi-icon">📦</div>
        <div class="kpi-content">
            <div class="kpi-label">Total Equipment</div>
            <div class="kpi-value"><?= $totalEquip ?></div>
            <div class="kpi-trend">
                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M7 17l9.2-9.2M17 17V8h-9"/></svg>
                <?= $totalEquipQty ?> items total
            </div>
            <div class="kpi-progress"><div class="kpi-progress-bar" data-width="100%"></div></div>
        </div>
    </div>

    <div class="kpi-card kpi-orange card-hover">
        <div class="kpi-icon">📋</div>
        <div class="kpi-content">
            <div class="kpi-label">Borrowed Today</div>
            <div class="kpi-value"><?= $totalBorrowed ?></div>
            <div class="kpi-trend">
                <?= $borrowedPercent ?>% of capacity
            </div>
            <div class="kpi-progress"><div class="kpi-progress-bar" data-width="<?= $borrowedPercent ?>%"></div></div>
        </div>
    </div>

    <div class="kpi-card kpi-red card-hover">
        <div class="kpi-icon">⏰</div>
        <div class="kpi-content">
            <div class="kpi-label">Overdue Items</div>
            <div class="kpi-value"><?= $totalOverdue ?></div>
            <div class="kpi-trend">
                <?= $overduePercent ?>% of borrowed
            </div>
            <div class="kpi-progress"><div class="kpi-progress-bar" data-width="<?= $overduePercent ?>%"></div></div>
        </div>
    </div>

    <div class="kpi-card kpi-green card-hover">
        <div class="kpi-icon">✅</div>
        <div class="kpi-content">
            <div class="kpi-label">Available Equipment</div>
            <div class="kpi-value"><?= $totalAvailable ?></div>
            <div class="kpi-trend">
                <?= $availablePercent ?>% available
            </div>
            <div class="kpi-progress"><div class="kpi-progress-bar" data-width="<?= $availablePercent ?>%"></div></div>
        </div>
    </div>
</div>

<!-- ===== Quick Actions ===== -->
<div class="quick-actions">
    <a href="javascript:void(0)" class="quick-btn qb-borrow" onclick="Modal.open('borrowModal')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        New Borrow
    </a>
    <a href="javascript:void(0)" class="quick-btn qb-equip" onclick="Modal.open('equipModal')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Equipment
    </a>
    <a href="javascript:void(0)" class="quick-btn qb-user" onclick="Modal.open('userModal')">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Add User
    </a>
    <a href="index.php?page=calendar" class="quick-btn qb-cal">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Borrow Calendar
    </a>
</div>

<!-- ===== Charts ===== -->
<div class="charts-grid-3">
    <!-- Line Chart: Borrow Activity -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                Borrow Activity (7 Days)
            </div>
        </div>
        <div style="height:220px;"><canvas id="borrowActivityChart"></canvas></div>
    </div>

    <!-- Bar Chart: Top Equipment -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-4.5A1.5 1.5 0 0015 12.75h-1.5a1.5 1.5 0 00-1.5 1.5v4.5m6 0h.75a.75.75 0 00.75-.75V6a.75.75 0 00-.75-.75H18"/></svg>
                Top Borrowed Equipment
            </div>
        </div>
        <div style="height:220px;"><canvas id="topEquipmentChart"></canvas></div>
    </div>

    <!-- Pie Chart: Status Distribution -->
    <div class="chart-card">
        <div class="chart-header">
            <div class="chart-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                Status Distribution
            </div>
        </div>
        <div style="height:220px;"><canvas id="statusPieChart"></canvas></div>
    </div>
</div>

<!-- ===== Recent Borrows & Due Soon ===== -->
<div class="content-grid">
    <!-- Recent Borrows Table -->
    <div class="data-table-wrapper">
        <div class="table-toolbar">
            <div class="section-title" style="margin-bottom:0;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Recent Borrows
            </div>
            <a href="index.php?page=borrows" class="btn btn-outline btn-sm">View All</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Equipment</th>
                    <th>Qty</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($recentBorrows as $r): ?>
                <tr>
                    <td>
                        <div class="cell-user">
                            <div class="cell-avatar"><?= strtoupper(substr($r['user_name'], 0, 1)) ?></div>
                            <span class="cell-name"><?= htmlspecialchars($r['user_name']) ?></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                    <td><?= $r['quantity'] ?></td>
                    <td><?= date('d M Y', strtotime($r['borrow_date'])) ?></td>
                    <td><?= date('d M Y', strtotime($r['due_date'])) ?></td>
                    <td>
                        <?php
                        $status = $r['status'];
                        if ($status === 'borrowed' && strtotime($r['due_date']) < time()) $status = 'overdue';
                        ?>
                        <span class="badge badge-<?= $status ?>"><?= ucfirst($status) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($recentBorrows)): ?>
                <tr><td colspan="6" class="empty-state"><p>No records yet</p></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Due Soon -->
    <div class="card">
        <div class="section-title" style="margin-bottom:16px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
            Due Soon (3 Days)
        </div>
        <?php if (empty($dueSoon)): ?>
            <div class="empty-state">
                <p>🎉 No upcoming dues</p>
            </div>
        <?php endif; ?>
        <?php foreach ($dueSoon as $d): ?>
            <div class="due-item <?= ($d['days_left'] <= 1) ? 'urgent' : '' ?>">
                <div class="due-info">
                    <div class="equip-name"><?= htmlspecialchars($d['equipment_name']) ?></div>
                    <div class="borrower"><?= htmlspecialchars($d['user_name']) ?> · Due <?= date('d M', strtotime($d['due_date'])) ?></div>
                </div>
                <span class="due-days <?= ($d['days_left'] <= 1) ? 'crit' : 'warn' ?>">
                    <?= $d['days_left'] <= 0 ? 'Today' : $d['days_left'] . 'd' ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Chart Data -->
<script>
var borrowActivityData = <?= json_encode($borrowActivity) ?>;
var topEquipmentData = <?= json_encode($topEquipment) ?>;
var statusDistData = <?= json_encode($statusDist) ?>;
</script>

<!-- ===== Dashboard Create Modals ===== -->

<!-- Borrow Modal -->
<div class="modal-overlay" id="borrowModal">
    <div class="modal-box" style="width:560px;">
        <div class="modal-header">
            <h3>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Borrow
            </h3>
            <button class="modal-close" onclick="Modal.close('borrowModal')">✕</button>
        </div>
        <form method="POST" action="index.php?page=borrows&action=store">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>User <span class="required">*</span></label>
                        <select name="user_id" required>
                            <option value="">-- Select User --</option>
                            <?php foreach ($allUsers as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?> (<?= $u['username'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Equipment <span class="required">*</span></label>
                        <select name="equipment_id" required>
                            <option value="">-- Select Equipment --</option>
                            <?php foreach ($allEquipment as $eq): ?>
                                <option value="<?= $eq['id'] ?>"><?= htmlspecialchars($eq['name']) ?> (avail: <?= $eq['available_qty'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Quantity <span class="required">*</span></label>
                        <input type="number" name="quantity" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Due Date <span class="required">*</span></label>
                        <input type="datetime-local" name="due_date" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Note</label>
                    <textarea name="note" rows="2" placeholder="Optional note..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="Modal.close('borrowModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Borrow</button>
            </div>
        </form>
    </div>
</div>

<!-- Equipment Modal -->
<div class="modal-overlay" id="equipModal">
    <div class="modal-box" style="width:560px;">
        <div class="modal-header">
            <h3>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Add Equipment
            </h3>
            <button class="modal-close" onclick="Modal.close('equipModal')">✕</button>
        </div>
        <form method="POST" action="index.php?page=equipment&action=store">
            <div class="modal-body">
                <div class="form-group">
                    <label>Name <span class="required">*</span></label>
                    <input type="text" name="name" required placeholder="Equipment name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="2" placeholder="Optional description"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" placeholder="e.g. ball, racket">
                    </div>
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" placeholder="https://...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Total Quantity <span class="required">*</span></label>
                        <input type="number" name="total_quantity" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Available Qty <span class="required">*</span></label>
                        <input type="number" name="available_qty" value="1" min="0" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="Modal.close('equipModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Equipment</button>
            </div>
        </form>
    </div>
</div>

<!-- User Modal -->
<div class="modal-overlay" id="userModal">
    <div class="modal-box" style="width:560px;">
        <div class="modal-header">
            <h3>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Add User
            </h3>
            <button class="modal-close" onclick="Modal.close('userModal')">✕</button>
        </div>
        <form method="POST" action="index.php?page=users&action=store">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Username <span class="required">*</span></label>
                        <input type="text" name="username" required placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" required placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" name="full_name" required placeholder="Full name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" required placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="Phone number">
                    </div>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="Modal.close('userModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../views/layout/footer.php'; ?>
