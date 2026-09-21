<?php

$title = "Detail Transaksi";
$page_css = "history.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$transaksi = $transaksi ?? [];
$detail = $detail ?? [];

?>

<main>

    <div class="page-header">

        <div>

            <h1>Detail Transaksi</h1>

            <p>
                Informasi lengkap transaksi.
            </p>

        </div>

        <a
            href="index.php?page=history"
            class="btn-cancel"
        >
            ← Kembali
        </a>

    </div>


    <div class="content-card">

        <div class="content-header">

            <div>

                <h2>
                    <?= htmlspecialchars(
                        $transaksi['kode_transaksi'] ?? '-'
                    ) ?>
                </h2>

                <p>
                    Detail transaksi yang dipilih.
                </p>

            </div>

        </div>


        <div class="form-card">

            <div class="form-group">

                <label>
                    Kode Transaksi
                </label>

                <input
                    type="text"
                    value="<?= htmlspecialchars(
                        $transaksi['kode_transaksi'] ?? '-'
                    ) ?>"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>
                    Nama Pembeli
                </label>

                <input
                    type="text"
                    value="<?= htmlspecialchars(
                        $transaksi['nama_pembeli'] ?? '-'
                    ) ?>"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>
                    Kasir
                </label>

                <input
                    type="text"
                    value="<?= htmlspecialchars(
                        $transaksi['nama_user'] ?? '-'
                    ) ?>"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>
                    Tanggal Transaksi
                </label>

                <input
                    type="text"
                    value="<?= !empty($transaksi['created_at'])
                        ? date(
                            'd-m-Y H:i',
                            strtotime($transaksi['created_at'])
                        )
                        : '-' ?>"
                    readonly
                >

            </div>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Kode Barang</th>

                        <th>Nama Barang</th>

                        <th>Harga</th>

                        <th>Jumlah</th>

                        <th>Diskon</th>

                        <th>Subtotal</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (empty($detail)): ?>

                        <tr>

                            <td
                                colspan="7"
                                style="text-align:center;"
                            >
                                Tidak ada detail barang.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($detail as $index => $item): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $item['kode_barang'] ?? '-'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $item['nama_barang'] ?? '-'
                                    ) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) (
                                            $item['harga_satuan'] ?? 0
                                        ),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    <?= (int) (
                                        $item['jumlah'] ?? 0
                                    ) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) (
                                            $item['diskon_detail'] ?? 0
                                        ),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) (
                                            $item['subtotal'] ?? 0
                                        ),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <div class="transaction-summary">

            <div class="summary-row">

                <span>
                    Subtotal
                </span>

                <strong>
                    Rp <?= number_format(
                        (float) (
                            $transaksi['subtotal'] ?? 0
                        ),
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Diskon
                </span>

                <strong>
                    Rp <?= number_format(
                        (float) (
                            $transaksi['diskon'] ?? 0
                        ),
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

            </div>


            <div class="summary-row total">

                <span>
                    Grand Total
                </span>

                <strong>
                    Rp <?= number_format(
                        (float) (
                            $transaksi['grand_total'] ?? 0
                        ),
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Uang Bayar
                </span>

                <strong>
                    Rp <?= number_format(
                        (float) (
                            $transaksi['uang_bayar'] ?? 0
                        ),
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

            </div>


            <div class="summary-row">

                <span>
                    Kembalian
                </span>

                <strong>
                    Rp <?= number_format(
                        (float) (
                            $transaksi['kembalian'] ?? 0
                        ),
                        0,
                        ',',
                        '.'
                    ) ?>
                </strong>

            </div>

        </div>


        <div class="form-actions">

            <a
                href="index.php?page=history"
                class="btn-cancel"
            >
                Kembali
            </a>

        </div>

    </div>

</main>


<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>