<?php

$title = "Login";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title; ?></title>

    <link rel="stylesheet" href="public/css/login.css">
</head>

<body>

<div class="login-page">

    <div class="login-card">

        <div class="login-logo">
            📦
        </div>

        <h1>Inventory Management</h1>

        <p class="login-subtitle">
            Silakan masuk ke akun Anda
        </p>

        <?php if (!empty($error)): ?>

            <div class="login-error">
                <?= htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>

        <form method="POST" action="index.php?page=login">

            <div class="form-group">

                <label for="nip">
                    NIP
                </label>

                <input
                    type="text"
                    id="nip"
                    name="nip"
                    placeholder="Masukkan NIP"
                    value="<?= htmlspecialchars($_POST['nip'] ?? ''); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >

            </div>

            <button type="submit" class="btn-login">
                Masuk
            </button>

        </form>

        <p class="login-footer">
            Inventory Management System
        </p>

    </div>

</div>

</body>
</html>