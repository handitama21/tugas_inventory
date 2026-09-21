<?php

class KategoriController
{
    private $connect;
    private $kategoriModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->kategoriModel = new KategoriModel($connect);
    }

    private function checkAdmin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $kategori = $this->kategoriModel->getAll();

        foreach ($kategori as &$item) {
            $query = "SELECT COUNT(*) 
                      FROM barang 
                      WHERE id_kategori = :id_kategori";

            $stmt = $this->connect->prepare($query);

            $stmt->execute([
                ':id_kategori' => $item['id_kategori']
            ]);

            $item['jumlah_barang'] = (int) $stmt->fetchColumn();
        }

        unset($item);

        $message = $_SESSION['kategori_message'] ?? '';
        $error = $_SESSION['kategori_error'] ?? '';

        unset($_SESSION['kategori_message']);
        unset($_SESSION['kategori_error']);

        require __DIR__ . '/../views/settings/kategori.php';
    }

    public function save()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=kategori");
            exit;
        }

        $idKategori = (int) ($_POST['id_kategori'] ?? 0);
        $namaKategori = trim($_POST['nama_kategori'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');

        if ($namaKategori === '') {
            $_SESSION['kategori_error'] = 'Nama kategori wajib diisi.';
            header("Location: index.php?page=kategori");
            exit;
        }

        try {
            if ($idKategori > 0) {
                $kategoriLama = $this->kategoriModel->getById($idKategori);

                if (!$kategoriLama) {
                    $_SESSION['kategori_error'] = 'Kategori tidak ditemukan.';
                    header("Location: index.php?page=kategori");
                    exit;
                }

                $this->kategoriModel->update($idKategori, [
                    'nama_kategori' => $namaKategori,
                    'deskripsi' => $deskripsi !== '' ? $deskripsi : null
                ]);

                $_SESSION['kategori_message'] =
                    'Kategori berhasil diperbarui.';
            } else {
                $this->kategoriModel->create([
                    'nama_kategori' => $namaKategori,
                    'deskripsi' => $deskripsi !== '' ? $deskripsi : null
                ]);

                $_SESSION['kategori_message'] =
                    'Kategori berhasil ditambahkan.';
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $_SESSION['kategori_error'] =
                    'Nama kategori sudah digunakan.';
            } else {
                $_SESSION['kategori_error'] =
                    'Terjadi kesalahan saat menyimpan kategori.';
            }
        }

        header("Location: index.php?page=kategori");
        exit;
    }

    public function delete()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=kategori");
            exit;
        }

        $idKategori = (int) ($_POST['id_kategori'] ?? 0);

        if ($idKategori <= 0) {
            $_SESSION['kategori_error'] =
                'Kategori yang akan dihapus tidak valid.';

            header("Location: index.php?page=kategori");
            exit;
        }

        try {
            $kategori = $this->kategoriModel->getById($idKategori);

            if (!$kategori) {
                $_SESSION['kategori_error'] =
                    'Kategori tidak ditemukan.';

                header("Location: index.php?page=kategori");
                exit;
            }

            $query = "SELECT COUNT(*)
                      FROM barang
                      WHERE id_kategori = :id_kategori";

            $stmt = $this->connect->prepare($query);

            $stmt->execute([
                ':id_kategori' => $idKategori
            ]);

            $jumlahBarang = (int) $stmt->fetchColumn();

            if ($jumlahBarang > 0) {
                $_SESSION['kategori_error'] =
                    'Kategori "' . $kategori['nama_kategori'] .
                    '" tidak dapat dihapus karena masih digunakan oleh ' .
                    $jumlahBarang . ' barang.';

                header("Location: index.php?page=kategori");
                exit;
            }

            $namaKategori = $kategori['nama_kategori'];

            $berhasil = $this->kategoriModel->delete($idKategori);

            if (!$berhasil) {
                $_SESSION['kategori_error'] =
                    'Kategori gagal dihapus.';

                header("Location: index.php?page=kategori");
                exit;
            }

            $_SESSION['kategori_message'] =
                'Kategori "' . $namaKategori .
                '" berhasil dihapus.';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $_SESSION['kategori_error'] =
                    'Kategori tidak dapat dihapus karena masih digunakan oleh barang.';
            } else {
                $_SESSION['kategori_error'] =
                    'Terjadi kesalahan saat menghapus kategori.';
            }
        }

        header("Location: index.php?page=kategori");
        exit;
    }
}