<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Borrow Records</h2>
<p style="margin-bottom:16px;">
    <a href="index.php?page=borrows&action=create" class="btn btn-primary">+ New Borrow</a>
</p>

<table>
    <thead>
        <tr>
            <th>#</th><th>User</th><th>Equipment</th><th>Qty</th>
            <th>Borrow Date</th><th>Due Date</th><th>Return Date</th><th>Status</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($records as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['user_name']) ?></td>
            <td><?= htmlspecialchars($r['equipment_name']) ?></td>
            <td><?= $r['quantity'] ?></td>
            <td><?= $r['borrow_date'] ?></td>
            <td><?= $r['due_date'] ?></td>
            <td><?= $r['return_date'] ?? '-' ?></td>
            <td>
                <span class="badge badge-<?= $r['status'] ?>"><?= ucfirst($r['status']) ?></span>
            </td>
            <td class="actions">
                <?php if ($r['status'] === 'borrowed'): ?>
                    <a href="index.php?page=borrows&action=return&id=<?= $r['id'] ?>" class="btn btn-success"
                       onclick="return confirm('Confirm return?')">Return</a>
                <?php endif; ?>
                <a href="index.php?page=borrows&action=delete&id=<?= $r['id'] ?>" class="btn btn-danger"
                   onclick="return confirm('Delete this record?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
