<?php

class HistoryTransaksiModel
{
    private $connect;
    private $table = "transaksi";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getAll()
    {
        $query = "SELECT
                    t.id_transaksi,
                    t.kode_transaksi,
                    t.nama_pembeli,
                    t.subtotal,
                    t.diskon,
                    t.grand_total,
                    t.uang_bayar,
                    t.kembalian,
                    t.created_at,
                    u.nama AS nama_user
                  FROM {$this->table} t
                  INNER JOIN users u
                    ON t.id_user = u.id_user
                  ORDER BY t.created_at DESC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT
                    t.id_transaksi,
                    t.kode_transaksi,
                    t.nama_pembeli,
                    t.subtotal,
                    t.diskon,
                    t.grand_total,
                    t.uang_bayar,
                    t.kembalian,
                    t.created_at,
                    u.nama AS nama_user
                  FROM {$this->table} t
                  INNER JOIN users u
                    ON t.id_user = u.id_user
                  WHERE t.id_transaksi = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDetail($id)
    {
        $query = "SELECT
                    td.id_detail,
                    td.id_transaksi,
                    td.id_barang,
                    td.harga_satuan,
                    td.jumlah,
                    td.diskon_detail,
                    td.subtotal,
                    b.kode_barang,
                    b.nama_barang
                  FROM transaksi_detail td
                  INNER JOIN barang b
                    ON td.id_barang = b.id_barang
                  WHERE td.id_transaksi = :id
                  ORDER BY td.id_detail ASC";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}