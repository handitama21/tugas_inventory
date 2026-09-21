<?php

$title = "Supplier";
$page_css = "supplier.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$supplier = $supplier ?? [];

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main>

    <div class="page-header">

        <div>
            <h1>Supplier</h1>
            <p>Kelola data supplier barang.</p>
        </div>

        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

            <a
                href="index.php?page=supplier&action=tambah"
                class="btn-primary"
            >
                + Tambah Supplier
            </a>

        <?php endif; ?>

    </div>

    <div class="content-card">

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Kontak</th>
                        <th>Email</th>
                        <th>Jumlah Barang</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($supplier)): ?>

                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Belum ada data supplier.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($supplier as $index => $item): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_supplier']) ?>
                                </td>

                                <td>
                                    <?= !empty($item['kontak'])
                                        ? htmlspecialchars($item['kontak'])
                                        : '-'
                                    ?>
                                </td>

                                <td>
                                    <?= !empty($item['email'])
                                        ? htmlspecialchars($item['email'])
                                        : '-'
                                    ?>
                                </td>

                                <td>
                                    <?= (int) $item['jumlah_barang'] ?>
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

                                            <a
                                                href="index.php?page=supplier&action=edit&id=<?= (int) $item['id_supplier'] ?>"
                                                class="btn-edit"
                                            >
                                                Edit
                                            </a>

                                            <?php if ((int) $item['jumlah_barang'] > 0): ?>

                                                <button
                                                    type="button"
                                                    class="btn-danger"
                                                    onclick="supplierTidakBisaDihapus('<?= htmlspecialchars($item['nama_supplier'], ENT_QUOTES) ?>', <?= (int) $item['jumlah_barang'] ?>)"
                                                >
                                                    Hapus
                                                </button>

                                            <?php else: ?>

                                                <form
                                                    action="index.php?page=supplier&action=delete"
                                                    method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirmHapusSupplier(event, this, '<?= htmlspecialchars($item['nama_supplier'], ENT_QUOTES) ?>')"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id_supplier"
                                                        value="<?= (int) $item['id_supplier'] ?>"
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

function supplierTidakBisaDihapus(nama, jumlahBarang) {

    Swal.fire({
        title: "Supplier tidak dapat dihapus",
        html:
            'Supplier <strong>"' + nama + '"</strong> masih digunakan oleh ' +
            '<strong>' + jumlahBarang + ' barang</strong>.',
        icon: "warning",
        confirmButtonText: "Mengerti"
    });

}

function confirmHapusSupplier(event, form, nama) {

    event.preventDefault();

    Swal.fire({
        title: "Hapus supplier?",
        html:
            'Supplier <strong>"' + nama + '"</strong> akan dihapus secara permanen.',
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