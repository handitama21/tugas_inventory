<?php

$title = "Edit Barang";
$page_css = "inventory.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$barang = $barang ?? [];
$kategori = $kategori ?? [];
$gudang = $gudang ?? [];
$supplier = $supplier ?? [];

?>

<main>

    <div class="page-header">

        <div>
            <h1>Edit Barang</h1>
            <p>Ubah data barang yang tersedia.</p>
        </div>

    </div>

    <div class="form-card">

        <form
            action="index.php?page=inventory&action=update"
            method="POST"
        >

            <input
                type="hidden"
                name="id_barang"
                value="<?= (int) ($barang['id_barang'] ?? 0) ?>"
            >

            <div class="form-group">

                <label for="kode_barang">
                    Kode Barang
                </label>

                <input
                    type="text"
                    id="kode_barang"
                    value="<?= htmlspecialchars($barang['kode_barang'] ?? '') ?>"
                    readonly
                >

            </div>

            <div class="form-group">

                <label for="nama_barang">
                    Nama Barang
                </label>

                <input
                    type="text"
                    id="nama_barang"
                    name="nama_barang"
                    value="<?= htmlspecialchars($barang['nama_barang'] ?? '') ?>"
                    placeholder="Masukkan nama barang"
                    required
                >

            </div>

            <div class="form-group">

                <label for="id_kategori">
                    Jenis Barang
                </label>

                <select
                    id="id_kategori"
                    name="id_kategori"
                    required
                >

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <?php foreach ($kategori as $item): ?>

                        <option
                            value="<?= (int) $item['id_kategori'] ?>"
                            <?= (int) ($barang['id_kategori'] ?? 0) === (int) $item['id_kategori'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($item['nama_kategori']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label for="kuantitas_stok">
                    Kuantitas Stok
                </label>

                <input
                    type="number"
                    id="kuantitas_stok"
                    name="kuantitas_stok"
                    min="0"
                    value="<?= (int) ($barang['stok'] ?? 0) ?>"
                    placeholder="Masukkan jumlah stok"
                    required
                >

            </div>

            <div class="form-group">

                <label for="lokasi_gudang">
                    Lokasi Gudang
                </label>

                <select
                    id="lokasi_gudang"
                    name="lokasi_gudang"
                    required
                >

                    <option value="">
                        -- Pilih Gudang --
                    </option>

                    <?php foreach ($gudang as $item): ?>

                        <option
                            value="<?= (int) $item['id_gudang'] ?>"
                            <?= (int) ($barang['id_gudang'] ?? 0) === (int) $item['id_gudang'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($item['nama_gudang']) ?>
                            -
                            <?= htmlspecialchars($item['lokasi']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label for="serial_number">
                    Serial Number
                </label>

                <input
                    type="text"
                    id="serial_number"
                    name="serial_number"
                    value="<?= htmlspecialchars($barang['serial_number'] ?? '') ?>"
                    placeholder="Masukkan serial number jika ada"
                >

            </div>

            <div class="form-group">

                <label for="id_supplier">
                    Supplier
                </label>

                <select
                    id="id_supplier"
                    name="id_supplier"
                    required
                >

                    <option value="">
                        -- Pilih Supplier --
                    </option>

                    <?php foreach ($supplier as $item): ?>

                        <option
                            value="<?= (int) $item['id_supplier'] ?>"
                            <?= (int) ($barang['id_supplier'] ?? 0) === (int) $item['id_supplier'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($item['nama_supplier']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">

                <label for="harga">
                    Harga
                </label>

                <input
                    type="number"
                    id="harga"
                    name="harga"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($barang['harga'] ?? 0) ?>"
                    placeholder="Masukkan harga barang"
                    required
                >

            </div>

            <div class="form-actions">

                <a
                    href="index.php?page=inventory"
                    class="btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Update Barang
                </button>

            </div>

        </form>

    </div>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>