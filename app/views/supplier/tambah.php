<?php

$title = "Tambah Supplier";
$page_css = "supplier.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

?>

<main>

    <div class="page-header">

        <div>
            <h1>Tambah Supplier</h1>
            <p>Tambahkan supplier baru ke dalam sistem.</p>
        </div>

    </div>

    <div class="form-card">

        <form action="index.php?page=supplier&action=simpan" method="POST">

            <div class="form-group">

                <label for="nama_supplier">
                    Nama Supplier
                </label>

                <input
                    type="text"
                    id="nama_supplier"
                    name="nama_supplier"
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
                ></textarea>

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
                    Simpan Supplier
                </button>

            </div>

        </form>

    </div>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>