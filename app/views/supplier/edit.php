<?php

$title = "Edit Supplier";
$page_css = "supplier.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$supplier = $supplier ?? [];

?>

<main>

    <div class="page-header">

        <div>
            <h1>Edit Supplier</h1>
            <p>Ubah data supplier.</p>
        </div>

    </div>

    <div class="form-card">

        <form action="index.php?page=supplier&action=update" method="POST">

            <input
                type="hidden"
                name="id_supplier"
                value="<?= (int) ($supplier['id_supplier'] ?? 0) ?>"
            >

            <div class="form-group">

                <label for="nama_supplier">
                    Nama Supplier
                </label>

                <input
                    type="text"
                    id="nama_supplier"
                    name="nama_supplier"
                    value="<?= htmlspecialchars($supplier['nama_supplier'] ?? '') ?>"
                    placeholder="Masukkan nama supplier"
                    required
                >

            </div>

            <div class="form-group">

                <label for="kontak">
                    Kontak
                </label>

                <input
                    type="text"
                    id="kontak"
                    name="kontak"
                    value="<?= htmlspecialchars($supplier['kontak'] ?? '') ?>"
                    placeholder="Masukkan nomor kontak"
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($supplier['email'] ?? '') ?>"
                    placeholder="Masukkan email supplier"
                >

            </div>

            <div class="form-group">

                <label for="alamat">
                    Alamat
                </label>

                <textarea
                    id="alamat"
                    name="alamat"
                    rows="5"
                    placeholder="Masukkan alamat supplier"
                ><?= htmlspecialchars($supplier['alamat'] ?? '') ?></textarea>

            </div>

            <div class="form-actions">

                <a
                    href="index.php?page=supplier"
                    class="btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Update Supplier
                </button>

            </div>

        </form>

    </div>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>