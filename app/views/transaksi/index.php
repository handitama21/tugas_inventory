<?php

$title = "Kasir / Transaksi";

$barang = $barang ?? [];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($title) ?></title>

    <link
        rel="stylesheet"
        href="public/css/transaksi.css"
    >

</head>

<body>

<div class="transaksi-page">

    <div class="transaksi-header">

        <div class="header-left">

            <a
                href="index.php?page=dashboard"
                class="btn-back"
            >
                ← Kembali
            </a>

            <div>

                <h1>Kasir / Transaksi</h1>

                <p>
                    Buat transaksi baru dan kelola pembayaran pelanggan.
                </p>

            </div>

        </div>

        <div class="transaction-info">

            <span id="tanggalTransaksi"></span>

        </div>

    </div>


    <div class="kasir-container">

        <div class="produk-section">

            <div class="section-header">

                <div>

                    <h2>Daftar Barang</h2>

                    <p>
                        Pilih barang yang ingin dimasukkan ke transaksi.
                    </p>

                </div>

                <div class="search-box">

                    <input
                        type="text"
                        id="searchBarang"
                        placeholder="Cari barang..."
                    >

                </div>

            </div>


            <div
                class="produk-grid"
                id="produkGrid"
            >

                <?php if (empty($barang)): ?>

                    <div class="keranjang-kosong">

                        <div class="empty-icon">
                            📦
                        </div>

                        <h3>
                            Tidak ada barang
                        </h3>

                        <p>
                            Belum ada barang yang memiliki stok.
                        </p>

                    </div>

                <?php else: ?>

                    <?php foreach ($barang as $item): ?>

                        <div
                            class="produk-card"
                            data-id="<?= (int) $item['id_barang'] ?>"
                            data-nama="<?= htmlspecialchars(
                                $item['nama_barang'],
                                ENT_QUOTES
                            ) ?>"
                            data-harga="<?= (float) $item['harga'] ?>"
                            data-stok="<?= (int) $item['stok'] ?>"
                        >

                            <div class="produk-icon">
                                📦
                            </div>

                            <div class="produk-info">

                                <h3>
                                    <?= htmlspecialchars(
                                        $item['nama_barang']
                                    ) ?>
                                </h3>

                                <p class="kode-barang">

                                    <?= htmlspecialchars(
                                        $item['kode_barang']
                                    ) ?>

                                </p>

                                <strong>

                                    Rp<?= number_format(
                                        (float) $item['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </strong>

                                <span class="stok">

                                    Stok:
                                    <?= (int) $item['stok'] ?>

                                </span>

                            </div>

                            <button
                                type="button"
                                class="btn-tambah"
                                onclick="tambahKeKeranjang(this)"
                            >
                                + Tambah
                            </button>

                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </div>


        <div class="keranjang-section">

            <div class="keranjang-card">

                <div class="keranjang-header">

                    <div>

                        <h2>
                            Keranjang
                        </h2>

                        <p>

                            <span id="jumlahItem">
                                0
                            </span>

                            item

                        </p>

                    </div>

                    <button
                        type="button"
                        class="btn-reset"
                        onclick="resetKeranjang()"
                    >
                        Kosongkan
                    </button>

                </div>


                <div
                    class="keranjang-list"
                    id="keranjangList"
                >

                    <div
                        class="keranjang-kosong"
                        id="keranjangKosong"
                    >

                        <div class="empty-icon">
                            🛒
                        </div>

                        <h3>
                            Keranjang masih kosong
                        </h3>

                        <p>
                            Tambahkan barang dari daftar barang.
                        </p>

                    </div>

                </div>


                <div class="pembeli-form">

                    <label for="namaPembeli">
                        Nama Pembeli
                    </label>

                    <input
                        type="text"
                        id="namaPembeli"
                        placeholder="Masukkan nama pembeli"
                    >

                </div>


                <div class="perhitungan">

                    <div class="perhitungan-row">

                        <span>
                            Subtotal
                        </span>

                        <strong id="subtotal">
                            Rp0
                        </strong>

                    </div>


                    <div class="perhitungan-row">

                        <span>
                            Diskon
                        </span>

                        <strong
                            class="diskon-text"
                            id="diskon"
                        >
                            -Rp0
                        </strong>

                    </div>


                    <div class="perhitungan-row total-row">

                        <span>
                            Total
                        </span>

                        <strong id="grandTotal">
                            Rp0
                        </strong>

                    </div>

                </div>


                <div class="pembayaran">

                    <label for="uangBayar">
                        Uang Bayar
                    </label>

                    <div class="payment-input">

                        <span>
                            Rp
                        </span>

                        <input
                            type="number"
                            id="uangBayar"
                            placeholder="0"
                            min="0"
                            oninput="hitungPembayaran()"
                        >

                    </div>


                    <div
                        class="hasil-pembayaran"
                        id="hasilPembayaran"
                    >

                        <div class="hasil-row">

                            <span>
                                Kembalian
                            </span>

                            <strong id="kembalian">
                                Rp0
                            </strong>

                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-transaksi"
                    id="btnTransaksi"
                    onclick="selesaikanTransaksi()"
                >
                    Selesaikan Transaksi
                </button>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="public/js/transaksi.js"></script>

</body>

</html>