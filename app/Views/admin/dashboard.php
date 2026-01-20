<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/admin.css') ?>" rel="stylesheet">
</head>

<body>

    <div class="dashboard-shell">

        <!-- TOP BAR -->
        <nav class="topbar">
            <div class="brand">
                <span class="logo">🧠</span>
                <strong>Recruitment Admin</strong>
            </div>

            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/roles') ?>" class="btn btn-outline-info btn-sm">
                    ⚙️ Manage Roles
                </a>

                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">
                    Logout
                </a>
            </div>
        </nav>


        <!-- CONTENT -->
        <div class="dashboard-content container-fluid">

            <!-- KPI GRID -->
            <div class="row g-4 mb-4">
                <?php foreach ($stats as $k => $v): ?>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="kpi-tile <?= $k ?>">
                            <div class="kpi-icon">📊</div>
                            <div class="kpi-text">
                                <span><?= ucfirst(str_replace('_', ' ', $k)) ?></span>
                                <strong><?= $v ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>

            <!-- FILTER PANEL -->
            <div class="filter-panel mb-4">
                <form method="get" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label>Search</label>
                        <input name="search" class="form-control" value="<?= esc($_GET['search'] ?? '') ?>"
                            placeholder="Name / Email / Mobile">
                    </div>

                    <div class="col-md-2">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <?php foreach (['submitted', 'under_review', 'shortlisted', 'selected', 'rejected'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($_GET['status'] ?? '') === $s ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_', ' ', $s)) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Skill</label>
                        <select name="skill" class="form-select">
                            <option value="">Any</option>
                            <?php foreach (['PHP', 'JS', 'MySQL'] as $sk): ?>
                                <option <?= ($_GET['skill'] ?? '') === $sk ? 'selected' : '' ?>><?= $sk ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>From</label>
                        <input type="date" name="from" class="form-control" value="<?= $_GET['from'] ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label>To</label>
                        <input type="date" name="to" class="form-control" value="<?= $_GET['to'] ?? '' ?>">
                    </div>

                    <div class="col-md-1">
                        <button class="btn btn-primary w-100">Apply</button>
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="table-card">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Resume</th>
                            <th>Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($apps as $app): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($app->full_name) ?></strong><br>
                                    <small><?= esc($app->email) ?></small>
                                </td>

                                <td><?= esc($app->mobile) ?></td>

                                <td>
                                    <select class="form-select form-select-sm status-select" data-id="<?= $app->id ?>">
                                        <?php foreach (['submitted', 'under_review', 'shortlisted', 'selected', 'rejected'] as $s): ?>
                                            <option value="<?= $s ?>" <?= $app->application_status === $s ? 'selected' : '' ?>>
                                                <?= ucfirst(str_replace('_', ' ', $s)) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </td>

                                <td>
                                    <a target="_blank" href="<?= base_url('admin/resume/' . basename($app->resume_path)) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>

                                <td>
                                    <button class="btn btn-sm btn-outline-info view-btn" data-id="<?= $app->id ?>">
                                        Inspect
                                    </button>
                                </td>

                                <td><?= date('d M Y', strtotime($app->submitted_at)) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- MODALS + NOTIFY (unchanged) -->
    <?= view('admin/_modals') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const BASE_URL = "<?= base_url() ?>";
        const CSRF_NAME = "<?= csrf_token() ?>";
        const CSRF_HASH = "<?= csrf_hash() ?>";
    </script>

    <script src="<?= base_url('public/assets/js/dashboard.js') ?>"></script>

</body>

</html>