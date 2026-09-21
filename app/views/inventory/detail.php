<?php

$title = "Detail Barang";
$page_css = "inventory.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$barang = $barang ?? [];

?>

<main>

    <div class="page-header">

        <div>
            <h1>Detail Barang</h1>
            <p>Informasi lengkap barang inventory.</p>
        </div>

        <a
            href="index.php?page=inventory"
            class="btn-cancel"
        >
            ← Kembali
        </a>

    </div>

    <div class="form-card">

        <div class="form-group">

            <label>Kode Barang</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['kode_barang'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Nama Barang</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['nama_barang'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Jenis Barang</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['nama_kategori'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Kuantitas Stok</label>

            <input
                type="text"
                value="<?= (int) ($barang['stok'] ?? 0) ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Lokasi Gudang</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['nama_gudang'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Lokasi</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['lokasi'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Serial Number</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['serial_number'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Supplier</label>

            <input
                type="text"
                value="<?= htmlspecialchars($barang['nama_supplier'] ?? '-') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Kondisi</label>

            <input
                type="text"
                value="<?= htmlspecialchars(ucfirst($barang['kondisi'] ?? '-')) ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Status</label>

            <input
                type="text"
                value="<?= htmlspecialchars(ucfirst($barang['status'] ?? '-')) ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Harga</label>

            <input
                type="text"
                value="Rp <?= number_format((float) ($barang['harga'] ?? 0), 0, ',', '.') ?>"
                readonly
            >

        </div>

        <div class="form-group">

            <label>Deskripsi</label>

            <textarea
                rows="4"
                readonly
            ><?= htmlspecialchars($barang['deskripsi'] ?? '-') ?></textarea>

        </div>

        <div class="form-actions">

            <a
                href="index.php?page=inventory"
                class="btn-cancel"
            >
                Kembali
            </a>

            <a
                href="index.php?page=inventory&action=edit&id=<?= (int) ($barang['id_barang'] ?? 0) ?>"
                class="btn-primary"
            >
                Edit Barang
            </a>

        </div>

    </div>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>