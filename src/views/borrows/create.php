<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Borrow Equipment</h2>

<div class="card">
    <form method="POST" action="index.php?page=borrows&action=store">
        <div class="form-group">
            <label>User</label>
            <select name="user_id" required>
                <option value="">-- Select User --</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?> (<?= $u['username'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Equipment</label>
            <select name="equipment_id" required>
                <option value="">-- Select Equipment --</option>
                <?php foreach ($equipmentList as $eq): ?>
                    <option value="<?= $eq['id'] ?>"><?= htmlspecialchars($eq['name']) ?> (avail: <?= $eq['available_qty'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" value="1" min="1" required>
        </div>
        <div class="form-group">
            <label>Due Date</label>
            <input type="datetime-local" name="due_date" required>
        </div>
        <div class="form-group">
            <label>Note</label>
            <textarea name="note" rows="2"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Borrow</button>
        <a href="index.php?page=borrows" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
