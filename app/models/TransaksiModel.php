<?php

class TransaksiModel
{
    private $connect;
    private $table = "transaksi";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (
                      kode_transaksi,
                      id_user,
                      nama_pembeli,
                      subtotal,
                      diskon,
                      grand_total,
                      uang_bayar,
                      kembalian
                  )
                  VALUES
                  (
                      :kode_transaksi,
                      :id_user,
                      :nama_pembeli,
                      :subtotal,
                      :diskon,
                      :grand_total,
                      :uang_bayar,
                      :kembalian
                  )";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':kode_transaksi' => $data['kode_transaksi'],
            ':id_user' => $data['id_user'],
            ':nama_pembeli' => $data['nama_pembeli'],
            ':subtotal' => $data['subtotal'],
            ':diskon' => $data['diskon'],
            ':grand_total' => $data['grand_total'],
            ':uang_bayar' => $data['uang_bayar'],
            ':kembalian' => $data['kembalian']
        ]);

        return $this->connect->lastInsertId();
    }

    public function getAll()
    {
        $query = "SELECT
                    t.id_transaksi,
                    t.kode_transaksi,
                    t.id_user,
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
                    t.id_user,
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
}