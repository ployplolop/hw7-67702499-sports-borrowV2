<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Edit Equipment</h2>

<div class="card">
    <form method="POST" action="index.php?page=equipment&action=update&id=<?= $equipment['id'] ?>">
        <div class="form-group">
            <label>Name</label>
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
        <div class="form-group">
            <label>Total Quantity</label>
            <input type="number" name="total_quantity" value="<?= $equipment['total_quantity'] ?>" min="1" required>
        </div>
        <div class="form-group">
            <label>Available Quantity</label>
            <input type="number" name="available_qty" value="<?= $equipment['available_qty'] ?>" min="0" required>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" value="<?= htmlspecialchars($equipment['image_url'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php?page=equipment" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
