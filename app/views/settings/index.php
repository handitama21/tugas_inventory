<?php

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php?page=dashboard");
    exit;
}

$namaAdmin = $_SESSION['nama'] ?? 'Admin';
?>

<link rel="stylesheet" href="public/css/settings.css">

<div class="settings-page">

    <div class="settings-header">

        <div class="settings-header-left">

            <a href="index.php?page=dashboard" class="btn-back">
                ← Kembali
            </a>

            <div>
                <h1>Pengaturan</h1>
                <p>Kelola pengaturan dan data sistem.</p>
            </div>

        </div>

        <div class="admin-badge">
            <span>Admin</span>
            <strong><?= htmlspecialchars($namaAdmin) ?></strong>
        </div>

    </div>

    <div class="settings-card">

        <div class="settings-card-header">
            <h2>Pengaturan Admin</h2>
            <p>Kelola akun, pengguna, kategori, gudang, dan notifikasi.</p>
        </div>

        <div class="settings-grid">

            <a href="index.php?page=settings&action=profile" class="setting-item">

                <div class="setting-icon profile-icon">
                    👤
                </div>

                <div class="setting-content">
                    <h3>Profil Saya</h3>
                    <p>Kelola informasi profil dan password akun.</p>
                </div>

                <div class="setting-arrow">
                    →
                </div>

            </a>

            <a href="index.php?page=settings&action=users" class="setting-item">

                <div class="setting-icon users-icon">
                    👥
                </div>

                <div class="setting-content">
                    <h3>Kelola Pengguna</h3>
                    <p>Tambah, edit, dan hapus pengguna sistem.</p>
                </div>

                <div class="setting-arrow">
                    →
                </div>

            </a>

            <a href="index.php?page=settings&action=kategori" class="setting-item">

                <div class="setting-icon category-icon">
                    🏷️
                </div>

                <div class="setting-content">
                    <h3>Kelola Kategori</h3>
                    <p>Kelola kategori barang yang tersedia.</p>
                </div>

                <div class="setting-arrow">
                    →
                </div>

            </a>

            <a href="index.php?page=settings&action=gudang" class="setting-item">

                <div class="setting-icon warehouse-icon">
                    🏢
                </div>

                <div class="setting-content">
                    <h3>Kelola Gudang</h3>
                    <p>Tambah dan kelola data gudang.</p>
                </div>

                <div class="setting-arrow">
                    →
                </div>

            </a>

            <a href="index.php?page=settings&action=notifikasi" class="setting-item">

                <div class="setting-icon notification-icon">
                    🔔
                </div>

                <div class="setting-content">
                    <h3>Notifikasi</h3>
                    <p>Lihat dan kelola notifikasi akun.</p>
                </div>

                <div class="setting-arrow">
                    →
                </div>

            </a>

        </div>

    </div>

</div>