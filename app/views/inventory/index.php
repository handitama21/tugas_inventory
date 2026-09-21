<?php

$title = "Inventory";
$page_css = "inventory.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$barang = $barang ?? [];

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main>

    <div class="page-header">

        <div>
            <h1>Inventory</h1>
            <p>Kelola data barang yang tersedia.</p>
        </div>

        <a
            href="index.php?page=inventory&action=tambah"
            class="btn-primary"
        >
            + Tambah Barang
        </a>

    </div>

    <div class="content-card">

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Gudang</th>
                        <th>Supplier</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($barang)): ?>

                        <tr>

                            <td
                                colspan="9"
                                style="text-align: center;"
                            >
                                Belum ada data barang.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($barang as $index => $item): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['kode_barang']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_barang']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_kategori']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_gudang']) ?>
                                </td>

                                <td>
                                    <?= !empty($item['nama_supplier'])
                                        ? htmlspecialchars($item['nama_supplier'])
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= (int) $item['stok'] ?>
                                </td>

                                <td>
                                    Rp <?= number_format(
                                        (float) $item['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="index.php?page=inventory&action=detail&id=<?= (int) $item['id_barang'] ?>"
                                            class="btn-detail"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="index.php?page=inventory&action=edit&id=<?= (int) $item['id_barang'] ?>"
                                            class="btn-edit"
                                        >
                                            Edit
                                        </a>

                                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

                                            <?php if ((int) $item['stok'] > 0): ?>

                                                <button
                                                    type="button"
                                                    class="btn-danger"
                                                    onclick="barangTidakBisaDihapus(
                                                        '<?= htmlspecialchars($item['nama_barang'], ENT_QUOTES) ?>',
                                                        <?= (int) $item['stok'] ?>
                                                    )"
                                                >
                                                    Hapus
                                                </button>

                                            <?php else: ?>

                                                <form
                                                    action="index.php?page=inventory&action=delete"
                                                    method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirmHapusBarang(
                                                        event,
                                                        this,
                                                        '<?= htmlspecialchars($item['nama_barang'], ENT_QUOTES) ?>'
                                                    )"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id_barang"
                                                        value="<?= (int) $item['id_barang'] ?>"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="btn-danger"
                                                    >
                                                        Hapus
                                                    </button>

                                                </form>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            <span>-</span>

                                        <?php endif; ?>

                                    </div>

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

function barangTidakBisaDihapus(nama, stok) {

    Swal.fire({
        title: "Barang tidak dapat dihapus",
        html:
            'Barang <strong>"' + nama + '"</strong> masih memiliki ' +
            '<strong>' + stok + ' stok</strong>.',
        icon: "warning",
        confirmButtonText: "Mengerti"
    });

}

function confirmHapusBarang(event, form, nama) {

    event.preventDefault();

    Swal.fire({
        title: "Hapus barang?",
        html:
            'Barang <strong>"' + nama + '"</strong> akan dihapus secara permanen.',
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {
            form.submit();
        }

    });

    return false;

}

<?php if (!empty($message)): ?>

Swal.fire({
    title: "Berhasil",
    text: <?= json_encode($message) ?>,
    icon: "success",
    confirmButtonText: "OK"
});

<?php endif; ?>

<?php if (!empty($error)): ?>

Swal.fire({
    title: "Tidak dapat diproses",
    text: <?= json_encode($error) ?>,
    icon: "error",
    confirmButtonText: "OK"
});

<?php endif; ?>

</script>

<?php

require_once dirname(__DIR__) . "/layouts/footer.php";

?>