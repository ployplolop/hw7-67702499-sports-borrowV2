<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Add User</h2>

<div class="card">
    <form method="POST" action="index.php?page=users&action=store">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone">
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="index.php?page=users" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
