<?php

class SupplierModel
{
    private $connect;
    private $table = "supplier";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getAll()
    {
        $query = "SELECT
                    s.id_supplier,
                    s.nama_supplier,
                    s.kontak,
                    s.email,
                    s.alamat,
                    s.created_at,
                    s.updated_at,
                    COUNT(b.id_barang) AS jumlah_barang
                  FROM {$this->table} s
                  LEFT JOIN barang b
                    ON s.id_supplier = b.id_supplier
                  GROUP BY
                    s.id_supplier,
                    s.nama_supplier,
                    s.kontak,
                    s.email,
                    s.alamat,
                    s.created_at,
                    s.updated_at
                  ORDER BY s.id_supplier DESC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM {$this->table}
                  WHERE id_supplier = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                    (
                        nama_supplier,
                        kontak,
                        email,
                        alamat
                    )
                  VALUES
                    (
                        :nama_supplier,
                        :kontak,
                        :email,
                        :alamat
                    )";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_supplier' => $data['nama_supplier'],
            ':kontak' => $data['kontak'],
            ':email' => $data['email'],
            ':alamat' => $data['alamat']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET
                    nama_supplier = :nama_supplier,
                    kontak = :kontak,
                    email = :email,
                    alamat = :alamat
                  WHERE id_supplier = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_supplier' => $data['nama_supplier'],
            ':kontak' => $data['kontak'],
            ':email' => $data['email'],
            ':alamat' => $data['alamat'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table}
                  WHERE id_supplier = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function getJumlahBarang($id)
    {
        $query = "SELECT COUNT(*)
                  FROM barang
                  WHERE id_supplier = :id";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn();
    }
}