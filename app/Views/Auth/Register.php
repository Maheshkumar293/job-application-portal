<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Auth CSS -->
    <link href="<?= base_url('public/assets/css/auth.css') ?>" rel="stylesheet">

</head>

<body>

    <div class="auth-wrapper">

        <div class="auth-card">

            <h3 class="text-center mb-4 fw-bold">
                <i class="bi bi-person-plus-fill text-primary"></i> Create Account
            </h3>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger small">
                    <?= implode('<br>', session()->getFlashdata('errors')) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('register') ?>" novalidate id="registerForm">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" required minlength="3">
                        <div class="invalid-feedback">Name must be at least 3 characters</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" required>
                        <div class="invalid-feedback">Enter a valid email</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required minlength="6">
                        <div class="invalid-feedback">Minimum 6 characters</div>
                    </div>
                </div>

                <button class="btn btn-primary w-100 py-2">
                    <i class="bi bi-arrow-right-circle"></i> Register
                </button>
            </form>

            <p class="text-center mt-4 mb-0">
                Already have an account?
                <a href="<?= base_url('login') ?>" class="fw-semibold">Login</a>
            </p>

        </div>

    </div>

    <script>
        (() => {
            const form = document.getElementById('registerForm');
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        })();
    </script>

</body>


</html>