<?php

class SupplierController
{
    private $connect;
    private $supplierModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->supplierModel = new SupplierModel($connect);
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
            header("Location: index.php?page=supplier");
            exit;
        }
    }

    public function index()
    {
        $this->checkLogin();

        $supplier = $this->supplierModel->getAll();

        $message = $_SESSION['supplier_message'] ?? '';
        $error = $_SESSION['supplier_error'] ?? '';

        unset($_SESSION['supplier_message']);
        unset($_SESSION['supplier_error']);

        require __DIR__ . '/../views/supplier/index.php';
    }

    public function tambah()
    {
        $this->checkAdmin();

        require __DIR__ . '/../views/supplier/tambah.php';
    }

    public function simpan()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=supplier");
            exit;
        }

        $namaSupplier = trim($_POST['nama_supplier'] ?? '');
        $kontak = trim($_POST['kontak'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');

        if ($namaSupplier === '') {
            $_SESSION['supplier_error'] =
                'Nama supplier wajib diisi.';

            header("Location: index.php?page=supplier&action=tambah");
            exit;
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['supplier_error'] =
                'Format email tidak valid.';

            header("Location: index.php?page=supplier&action=tambah");
            exit;
        }

        try {

            $this->supplierModel->create([
                'nama_supplier' => $namaSupplier,
                'kontak' => $kontak !== '' ? $kontak : null,
                'email' => $email !== '' ? $email : null,
                'alamat' => $alamat !== '' ? $alamat : null
            ]);

            $_SESSION['supplier_message'] =
                'Supplier berhasil ditambahkan.';

        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {

                $_SESSION['supplier_error'] =
                    'Data supplier tidak dapat disimpan karena terdapat data yang sama.';

            } else {

                $_SESSION['supplier_error'] =
                    'Terjadi kesalahan saat menambahkan supplier.';
            }
        }

        header("Location: index.php?page=supplier");
        exit;
    }

    public function edit()
    {
        $this->checkAdmin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['supplier_error'] =
                'ID supplier tidak valid.';

            header("Location: index.php?page=supplier");
            exit;
        }

        $supplier = $this->supplierModel->getById($id);

        if (!$supplier) {
            $_SESSION['supplier_error'] =
                'Supplier tidak ditemukan.';

            header("Location: index.php?page=supplier");
            exit;
        }

        require __DIR__ . '/../views/supplier/edit.php';
    }

    public function update()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=supplier");
            exit;
        }

        $id = (int) ($_POST['id_supplier'] ?? 0);

        $namaSupplier = trim($_POST['nama_supplier'] ?? '');
        $kontak = trim($_POST['kontak'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');

        if ($id <= 0 || $namaSupplier === '') {
            $_SESSION['supplier_error'] =
                'Data supplier tidak valid.';

            header("Location: index.php?page=supplier");
            exit;
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['supplier_error'] =
                'Format email tidak valid.';

            header("Location: index.php?page=supplier&action=edit&id=" . $id);
            exit;
        }

        $supplier = $this->supplierModel->getById($id);

        if (!$supplier) {
            $_SESSION['supplier_error'] =
                'Supplier tidak ditemukan.';

            header("Location: index.php?page=supplier");
            exit;
        }

        try {

            $this->supplierModel->update($id, [
                'nama_supplier' => $namaSupplier,
                'kontak' => $kontak !== '' ? $kontak : null,
                'email' => $email !== '' ? $email : null,
                'alamat' => $alamat !== '' ? $alamat : null
            ]);

            $_SESSION['supplier_message'] =
                'Data supplier berhasil diperbarui.';

        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {

                $_SESSION['supplier_error'] =
                    'Data supplier tidak dapat diperbarui karena terdapat data yang sama.';

            } else {

                $_SESSION['supplier_error'] =
                    'Terjadi kesalahan saat memperbarui supplier.';
            }
        }

        header("Location: index.php?page=supplier");
        exit;
    }

    public function delete()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=supplier");
            exit;
        }

        $id = (int) ($_POST['id_supplier'] ?? 0);

        if ($id <= 0) {
            $_SESSION['supplier_error'] =
                'Supplier yang akan dihapus tidak valid.';

            header("Location: index.php?page=supplier");
            exit;
        }

        $supplier = $this->supplierModel->getById($id);

        if (!$supplier) {
            $_SESSION['supplier_error'] =
                'Supplier tidak ditemukan.';

            header("Location: index.php?page=supplier");
            exit;
        }

        try {

            $jumlahBarang = $this->supplierModel->getJumlahBarang($id);

            if ($jumlahBarang > 0) {

                $_SESSION['supplier_error'] =
                    'Supplier "' . $supplier['nama_supplier'] .
                    '" tidak dapat dihapus karena masih digunakan oleh ' .
                    $jumlahBarang . ' barang.';

                header("Location: index.php?page=supplier");
                exit;
            }

            $berhasil = $this->supplierModel->delete($id);

            if (!$berhasil) {

                $_SESSION['supplier_error'] =
                    'Supplier gagal dihapus.';

                header("Location: index.php?page=supplier");
                exit;
            }

            $_SESSION['supplier_message'] =
                'Supplier "' . $supplier['nama_supplier'] .
                '" berhasil dihapus.';

        } catch (PDOException $e) {

            $_SESSION['supplier_error'] =
                'Supplier tidak dapat dihapus karena masih digunakan oleh data lain.';
        }

        header("Location: index.php?page=supplier");
        exit;
    }
}