<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Add User
    </h1>
    <div class="actions"><a href="index.php?page=users" class="btn btn-ghost">Back to list</a></div>
</div>

<div class="card" style="max-width:640px;">
    <form method="POST" action="index.php?page=users&action=store">
        <div class="form-row">
            <div class="form-group">
                <label>Username <span class="required">*</span></label>
                <input type="text" name="username" required placeholder="Username">
            </div>
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" required placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label>Full Name <span class="required">*</span></label>
            <input type="text" name="full_name" required placeholder="Full name">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" required placeholder="email@example.com">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Phone number">
            </div>
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save User</button>
            <a href="index.php?page=users" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
