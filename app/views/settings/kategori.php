<?php

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php?page=dashboard");
    exit;
}

$kategori = $kategori ?? [];
$message = $message ?? '';
$error = $error ?? '';
?>

<link rel="stylesheet" href="public/css/kategori.css">

<div class="kategori-page">

    <div class="kategori-header">

        <div class="kategori-header-left">

            <a href="index.php?page=settings" class="btn-back">
                ← Kembali
            </a>

            <div>
                <h1>Kelola Kategori</h1>
                <p>Tambah, edit, dan kelola kategori barang.</p>
            </div>

        </div>

        <button
            type="button"
            class="btn-add-kategori"
            onclick="openAddKategoriModal()"
        >
            + Tambah Kategori
        </button>

    </div>

    <?php if ($message !== ''): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="kategori-card">

        <div class="kategori-card-header">

            <div>
                <h2>Daftar Kategori</h2>
                <span><?= count($kategori) ?> kategori</span>
            </div>

            <div class="search-kategori">
                <input
                    type="text"
                    id="searchKategori"
                    placeholder="Cari kategori..."
                >
            </div>

        </div>

        <div class="table-wrapper">

            <table id="kategoriTable">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Barang</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($kategori)): ?>

                        <tr>
                            <td colspan="6" class="empty-kategori">
                                Belum ada kategori.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($kategori as $index => $item): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <strong class="nama-kategori">
                                        <?= htmlspecialchars($item['nama_kategori']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        <?= (int) $item['jumlah_barang'] ?> barang
                                    </strong>
                                </td>

                                <td>
                                    <span class="deskripsi-kategori">
                                        <?= $item['deskripsi']
                                            ? htmlspecialchars($item['deskripsi'])
                                            : '-'
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <?= date(
                                        'd/m/Y',
                                        strtotime($item['created_at'])
                                    ) ?>
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <button
                                            type="button"
                                            class="btn-edit"
                                            onclick='openEditKategoriModal(<?= json_encode($item) ?>)'
                                        >
                                            Edit
                                        </button>

                                        <form
                                            method="POST"
                                            action="index.php?page=kategori&action=delete"
                                            onsubmit="return confirmDeleteKategori(event, '<?= htmlspecialchars($item['nama_kategori'], ENT_QUOTES) ?>')"
                                        >

                                            <input
                                                type="hidden"
                                                name="id_kategori"
                                                value="<?= $item['id_kategori'] ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="btn-delete"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="modal-overlay" id="kategoriModal">

    <div class="kategori-modal">

        <div class="modal-header">

            <div>

                <h2 id="kategoriModalTitle">
                    Tambah Kategori
                </h2>

                <p id="kategoriModalDescription">
                    Tambahkan kategori barang baru.
                </p>

            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeKategoriModal()"
            >
                ×
            </button>

        </div>

        <form
            method="POST"
            action="index.php?page=kategori&action=save"
            id="kategoriForm"
        >

            <input
                type="hidden"
                name="id_kategori"
                id="id_kategori"
                value=""
            >

            <div class="form-group">

                <label for="nama_kategori">
                    Nama Kategori
                </label>

                <input
                    type="text"
                    name="nama_kategori"
                    id="nama_kategori"
                    placeholder="Masukkan nama kategori"
                    required
                >

            </div>

            <div class="form-group">

                <label for="deskripsi">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="4"
                    placeholder="Masukkan deskripsi kategori"
                ></textarea>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeKategoriModal()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn-save"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/kategori.js"></script>