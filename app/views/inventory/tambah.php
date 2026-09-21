<?php

$title = "Tambah Barang";
$page_css = "inventory.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$kategori = $kategori ?? [];
$gudang = $gudang ?? [];
$supplier = $supplier ?? [];

?>

<main>

    <div class="page-header">

        <div>
            <h1>Tambah Barang</h1>
            <p>Tambahkan barang baru ke dalam inventory.</p>
        </div>

    </div>

    <div class="form-card">

        <form
            action="index.php?page=inventory&action=simpan"
            method="POST"
        >

            <div class="form-group">

                <label for="nama_barang">
                    Nama Barang
                </label>

                <input
                    type="text"
                    id="nama_barang"
                    name="nama_barang"
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
                        >
                            <?= htmlspecialchars($item['nama_kategori']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <?php if (empty($kategori)): ?>

                    <small>
                        Belum ada data kategori. Tambahkan kategori terlebih dahulu.
                    </small>

                <?php endif; ?>

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
                        >
                            <?= htmlspecialchars($item['nama_gudang']) ?>
                            -
                            <?= htmlspecialchars($item['lokasi']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <?php if (empty($gudang)): ?>

                    <small>
                        Belum ada data gudang. Tambahkan gudang terlebih dahulu.
                    </small>

                <?php endif; ?>

            </div>

            <div class="form-group">

                <label for="serial_number">
                    Serial Number
                </label>

                <input
                    type="text"
                    id="serial_number"
                    name="serial_number"
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
                        >
                            <?= htmlspecialchars($item['nama_supplier']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <?php if (empty($supplier)): ?>

                    <small>
                        Belum ada data supplier. Tambahkan supplier terlebih dahulu.
                    </small>

                <?php endif; ?>

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
                    Simpan Barang
                </button>

            </div>

        </form>

    </div>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>