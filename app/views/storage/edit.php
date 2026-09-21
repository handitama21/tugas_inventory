<?php

$gudang = $gudang ?? [];

$title = "Edit Gudang";
$page_css = "storage.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

?>

<main>

    <div class="page-header">

        <div>
            <h1>Edit Gudang</h1>
            <p>Ubah data gudang yang tersedia.</p>
        </div>

    </div>

    <?php if (!empty($_SESSION['gudang_error'])): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['gudang_error']) ?>
        </div>

        <?php unset($_SESSION['gudang_error']); ?>

    <?php endif; ?>

    <div class="form-card">

        <form
            action="index.php?page=storage&action=update"
            method="POST"
        >

            <input
                type="hidden"
                name="id_gudang"
                value="<?= (int) ($gudang['id_gudang'] ?? 0) ?>"
            >

            <div class="form-group">

                <label>Nama Gudang</label>

                <input
                    type="text"
                    name="nama_gudang"
                    value="<?= htmlspecialchars($gudang['nama_gudang'] ?? '') ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Lokasi</label>

                <input
                    type="text"
                    name="lokasi"
                    value="<?= htmlspecialchars($gudang['lokasi'] ?? '') ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="5"
                    placeholder="Masukkan deskripsi gudang"
                ><?= htmlspecialchars($gudang['deskripsi'] ?? '') ?></textarea>

            </div>

            <div class="form-actions">

                <a
                    href="index.php?page=storage"
                    class="btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Update Gudang
                </button>

            </div>

        </form>

    </div>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>