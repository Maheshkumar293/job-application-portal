<!DOCTYPE html>
<html>

<head>
    <title>Staff Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('public/assets/css/staff.css') ?>">
</head>

<body class="bg-dark text-white p-4">

    <div class="container">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold">My Assigned Applications</h3>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light">
                Logout
            </a>
        </div>

        <?php if (empty($apps)): ?>
            <div class="alert alert-secondary text-center">
                No applications assigned to you yet.
            </div>
        <?php else: ?>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Assigned By</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($apps as $app): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($app['full_name']) ?></td>
                                <td><?= esc($app['email']) ?></td>
                                <td><?= esc($app['mobile']) ?></td>

                                <!-- STATUS UPDATE -->
                                <td>
                                    <select class="form-select form-select-sm staff-status" data-id="<?= $app['id'] ?>"
                                        data-prev="<?= $app['application_status'] ?>">
                                        <?php foreach (['submitted', 'under_review', 'shortlisted', 'selected', 'rejected'] as $s): ?>
                                            <option value="<?= $s ?>" <?= $app['application_status'] === $s ? 'selected' : '' ?>>
                                                <?= ucfirst(str_replace('_', ' ', $s)) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </td>

                                <!-- RESUME -->
                                <td>
                                    <a href="<?= base_url('resume/' . basename($app['resume_path'])) ?>" target="_blank"
                                        class="btn btn-sm btn-outline-info">
                                        📄 View
                                    </a>

                                </td>

                                <td><?= esc($app['assigned_by'] ?? 'Admin') ?></td>

                                <td><?= date('d M Y', strtotime($app['submitted_at'])) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        <?php endif ?>

    </div>

    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- GLOBAL VARS -->
    <script>
        const BASE_URL = "<?= base_url() ?>";
        const CSRF_NAME = "<?= csrf_token() ?>";
        let CSRF_HASH = "<?= csrf_hash() ?>";
    </script>

    <!-- STATUS UPDATE SCRIPT -->
    <script>
        document.querySelectorAll('.staff-status').forEach(select => {

            select.addEventListener('change', function () {

                const appId = this.dataset.id;
                const prev = this.dataset.prev;
                const status = this.value;

                if (status === prev) return;

                const ok = confirm(`Change status to "${status.replace('_', ' ')}"?`);
                if (!ok) {
                    this.value = prev;
                    return;
                }

                fetch(`${BASE_URL}update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body:
                        `id=${appId}` +
                        `&status=${status}` +
                        `&${CSRF_NAME}=${CSRF_HASH}`
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            this.dataset.prev = status;
                            if (res.csrf) CSRF_HASH = res.csrf;
                        } else {
                            alert(res.msg || 'Update failed');
                            this.value = prev;
                        }
                    })
                    .catch(() => {
                        alert('Network error');
                        this.value = prev;
                    });

            });

        });
    </script>


</body>

</html>