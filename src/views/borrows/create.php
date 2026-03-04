<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Borrow Equipment
    </h1>
    <div class="actions"><a href="index.php?page=borrows" class="btn btn-ghost">Back to list</a></div>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="index.php?page=borrows&action=store">
        <div class="form-row">
            <div class="form-group">
                <label>User <span class="required">*</span></label>
                <select name="user_id" required>
                    <option value="">-- Select User --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?> (<?= $u['username'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Equipment <span class="required">*</span></label>
                <select name="equipment_id" required>
                    <option value="">-- Select Equipment --</option>
                    <?php foreach ($equipmentList as $eq): ?>
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
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Submit Borrow</button>
            <a href="index.php?page=borrows" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
