<?php

$title = "Dashboard Inventory";
$page_css = "dashboard.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$totalBarang = $totalBarang ?? 0;
$stokMenipis = $stokMenipis ?? 0;
$totalSupplier = $totalSupplier ?? 0;
$totalGudang = $totalGudang ?? 0;
$barangTerbaru = $barangTerbaru ?? [];
$jumlahStokMenipisDanHabis = $jumlahStokMenipisDanHabis ?? 0;

?>

<main>

    <div class="page-header">

        <div>
            <h1>Dashboard</h1>
            <p>Selamat datang di Inventory Management System.</p>
        </div>

    </div>

    <div class="dashboard-cards">

        <div class="card">

            <div class="card-icon">
                📦
            </div>

            <div class="card-content">
                <span>Total Barang</span>

                <h2>
                    <?= number_format($totalBarang, 0, ',', '.') ?>
                </h2>
            </div>

        </div>

        <div class="card">

            <div class="card-icon">
                ⚠️
            </div>

            <div class="card-content">
                <span>Stok Menipis</span>

                <h2>
                    <?= number_format($stokMenipis, 0, ',', '.') ?>
                </h2>
            </div>

        </div>

        <div class="card">

            <div class="card-icon">
                🚚
            </div>

            <div class="card-content">
                <span>Supplier</span>

                <h2>
                    <?= number_format($totalSupplier, 0, ',', '.') ?>
                </h2>
            </div>

        </div>

        <div class="card">

            <div class="card-icon">
                🏢
            </div>

            <div class="card-content">
                <span>Gudang</span>

                <h2>
                    <?= number_format($totalGudang, 0, ',', '.') ?>
                </h2>
            </div>

        </div>

    </div>

    <div class="content-card">

        <div class="content-header">

            <div>
                <h2>Inventory Terbaru</h2>
                <p>Daftar barang yang baru ditambahkan.</p>
            </div>

            <a
                href="index.php?page=inventory"
                class="btn-primary"
            >
                Lihat Semua
            </a>

        </div>

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Lokasi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($barangTerbaru)): ?>

                        <tr>

                            <td
                                colspan="6"
                                style="text-align: center;"
                            >
                                Belum ada data barang.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($barangTerbaru as $index => $barang): ?>

                            <?php

                            $stok = (int) $barang['stok'];

                            if ($stok <= 0) {

                                $statusText = "Habis";
                                $statusClass = "habis";

                            } elseif ($stok <= 10) {

                                $statusText = "Menipis";
                                $statusClass = "menipis";

                            } else {

                                $statusText = "Aman";
                                $statusClass = "aman";

                            }

                            ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['nama_barang']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['nama_kategori']
                                    ) ?>
                                </td>

                                <td>
                                    <?= $stok ?>
                                </td>

                                <td>
                                    <span class="status <?= $statusClass ?>">
                                        <?= $statusText ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $barang['nama_gudang']
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <?php if ($jumlahStokMenipisDanHabis > 0): ?>

        <div class="alert-box">

            <div class="alert-icon">
                ⚠️
            </div>

            <div>

                <strong>Perhatian Stok</strong>

                <p>
                    Terdapat
                    <?= number_format(
                        $jumlahStokMenipisDanHabis,
                        0,
                        ',',
                        '.'
                    ) ?>
                    barang dengan stok menipis atau habis.
                    Silakan lakukan pengecekan inventory.
                </p>

            </div>

        </div>

    <?php else: ?>

        <div class="alert-box">

            <div class="alert-icon">
                ✅
            </div>

            <div>

                <strong>Stok Aman</strong>

                <p>
                    Semua barang memiliki stok yang masih tersedia.
                </p>

            </div>

        </div>

    <?php endif; ?>

</main>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>