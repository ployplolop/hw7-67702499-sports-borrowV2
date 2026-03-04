<?php require __DIR__ . '/../../views/layout/header.php'; ?>

<?php
$categories = array_unique(array_filter(array_column($equipmentList, 'category')));
sort($categories);
$categoryIcons = ['ball' => '⚽', 'racket' => '🏸', 'net' => '🏐', 'fitness' => '🧘'];
?>

<!-- Page Header -->
<div class="page-header">
    <h1>
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
        </svg>
        Equipment <em style="font-weight:400;font-size:0.55em;color:var(--color-text-muted);">(จัดการคลังอุปกรณ์)</em>
    </h1>
    <div class="actions">
        <button type="button" class="btn btn-primary" onclick="openEquipModal('create')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Equipment
        </button>
    </div>
</div>

<!-- Equipment Table -->
<div class="data-table-wrapper">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <div class="table-search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input type="text" placeholder="Search equipment...">
            </div>
            <div class="table-filter">
                <select data-col="1">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= ucfirst(htmlspecialchars($cat)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="toolbar-right">
            <span class="text-sm text-muted"><?= count($equipmentList) ?> items</span>
        </div>
    </div>

    <table class="data-table striped">
        <thead>
            <tr>
                <th>Equipment</th>
                <th>Category</th>
                <th>Total</th>
                <th>Available</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($equipmentList as $eq): ?>
            <?php $icon = $categoryIcons[$eq['category'] ?? ''] ?? '📦'; ?>
            <tr>
                <td>
                    <div class="cell-equip">
                        <div class="cell-equip-img"><?= $icon ?></div>
                        <div>
                            <div class="cell-name"><?= htmlspecialchars($eq['name']) ?></div>
                            <?php if ($eq['description']): ?>
                                <div class="cell-sub"><?= htmlspecialchars(mb_strimwidth($eq['description'], 0, 40, '...')) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-user"><?= ucfirst(htmlspecialchars($eq['category'] ?? 'N/A')) ?></span></td>
                <td class="fw-600"><?= $eq['total_quantity'] ?></td>
                <td class="fw-600"><?= $eq['available_qty'] ?></td>
                <td>
                    <?php if ($eq['available_qty'] > 0): ?>
                        <span class="badge badge-active"><span class="status-dot green"></span>Available</span>
                    <?php else: ?>
                        <span class="badge badge-overdue"><span class="status-dot red"></span>Out of Stock</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="cell-actions">
                        <button type="button" class="btn btn-warning btn-sm" data-edit-equip='<?= htmlspecialchars(json_encode($eq), ENT_QUOTES, "UTF-8") ?>'>Edit</button>
                        <a href="index.php?page=equipment&action=delete&id=<?= $eq['id'] ?>" class="btn btn-danger btn-sm"
                           data-confirm="Delete this equipment?" data-confirm-type="danger">Delete</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($equipmentList)): ?>
            <tr><td colspan="6" class="empty-state"><p>No equipment found</p></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Equipment Modal (Create / Edit) -->
<div class="modal-overlay" id="equipModal">
    <div class="modal-box" style="width:560px;">
        <div class="modal-header">
            <h3 id="equipModalTitle">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Add Equipment
            </h3>
            <button class="modal-close" onclick="Modal.close('equipModal')">✕</button>
        </div>
        <form id="equipForm" method="POST" action="index.php?page=equipment&action=store">
            <div class="modal-body">
                <div class="form-group">
                    <label>Name <span class="required">*</span></label>
                    <input type="text" name="name" id="eq_name" required placeholder="Equipment name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="eq_description" rows="2" placeholder="Optional description"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" id="eq_category" placeholder="e.g. ball, racket">
                    </div>
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image_url" id="eq_image_url" placeholder="https://...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Total Quantity <span class="required">*</span></label>
                        <input type="number" name="total_quantity" id="eq_total_qty" value="1" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Available Qty <span class="required">*</span></label>
                        <input type="number" name="available_qty" id="eq_avail_qty" value="1" min="0" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="Modal.close('equipModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="equipSubmitBtn">Save Equipment</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEquipModal(mode, data) {
    var form = document.getElementById('equipForm');
    var title = document.getElementById('equipModalTitle');
    var btn = document.getElementById('equipSubmitBtn');
    var icon = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg> ';
    if (mode === 'edit' && data) {
        title.innerHTML = icon + 'Edit Equipment';
        form.action = 'index.php?page=equipment&action=update&id=' + data.id;
        document.getElementById('eq_name').value = data.name || '';
        document.getElementById('eq_description').value = data.description || '';
        document.getElementById('eq_category').value = data.category || '';
        document.getElementById('eq_total_qty').value = data.total_quantity || 1;
        document.getElementById('eq_avail_qty').value = data.available_qty || 0;
        document.getElementById('eq_image_url').value = data.image_url || '';
        btn.textContent = 'Update Equipment';
    } else {
        title.innerHTML = icon + 'Add Equipment';
        form.action = 'index.php?page=equipment&action=store';
        form.reset();
        document.getElementById('eq_total_qty').value = '1';
        document.getElementById('eq_avail_qty').value = '1';
        btn.textContent = 'Save Equipment';
    }
    Modal.open('equipModal');
}
document.querySelectorAll('[data-edit-equip]').forEach(function(el) {
    el.addEventListener('click', function() {
        openEquipModal('edit', JSON.parse(this.getAttribute('data-edit-equip')));
    });
});
</script>

<?php require __DIR__ . '/../../views/layout/footer.php'; ?>
