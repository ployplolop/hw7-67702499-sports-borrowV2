<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Equipment List</h2>
<p style="margin-bottom:16px;">
    <a href="index.php?page=equipment&action=create" class="btn btn-success">+ Add Equipment</a>
</p>

<table>
    <thead>
        <tr>
            <th>#</th><th>Name</th><th>Category</th><th>Total</th><th>Available</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($equipmentList as $eq): ?>
        <tr>
            <td><?= $eq['id'] ?></td>
            <td><?= htmlspecialchars($eq['name']) ?></td>
            <td><?= htmlspecialchars($eq['category'] ?? '-') ?></td>
            <td><?= $eq['total_quantity'] ?></td>
            <td><?= $eq['available_qty'] ?></td>
            <td class="actions">
                <a href="index.php?page=equipment&action=edit&id=<?= $eq['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="index.php?page=equipment&action=delete&id=<?= $eq['id'] ?>" class="btn btn-danger"
                   onclick="return confirm('Delete this equipment?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
