<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Equipment
    </h1>
    <div class="actions"><a href="index.php?page=equipment" class="btn btn-ghost">Back to list</a></div>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="index.php?page=equipment&action=store">
        <div class="form-group">
            <label>Name <span class="required">*</span></label>
            <input type="text" name="name" required placeholder="Equipment name">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Optional description"></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" placeholder="e.g. ball, racket, net">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Total Quantity <span class="required">*</span></label>
                <input type="number" name="total_quantity" value="1" min="1" required>
            </div>
            <div class="form-group">
                <label>Available Quantity <span class="required">*</span></label>
                <input type="number" name="available_qty" value="1" min="0" required>
            </div>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" placeholder="https://...">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Equipment</button>
            <a href="index.php?page=equipment" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
