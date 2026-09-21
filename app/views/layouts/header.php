<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $title ?? 'Inventory Management'; ?>
    </title>

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="public/css/style.css">

    <!-- CSS KHUSUS HALAMAN -->
    <?php if (isset($page_css)): ?>

        <link rel="stylesheet" href="public/css/<?= $page_css; ?>">

    <?php endif; ?>

</head>

<body>

<div class="app">