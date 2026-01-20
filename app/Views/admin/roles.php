<!DOCTYPE html>
<html>
<head>
    <title>Manage Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white p-4">

<div class="container">

    <div class="d-flex justify-content-between mb-4">
        <h3>Role Management</h3>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-light">← Back</a>
    </div>

    <?php if (session('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif ?>

    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif ?>

    <!-- ADD ROLE -->
    <form method="post" action="<?= base_url('admin/roles/create') ?>" class="row g-2 mb-4">
        <?= csrf_field() ?>
        <div class="col-md-8">
            <input name="role_name" class="form-control" placeholder="New role name">
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary w-100">Add Role</button>
        </div>
    </form>

    <!-- ROLES TABLE -->
    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($roles as $r): ?>
            <tr>
                <td><?= esc($r['role_name']) ?></td>
                <td>
                    <span class="badge bg-<?= $r['status']==='active'?'success':'secondary' ?>">
                        <?= ucfirst($r['status']) ?>
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-warning toggle-role"
                            data-id="<?= $r['id'] ?>">
                        Toggle
                    </button>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

</div>

<script>
document.querySelectorAll('.toggle-role').forEach(btn => {
    btn.onclick = () => {
        fetch('<?= base_url('admin/roles/toggle') ?>', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:`id=${btn.dataset.id}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
        })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success){
                alert(d.msg || 'Cannot change role');
            } else {
                location.reload();
            }
        });
    };
});
</script>

</body>
</html>
