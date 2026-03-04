<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<h2>Edit User</h2>

<div class="card">
    <form method="POST" action="index.php?page=users&action=update&id=<?= $user['id'] ?>">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="user"  <?= $user['role'] === 'user'  ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php?page=users" class="btn btn-danger">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
