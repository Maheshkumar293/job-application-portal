<!DOCTYPE html>
<html>
<head>
    <title>Staff Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white p-4">

<div class="container">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Staff Management</h3>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-light">
            ← Back
        </a>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (session('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif ?>

    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif ?>

    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <!-- ADD STAFF -->
    <form method="post" action="<?= base_url('admin/staffs/create') ?>" class="row g-2 mb-4">
        <?= csrf_field() ?>

        <div class="col-md-3">
            <input name="name" class="form-control" placeholder="Full Name" required>
        </div>

        <div class="col-md-3">
            <input name="email" type="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="col-md-3">
            <input name="password" type="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">
                ➕ Add Staff
            </button>
        </div>
    </form>

    <!-- STAFF TABLE -->
    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Staff Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php if (empty($staffs)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No staff accounts found
                </td>
            </tr>
        <?php endif ?>

        <?php foreach ($staffs as $i => $s): ?>
            <tr>
                <td><?= $i + 1 ?></td>

                <td><?= esc($s['name']) ?></td>

                <td><?= esc($s['email']) ?></td>

                <td>
                    <span class="badge bg-<?= $s['status'] === 'active' ? 'success' : 'secondary' ?>">
                        <?= ucfirst($s['status']) ?>
                    </span>
                </td>

                <td>
                    <small><?= date('d M Y', strtotime($s['created_at'])) ?></small>
                </td>

                <td>
                    <?php if ($s['status'] === 'active'): ?>
                        <form method="post"
                              action="<?= base_url('admin/staffs/block/'.$s['id']) ?>"
                              onsubmit="return confirm('Block this staff account?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger">
                                Block
                            </button>
                        </form>
                    <?php else: ?>
                        <form method="post"
                              action="<?= base_url('admin/staffs/unblock/'.$s['id']) ?>"
                              onsubmit="return confirm('Unblock this staff account?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-success">
                                UnBlock
                            </button>
                        </form>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

</div>

</body>
</html>
