<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Users</h2>
<p style="margin-bottom:16px;">
    <a href="index.php?page=users&action=create" class="btn btn-success">+ Add User</a>
</p>

<table>
    <thead>
        <tr>
            <th>#</th><th>Username</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
            <td><?= ucfirst($u['role']) ?></td>
            <td class="actions">
                <a href="index.php?page=users&action=edit&id=<?= $u['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="index.php?page=users&action=delete&id=<?= $u['id'] ?>" class="btn btn-danger"
                   onclick="return confirm('Delete this user?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
