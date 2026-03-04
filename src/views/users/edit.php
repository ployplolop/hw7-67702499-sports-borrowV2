<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        Edit User
    </h1>
    <div class="actions"><a href="index.php?page=users" class="btn btn-ghost">Back to list</a></div>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="index.php?page=users&action=update&id=<?= $user['id'] ?>">
        <div class="form-group">
            <label>Username <span class="required">*</span></label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="form-group">
            <label>Full Name <span class="required">*</span></label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="user"  <?= $user['role'] === 'user'  ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="index.php?page=users" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
