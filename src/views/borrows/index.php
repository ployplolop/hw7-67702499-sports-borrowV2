<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V19.5a2.25 2.25 0 002.25 2.25h.75"/>
        </svg>
        Borrow Records <em style="font-weight:400;font-size:0.55em;color:var(--color-text-muted);">(บันทึกการยืม-คืน)</em>
    </h1>
    <div class="actions">
        <a href="index.php?page=borrows&action=create" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            New Borrow
        </a>
    </div>
</div>

<!-- Borrow Records Table -->
<div class="data-table-wrapper">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <div class="table-search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Search records...">
            </div>
            <div class="table-filter">
                <select data-col="7">
                    <option value="">All Status</option>
                    <option value="borrowed">Borrowed</option>
                    <option value="returned">Returned</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>
        <div class="toolbar-right">
            <span class="text-sm text-muted"><?= count($records) ?> records</span>
        </div>
    </div>

    <table class="data-table striped">
        <thead>
            <tr>
                <th>Borrower</th>
                <th>Equipment</th>
                <th>Qty</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Note</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $r): ?>
            <?php
            $status = $r['status'];
            if ($status === 'borrowed' && strtotime($r['due_date']) < time()) $status = 'overdue';
            ?>
            <tr>
                <td>
                    <div class="cell-user">
                        <div class="cell-avatar"><?= strtoupper(substr($r['user_name'], 0, 1)) ?></div>
                        <span class="cell-name"><?= htmlspecialchars($r['user_name']) ?></span>
                    </div>
                </td>
                <td><?= htmlspecialchars($r['equipment_name']) ?></td>
                <td class="fw-600"><?= $r['quantity'] ?></td>
                <td><?= date('d M Y', strtotime($r['borrow_date'])) ?></td>
                <td><?= date('d M Y', strtotime($r['due_date'])) ?></td>
                <td><?= $r['return_date'] ? date('d M Y', strtotime($r['return_date'])) : '<span class="text-muted">—</span>' ?></td>
                <td><?= htmlspecialchars($r['note'] ?? '') ?: '<span class="text-muted">—</span>' ?></td>
                <td>
                    <span class="badge badge-<?= $status ?>"><?= ucfirst($status) ?></span>
                </td>
                <td>
                    <div class="cell-actions">
                        <?php if ($r['status'] === 'borrowed'): ?>
                            <a href="index.php?page=borrows&action=return&id=<?= $r['id'] ?>" class="btn btn-success btn-sm"
                               data-confirm="Confirm return this item?" data-confirm-type="success">Return</a>
                        <?php endif; ?>
                        <a href="index.php?page=borrows&action=delete&id=<?= $r['id'] ?>" class="btn btn-danger btn-sm"
                           data-confirm="Delete this record?" data-confirm-type="danger">Delete</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($records)): ?>
            <tr><td colspan="9" class="empty-state"><p>No borrow records found</p></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
