<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green"><?= esc(session()->getFlashdata('success')) ?></p>
<?php endif; ?>

<form method="post" action="<?= base_url('login') ?>">
    <?= csrf_field() ?>

    <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>"><br><br>

    <input type="password" name="password" placeholder="Password"><br><br>

    <button type="submit">Login</button>
</form>

<a href="<?= base_url('register') ?>">Create account</a>

</body>
</html>
