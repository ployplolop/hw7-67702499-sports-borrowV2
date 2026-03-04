<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit Equipment
    </h1>
    <div class="actions"><a href="index.php?page=equipment" class="btn btn-ghost">Back to list</a></div>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="index.php?page=equipment&action=update&id=<?= $equipment['id'] ?>">
        <div class="form-group">
            <label>Name <span class="required">*</span></label>
            <input type="text" name="name" value="<?= htmlspecialchars($equipment['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($equipment['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" value="<?= htmlspecialchars($equipment['category'] ?? '') ?>">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Total Quantity <span class="required">*</span></label>
                <input type="number" name="total_quantity" value="<?= $equipment['total_quantity'] ?>" min="1" required>
            </div>
            <div class="form-group">
                <label>Available Quantity <span class="required">*</span></label>
                <input type="number" name="available_qty" value="<?= $equipment['available_qty'] ?>" min="0" required>
            </div>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" value="<?= htmlspecialchars($equipment['image_url'] ?? '') ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Equipment</button>
            <a href="index.php?page=equipment" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
