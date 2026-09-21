<?php

class TransaksiController
{
    private $connect;
    private $transaksiModel;
    private $detailModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->transaksiModel = new TransaksiModel($connect);
        $this->detailModel = new TransaksiDetailModel($connect);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $query = "SELECT
                    id_barang,
                    kode_barang,
                    nama_barang,
                    stok,
                    harga,
                    kondisi,
                    status
                  FROM barang
                  WHERE stok > 0
                  ORDER BY nama_barang ASC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        $barang = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/transaksi/index.php';
    }

    public function simpan()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Method tidak diperbolehkan.'
            ]);
            exit;
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.'
            ]);
            exit;
        }

        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (!is_array($data)) {
            echo json_encode([
                'success' => false,
                'message' => 'Data transaksi tidak valid.'
            ]);
            exit;
        }

        $keranjang = $data['keranjang'] ?? [];
        $namaPembeli = trim($data['nama_pembeli'] ?? '');
        $uangBayar = (float) ($data['uang_bayar'] ?? 0);

        if (empty($keranjang)) {
            echo json_encode([
                'success' => false,
                'message' => 'Keranjang masih kosong.'
            ]);
            exit;
        }

        if ($uangBayar < 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Uang pembayaran tidak valid.'
            ]);
            exit;
        }

        $subtotal = 0;
        $diskon = 0;
        $dataBarang = [];

        try {

            foreach ($keranjang as $item) {

                $idBarang = (int) ($item['id_barang'] ?? 0);
                $jumlah = (int) ($item['jumlah'] ?? 0);

                if ($idBarang <= 0 || $jumlah <= 0) {
                    throw new Exception('Data barang tidak valid.');
                }

                $barang = $this->getBarang($idBarang);

                if (!$barang) {
                    throw new Exception('Barang tidak ditemukan.');
                }

                $stokSekarang = (int) $barang['stok'];

                if ($stokSekarang < $jumlah) {
                    throw new Exception(
                        'Stok ' .
                        $barang['nama_barang'] .
                        ' tidak mencukupi. Stok tersedia: ' .
                        $stokSekarang
                    );
                }

                $harga = (float) $barang['harga'];

                $subtotalItem = $harga * $jumlah;

                $diskonItem = 0;

                if ($jumlah >= 10) {
                    $diskonItem = $harga;
                }

                $subtotalDetail = $subtotalItem - $diskonItem;

                $subtotal += $subtotalItem;
                $diskon += $diskonItem;

                $dataBarang[] = [
                    'id_barang' => $idBarang,
                    'harga' => $harga,
                    'jumlah' => $jumlah,
                    'diskon_detail' => $diskonItem,
                    'subtotal' => $subtotalDetail
                ];
            }

            $grandTotal = $subtotal - $diskon;

            if ($uangBayar < $grandTotal) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Uang pembayaran kurang.'
                ]);
                exit;
            }

            $kembalian = $uangBayar - $grandTotal;

            $this->connect->beginTransaction();

            $kodeTransaksi =
                'TRX-' .
                date('YmdHis') .
                '-' .
                random_int(100, 999);

            $idTransaksi = $this->transaksiModel->create([
                'kode_transaksi' => $kodeTransaksi,
                'id_user' => $_SESSION['user_id'],
                'nama_pembeli' => $namaPembeli !== ''
                    ? $namaPembeli
                    : null,
                'subtotal' => $subtotal,
                'diskon' => $diskon,
                'grand_total' => $grandTotal,
                'uang_bayar' => $uangBayar,
                'kembalian' => $kembalian
            ]);

            if (!$idTransaksi) {
                throw new Exception(
                    'Gagal menyimpan transaksi.'
                );
            }

            foreach ($dataBarang as $item) {

                $idDetail = $this->detailModel->create([
                    'id_transaksi' => $idTransaksi,
                    'id_barang' => $item['id_barang'],
                    'harga_satuan' => $item['harga'],
                    'jumlah' => $item['jumlah'],
                    'diskon_detail' => $item['diskon_detail'],
                    'subtotal' => $item['subtotal']
                ]);

                if (!$idDetail) {
                    throw new Exception(
                        'Gagal menyimpan detail transaksi.'
                    );
                }

                $this->kurangiStok(
                    $item['id_barang'],
                    $item['jumlah']
                );
            }

            $this->connect->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'kode_transaksi' => $kodeTransaksi,
                'id_transaksi' => $idTransaksi,
                'kembalian' => $kembalian
            ]);

            exit;

        } catch (Exception $e) {

            if ($this->connect->inTransaction()) {
                $this->connect->rollBack();
            }

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);

            exit;
        }
    }

    private function getBarang($idBarang)
    {
        $query = "SELECT
                    id_barang,
                    nama_barang,
                    stok,
                    harga
                  FROM barang
                  WHERE id_barang = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $idBarang
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function kurangiStok($idBarang, $jumlah)
    {
        $query = "UPDATE barang
                  SET stok = stok - :jumlah
                  WHERE id_barang = :id
                  AND stok >= :jumlah";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':jumlah' => $jumlah,
            ':id' => $idBarang
        ]);

        if ($stmt->rowCount() === 0) {
            throw new Exception(
                'Gagal mengurangi stok barang.'
            );
        }
    }
}