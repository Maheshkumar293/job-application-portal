<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Candidate Registration</h2>

<?php if (session()->getFlashdata('error')): ?>
    <p style="color:red"><?= esc(session()->getFlashdata('error')) ?></p>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <ul style="color:red">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
            <li><?= esc($err) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="<?= base_url('register') ?>">
    <?= csrf_field() ?>

    <input type="text" name="name" placeholder="Full Name" value="<?= old('name') ?>"><br><br>

    <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>"><br><br>

    <input type="password" name="password" placeholder="Password"><br><br>

    <button type="submit">Register</button>
</form>

<a href="<?= base_url('login') ?>">Already have an account?</a>

</body>
</html>
