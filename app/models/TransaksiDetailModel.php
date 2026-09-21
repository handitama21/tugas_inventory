<?php

class TransaksiDetailModel
{
    private $connect;
    private $table = "transaksi_detail";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (
                      id_transaksi,
                      id_barang,
                      harga_satuan,
                      jumlah,
                      diskon_detail,
                      subtotal
                  )
                  VALUES
                  (
                      :id_transaksi,
                      :id_barang,
                      :harga_satuan,
                      :jumlah,
                      :diskon_detail,
                      :subtotal
                  )";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id_transaksi' => $data['id_transaksi'],
            ':id_barang' => $data['id_barang'],
            ':harga_satuan' => $data['harga_satuan'],
            ':jumlah' => $data['jumlah'],
            ':diskon_detail' => $data['diskon_detail'],
            ':subtotal' => $data['subtotal']
        ]);

        return $this->connect->lastInsertId();
    }

    public function getByTransaksi($idTransaksi)
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
                  FROM {$this->table} td
                  INNER JOIN barang b
                    ON td.id_barang = b.id_barang
                  WHERE td.id_transaksi = :id_transaksi
                  ORDER BY td.id_detail ASC";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id_transaksi' => $idTransaksi
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($idDetail)
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
                  FROM {$this->table} td
                  INNER JOIN barang b
                    ON td.id_barang = b.id_barang
                  WHERE td.id_detail = :id_detail
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id_detail' => $idDetail
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}