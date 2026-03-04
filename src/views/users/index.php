<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
        </svg>
        Users
    </h1>
    <div class="actions">
        <button type="button" class="btn btn-primary" onclick="openUserModal('create')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Add User
        </button>
    </div>
</div>

<!-- Users Table -->
<div class="data-table-wrapper">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <div class="table-search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Search users...">
            </div>
            <div class="table-filter">
                <select data-col="4">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
        </div>
        <div class="toolbar-right">
            <span class="text-sm text-muted"><?= count($users) ?> users</span>
        </div>
    </div>

    <table class="data-table striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td>
                    <div class="cell-user">
                        <div class="cell-avatar"><?= strtoupper(substr($u['full_name'], 0, 1)) ?></div>
                        <div>
                            <div class="cell-name"><?= htmlspecialchars($u['full_name']) ?></div>
                            <div class="cell-sub">Joined <?= date('d M Y', strtotime($u['created_at'])) ?></div>
                        </div>
                    </div>
                </td>
                <td><code style="font-size:0.82rem;background:#f3f4f6;padding:2px 8px;border-radius:4px;"><?= htmlspecialchars($u['username']) ?></code></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
                <td>
                    <span class="badge badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
                </td>
                <td>
                    <div class="cell-actions">
                        <button type="button" class="btn btn-warning btn-sm" data-edit-user='<?= htmlspecialchars(json_encode($u), ENT_QUOTES, "UTF-8") ?>'>Edit</button>
                        <a href="index.php?page=users&action=delete&id=<?= $u['id'] ?>" class="btn btn-danger btn-sm"
                           data-confirm="Delete this user?" data-confirm-type="danger">Delete</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($users)): ?>
            <tr><td colspan="6" class="empty-state"><p>No users found</p></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- User Modal (Create / Edit) -->
<div class="modal-overlay" id="userModal">
    <div class="modal-box" style="width:560px;">
        <div class="modal-header">
            <h3 id="userModalTitle">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Add User
            </h3>
            <button class="modal-close" onclick="Modal.close('userModal')">✕</button>
        </div>
        <form id="userForm" method="POST" action="index.php?page=users&action=store">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Username <span class="required">*</span></label>
                        <input type="text" name="username" id="usr_username" required placeholder="Username">
                    </div>
                    <div class="form-group" id="usr_pw_group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" id="usr_password" required placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label>Full Name <span class="required">*</span></label>
                    <input type="text" name="full_name" id="usr_fullname" required placeholder="Full name">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" id="usr_email" required placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" id="usr_phone" placeholder="Phone number">
                    </div>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="usr_role">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="Modal.close('userModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="userSubmitBtn">Save User</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal(mode, data) {
    var form = document.getElementById('userForm');
    var title = document.getElementById('userModalTitle');
    var btn = document.getElementById('userSubmitBtn');
    var pwGroup = document.getElementById('usr_pw_group');
    var pwInput = document.getElementById('usr_password');
    var icon = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg> ';
    if (mode === 'edit' && data) {
        title.innerHTML = icon + 'Edit User';
        form.action = 'index.php?page=users&action=update&id=' + data.id;
        document.getElementById('usr_username').value = data.username || '';
        document.getElementById('usr_fullname').value = data.full_name || '';
        document.getElementById('usr_email').value = data.email || '';
        document.getElementById('usr_phone').value = data.phone || '';
        document.getElementById('usr_role').value = data.role || 'user';
        pwGroup.style.display = 'none';
        pwInput.required = false;
        btn.textContent = 'Update User';
    } else {
        title.innerHTML = icon + 'Add User';
        form.action = 'index.php?page=users&action=store';
        form.reset();
        pwGroup.style.display = '';
        pwInput.required = true;
        btn.textContent = 'Save User';
    }
    Modal.open('userModal');
}
document.querySelectorAll('[data-edit-user]').forEach(function(el) {
    el.addEventListener('click', function() {
        openUserModal('edit', JSON.parse(this.getAttribute('data-edit-user')));
    });
});
</script>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
