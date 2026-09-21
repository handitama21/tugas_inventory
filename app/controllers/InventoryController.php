<?php

class InventoryController
{
    private $connect;
    private $inventoryModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->inventoryModel = new InventoryModel($connect);
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
            header("Location: index.php?page=inventory");
            exit;
        }
    }

    public function index()
    {
        $this->checkLogin();

        $barang = $this->inventoryModel->getAll();

        $totalBarang = $this->inventoryModel->getTotalBarang();

        $totalStok = $this->inventoryModel->getTotalStok();

        $message = $_SESSION['inventory_message'] ?? '';
        $error = $_SESSION['inventory_error'] ?? '';

        unset($_SESSION['inventory_message']);
        unset($_SESSION['inventory_error']);

        require __DIR__ . '/../views/inventory/index.php';
    }

    public function tambah()
    {
        $this->checkLogin();

        $kategori = $this->inventoryModel->getAllKategori();

        $gudang = $this->inventoryModel->getAllGudang();

        $supplier = $this->inventoryModel->getAllSupplier();

        $error = $_SESSION['inventory_error'] ?? '';

        unset($_SESSION['inventory_error']);

        require __DIR__ . '/../views/inventory/tambah.php';
    }

    public function simpan()
    {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=inventory");
            exit;
        }

        $namaBarang = trim($_POST['nama_barang'] ?? '');
        $idKategori = (int) ($_POST['id_kategori'] ?? 0);
        $stok = (int) ($_POST['kuantitas_stok'] ?? 0);
        $idGudang = (int) ($_POST['lokasi_gudang'] ?? 0);
        $serialNumber = trim($_POST['serial_number'] ?? '');
        $idSupplier = (int) ($_POST['id_supplier'] ?? 0);
        $harga = (float) ($_POST['harga'] ?? 0);

        if (
            $namaBarang === '' ||
            $idKategori <= 0 ||
            $stok < 0 ||
            $idGudang <= 0 ||
            $idSupplier <= 0 ||
            $harga < 0
        ) {
            $_SESSION['inventory_error'] =
                'Data barang belum lengkap.';

            header("Location: index.php?page=inventory&action=tambah");
            exit;
        }

        $kategori = $this->inventoryModel->getKategoriById($idKategori);

        if (!$kategori) {
            $_SESSION['inventory_error'] =
                'Kategori barang tidak ditemukan.';

            header("Location: index.php?page=inventory&action=tambah");
            exit;
        }

        $gudang = $this->inventoryModel->getGudangById($idGudang);

        if (!$gudang) {
            $_SESSION['inventory_error'] =
                'Gudang tidak ditemukan.';

            header("Location: index.php?page=inventory&action=tambah");
            exit;
        }

        $supplier = $this->inventoryModel->getSupplierById($idSupplier);

        if (!$supplier) {
            $_SESSION['inventory_error'] =
                'Supplier tidak ditemukan.';

            header("Location: index.php?page=inventory&action=tambah");
            exit;
        }

        $lastKode = $this->inventoryModel->getLastKode();

        if ($lastKode && preg_match('/BRG(\d+)/', $lastKode, $matches)) {
            $nomor = (int) $matches[1] + 1;
        } else {
            $nomor = 1;
        }

        $kodeBarang = 'BRG' . str_pad($nomor, 3, '0', STR_PAD_LEFT);

        if ($stok <= 0) {
            $status = 'rusak';
        } else {
            $status = 'tersedia';
        }

        try {

            $this->inventoryModel->create([
                'kode_barang' => $kodeBarang,
                'nama_barang' => $namaBarang,
                'id_kategori' => $idKategori,
                'id_gudang' => $idGudang,
                'id_supplier' => $idSupplier,
                'serial_number' => $serialNumber !== '' ? $serialNumber : null,
                'stok' => $stok,
                'kondisi' => 'baik',
                'status' => $status,
                'harga' => $harga,
                'deskripsi' => null
            ]);

            $_SESSION['inventory_message'] =
                'Barang berhasil ditambahkan.';

        } catch (PDOException $e) {

            $_SESSION['inventory_error'] =
                'Terjadi kesalahan saat menambahkan barang.';
        }

        header("Location: index.php?page=inventory");
        exit;
    }

    public function edit()
    {
        $this->checkLogin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header("Location: index.php?page=inventory");
            exit;
        }

        $barang = $this->inventoryModel->getById($id);

        if (!$barang) {
            header("Location: index.php?page=inventory");
            exit;
        }

        $kategori = $this->inventoryModel->getAllKategori();

        $gudang = $this->inventoryModel->getAllGudang();

        $supplier = $this->inventoryModel->getAllSupplier();

        $error = $_SESSION['inventory_error'] ?? '';

        unset($_SESSION['inventory_error']);

        require __DIR__ . '/../views/inventory/edit.php';
    }

    public function update()
    {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=inventory");
            exit;
        }

        $id = (int) ($_POST['id_barang'] ?? 0);

        $namaBarang = trim($_POST['nama_barang'] ?? '');
        $idKategori = (int) ($_POST['id_kategori'] ?? 0);
        $stok = (int) ($_POST['kuantitas_stok'] ?? 0);
        $idGudang = (int) ($_POST['lokasi_gudang'] ?? 0);
        $serialNumber = trim($_POST['serial_number'] ?? '');
        $idSupplier = (int) ($_POST['id_supplier'] ?? 0);
        $harga = (float) ($_POST['harga'] ?? 0);

        if (
            $id <= 0 ||
            $namaBarang === '' ||
            $idKategori <= 0 ||
            $stok < 0 ||
            $idGudang <= 0 ||
            $idSupplier <= 0 ||
            $harga < 0
        ) {
            $_SESSION['inventory_error'] =
                'Data barang tidak valid.';

            header("Location: index.php?page=inventory");
            exit;
        }

        $barang = $this->inventoryModel->getById($id);

        if (!$barang) {
            $_SESSION['inventory_error'] =
                'Barang tidak ditemukan.';

            header("Location: index.php?page=inventory");
            exit;
        }

        $kategori = $this->inventoryModel->getKategoriById($idKategori);

        if (!$kategori) {
            $_SESSION['inventory_error'] =
                'Kategori barang tidak ditemukan.';

            header("Location: index.php?page=inventory&action=edit&id=" . $id);
            exit;
        }

        $gudang = $this->inventoryModel->getGudangById($idGudang);

        if (!$gudang) {
            $_SESSION['inventory_error'] =
                'Gudang tidak ditemukan.';

            header("Location: index.php?page=inventory&action=edit&id=" . $id);
            exit;
        }

        $supplier = $this->inventoryModel->getSupplierById($idSupplier);

        if (!$supplier) {
            $_SESSION['inventory_error'] =
                'Supplier tidak ditemukan.';

            header("Location: index.php?page=inventory&action=edit&id=" . $id);
            exit;
        }

        if ($stok <= 0) {
            $status = 'rusak';
        } else {
            $status = 'tersedia';
        }

        try {

            $this->inventoryModel->update($id, [
                'nama_barang' => $namaBarang,
                'id_kategori' => $idKategori,
                'id_gudang' => $idGudang,
                'id_supplier' => $idSupplier,
                'serial_number' => $serialNumber !== '' ? $serialNumber : null,
                'stok' => $stok,
                'kondisi' => $barang['kondisi'],
                'status' => $status,
                'harga' => $harga,
                'deskripsi' => $barang['deskripsi']
            ]);

            $_SESSION['inventory_message'] =
                'Data barang berhasil diperbarui.';

        } catch (PDOException $e) {

            $_SESSION['inventory_error'] =
                'Terjadi kesalahan saat memperbarui barang.';
        }

        header("Location: index.php?page=inventory");
        exit;
    }

    public function delete()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=inventory");
            exit;
        }

        $id = (int) ($_POST['id_barang'] ?? 0);

        if ($id <= 0) {
            header("Location: index.php?page=inventory");
            exit;
        }

        $barang = $this->inventoryModel->getById($id);

        if (!$barang) {
            $_SESSION['inventory_error'] =
                'Barang tidak ditemukan.';

            header("Location: index.php?page=inventory");
            exit;
        }

        try {

            $this->inventoryModel->delete($id);

            $_SESSION['inventory_message'] =
                'Barang "' . $barang['nama_barang'] .
                '" berhasil dihapus.';

        } catch (PDOException $e) {

            $_SESSION['inventory_error'] =
                'Barang tidak dapat dihapus karena masih digunakan oleh data lain.';
        }

        header("Location: index.php?page=inventory");
        exit;
    }

    public function detail()
    {
        $this->checkLogin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header("Location: index.php?page=inventory");
            exit;
        }

        $barang = $this->inventoryModel->getById($id);

        if (!$barang) {
            header("Location: index.php?page=inventory");
            exit;
        }

        require __DIR__ . '/../views/inventory/detail.php';
    }
}