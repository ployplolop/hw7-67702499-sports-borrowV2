<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Add Equipment</h2>

<div class="card">
    <form method="POST" action="index.php?page=equipment&action=store">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label>Category</label>
            <input type="text" name="category" placeholder="e.g. ball, racket, net">
        </div>
        <div class="form-group">
            <label>Total Quantity</label>
            <input type="number" name="total_quantity" value="1" min="1" required>
        </div>
        <div class="form-group">
            <label>Available Quantity</label>
            <input type="number" name="available_qty" value="1" min="0" required>
        </div>
        <div class="form-group">
            <label>Image URL</label>
            <input type="text" name="image_url" placeholder="https://...">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php?page=equipment" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
