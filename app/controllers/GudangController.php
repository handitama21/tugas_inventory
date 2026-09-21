<?php

class GudangController
{
    private $connect;
    private $gudangModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->gudangModel = new GudangModel($connect);
    }

    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    private function checkAdmin()
    {
        $this->checkLogin();

        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public function index()
    {
        $this->checkLogin();

        $gudang = $this->gudangModel->getAll();

        $message = $_SESSION['gudang_message'] ?? '';
        $error = $_SESSION['gudang_error'] ?? '';

        unset($_SESSION['gudang_message']);
        unset($_SESSION['gudang_error']);

        require __DIR__ . '/../views/storage/index.php';
    }

    public function tambah()
    {
        $this->checkAdmin();

        require __DIR__ . '/../views/storage/tambah.php';
    }

    public function simpan()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=storage");
            exit;
        }

        $namaGudang = trim($_POST['nama_gudang'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if ($namaGudang === '' || $lokasi === '') {
            $_SESSION['gudang_error'] =
                'Nama gudang dan lokasi wajib diisi.';

            header("Location: index.php?page=storage&action=tambah");
            exit;
        }

        try {

            $this->gudangModel->create([
                'nama_gudang' => $namaGudang,
                'lokasi' => $lokasi,
                'deskripsi' => $deskripsi !== '' ? $deskripsi : null
            ]);

            $_SESSION['gudang_message'] =
                'Gudang berhasil ditambahkan.';

        } catch (PDOException $e) {

            $_SESSION['gudang_error'] =
                'Terjadi kesalahan saat menambahkan gudang.';
        }

        header("Location: index.php?page=storage");
        exit;
    }

    public function edit()
    {
        $this->checkAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['gudang_error'] =
                'ID gudang tidak valid.';

            header("Location: index.php?page=storage");
            exit;
        }

        $gudang = $this->gudangModel->getById($id);

        if (!$gudang) {
            $_SESSION['gudang_error'] =
                'Gudang tidak ditemukan.';

            header("Location: index.php?page=storage");
            exit;
        }

        require __DIR__ . '/../views/storage/edit.php';
    }

    public function update()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=storage");
            exit;
        }

        $id = (int) ($_POST['id_gudang'] ?? 0);
        $namaGudang = trim($_POST['nama_gudang'] ?? '');
        $lokasi = trim($_POST['lokasi'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if ($id <= 0 || $namaGudang === '' || $lokasi === '') {
            $_SESSION['gudang_error'] =
                'Data gudang tidak valid.';

            header("Location: index.php?page=storage");
            exit;
        }

        $gudangLama = $this->gudangModel->getById($id);

        if (!$gudangLama) {
            $_SESSION['gudang_error'] =
                'Gudang tidak ditemukan.';

            header("Location: index.php?page=storage");
            exit;
        }

        try {

            $this->gudangModel->update($id, [
                'nama_gudang' => $namaGudang,
                'lokasi' => $lokasi,
                'deskripsi' => $deskripsi !== '' ? $deskripsi : null
            ]);

            $_SESSION['gudang_message'] =
                'Data gudang berhasil diperbarui.';

        } catch (PDOException $e) {

            $_SESSION['gudang_error'] =
                'Terjadi kesalahan saat memperbarui gudang.';
        }

        header("Location: index.php?page=storage");
        exit;
    }

    public function delete()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=storage");
            exit;
        }

        $id = (int) ($_POST['id_gudang'] ?? 0);

        if ($id <= 0) {
            $_SESSION['gudang_error'] =
                'Gudang yang akan dihapus tidak valid.';

            header("Location: index.php?page=storage");
            exit;
        }

        $gudang = $this->gudangModel->getById($id);

        if (!$gudang) {
            $_SESSION['gudang_error'] =
                'Gudang tidak ditemukan.';

            header("Location: index.php?page=storage");
            exit;
        }

        try {

            $jumlahBarang = $this->gudangModel->getJumlahBarang($id);

            if ($jumlahBarang > 0) {

                $_SESSION['gudang_error'] =
                    'Gudang "' . $gudang['nama_gudang'] .
                    '" tidak dapat dihapus karena masih digunakan oleh ' .
                    $jumlahBarang . ' barang.';

                header("Location: index.php?page=storage");
                exit;
            }

            $this->gudangModel->delete($id);

            $_SESSION['gudang_message'] =
                'Gudang "' . $gudang['nama_gudang'] .
                '" berhasil dihapus.';

        } catch (PDOException $e) {

            $_SESSION['gudang_error'] =
                'Gudang tidak dapat dihapus karena masih digunakan oleh data lain.';
        }

        header("Location: index.php?page=storage");
        exit;
    }
}