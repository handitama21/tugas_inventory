<?php

$title = "Gudang";
$page_css = "storage.css";

require_once dirname(__DIR__) . "/layouts/header.php";
require_once dirname(__DIR__) . "/layouts/sidebar.php";

$gudang = $gudang ?? [];

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main>

    <div class="page-header">

        <div>
            <h1>Gudang</h1>
            <p>Kelola data gudang penyimpanan barang.</p>
        </div>

        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

            <a
                href="index.php?page=storage&action=tambah"
                class="btn-primary"
            >
                + Tambah Gudang
            </a>

        <?php endif; ?>

    </div>

    <div class="content-card">

        <div class="table-container">

            <table>

                <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama Gudang</th>
                        <th>Lokasi</th>
                        <th>Jumlah Barang</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($gudang)): ?>

                        <tr>
                            <td colspan="6" style="text-align: center;">
                                Belum ada data gudang.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($gudang as $index => $item): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['nama_gudang']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['lokasi']) ?>
                                </td>

                                <td>
                                    <?= (int) $item['jumlah_barang'] ?>
                                </td>

                                <td>
                                    <?= !empty($item['deskripsi'])
                                        ? htmlspecialchars($item['deskripsi'])
                                        : '-'
                                    ?>
                                </td>

                                <td>

                                    <div class="action-buttons">

                                        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

                                            <a
                                                href="index.php?page=storage&action=edit&id=<?= (int) $item['id_gudang'] ?>"
                                                class="btn-edit"
                                            >
                                                Edit
                                            </a>

                                            <?php if ((int) $item['jumlah_barang'] > 0): ?>

                                                <button
                                                    type="button"
                                                    class="btn-danger"
                                                    onclick="gudangTidakBisaDihapus('<?= htmlspecialchars($item['nama_gudang'], ENT_QUOTES) ?>', <?= (int) $item['jumlah_barang'] ?>)"
                                                >
                                                    Hapus
                                                </button>

                                            <?php else: ?>

                                                <form
                                                    action="index.php?page=storage&action=delete"
                                                    method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirmHapusGudang(event, this, '<?= htmlspecialchars($item['nama_gudang'], ENT_QUOTES) ?>')"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="id_gudang"
                                                        value="<?= (int) $item['id_gudang'] ?>"
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

function gudangTidakBisaDihapus(nama, jumlahBarang) {

    Swal.fire({
        title: "Gudang tidak dapat dihapus",
        html:
            'Gudang <strong>"' + nama + '"</strong> masih digunakan oleh ' +
            '<strong>' + jumlahBarang + ' barang</strong>.',
        icon: "warning",
        confirmButtonText: "Mengerti"
    });

}

function confirmHapusGudang(event, form, nama) {

    event.preventDefault();

    Swal.fire({
        title: "Hapus gudang?",
        html:
            'Gudang <strong>"' + nama + '"</strong> akan dihapus secara permanen.',
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