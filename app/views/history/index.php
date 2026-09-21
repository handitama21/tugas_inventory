<?php

$title = "History Transaksi";
$page_css = "history.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$history = $history ?? ($transaksi ?? []);

?>

<main>

    <div class="page-header">

        <div>

            <h1>History Transaksi</h1>

            <p>
                Riwayat transaksi yang sudah dilakukan.
            </p>

        </div>

        <a
            href="index.php?page=transaksi"
            class="btn-primary"
        >
            + Transaksi Baru
        </a>

    </div>


    <div class="content-card">

        <div class="content-header">

            <div>

                <h2>Daftar Transaksi</h2>

                <p>
                    <?= count($history) ?> transaksi
                </p>

            </div>

            <div class="search-box">

                <input
                    type="text"
                    id="searchHistory"
                    placeholder="Cari kode atau pembeli..."
                >

            </div>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Pembeli</th>
                        <th>Total</th>
                        <th>Uang Bayar</th>
                        <th>Kembalian</th>
                        <th>Kasir</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody id="historyTableBody">

                    <?php if (empty($history)): ?>

                        <tr>

                            <td
                                colspan="9"
                                style="text-align: center;"
                            >
                                Belum ada transaksi.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($history as $index => $item): ?>

                            <tr class="history-row">

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars(
                                            $item['kode_transaksi']
                                        ) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= !empty($item['nama_pembeli'])
                                        ? htmlspecialchars($item['nama_pembeli'])
                                        : 'Umum' ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) $item['grand_total'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) $item['uang_bayar'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) $item['kembalian'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $item['nama_user'] ?? '-'
                                    ) ?>
                                </td>

                                <td>

                                    <strong>
                                        <?= date(
                                            'd/m/Y H:i',
                                            strtotime($item['created_at'])
                                        ) ?>
                                    </strong>

                                    <span class="time-ago">
                                        <?= date(
                                            'd/m/Y',
                                            strtotime($item['created_at'])
                                        ) === date('d/m/Y')
                                            ? 'Hari ini'
                                            : '' ?>
                                    </span>

                                </td>

                                <td>

                                    <a
                                        href="index.php?page=history&action=detail&id=<?= (int) $item['id_transaksi'] ?>"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</main>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const searchInput =
        document.getElementById("searchHistory");

    const rows =
        document.querySelectorAll(".history-row");

    if (!searchInput) {
        return;
    }

    searchInput.addEventListener("input", function () {

        const keyword =
            this.value.toLowerCase().trim();

        rows.forEach(function (row) {

            const text =
                row.textContent.toLowerCase();

            if (text.includes(keyword)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    });

});

</script>


<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>