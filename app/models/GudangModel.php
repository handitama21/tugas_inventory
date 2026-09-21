<?php

class GudangModel
{
    private $connect;
    private $table = "gudang";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getAll()
    {
        $query = "SELECT
                    g.id_gudang,
                    g.nama_gudang,
                    g.lokasi,
                    g.deskripsi,
                    g.created_at,
                    g.updated_at,
                    COUNT(b.id_barang) AS jumlah_barang
                  FROM {$this->table} g
                  LEFT JOIN barang b
                    ON g.id_gudang = b.id_gudang
                  GROUP BY
                    g.id_gudang,
                    g.nama_gudang,
                    g.lokasi,
                    g.deskripsi,
                    g.created_at,
                    g.updated_at
                  ORDER BY g.id_gudang DESC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT *
                  FROM {$this->table}
                  WHERE id_gudang = :id
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
                        nama_gudang,
                        lokasi,
                        deskripsi
                    )
                  VALUES
                    (
                        :nama_gudang,
                        :lokasi,
                        :deskripsi
                    )";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_gudang' => $data['nama_gudang'],
            ':lokasi' => $data['lokasi'],
            ':deskripsi' => $data['deskripsi']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET
                    nama_gudang = :nama_gudang,
                    lokasi = :lokasi,
                    deskripsi = :deskripsi
                  WHERE id_gudang = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_gudang' => $data['nama_gudang'],
            ':lokasi' => $data['lokasi'],
            ':deskripsi' => $data['deskripsi'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table}
                  WHERE id_gudang = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function getJumlahBarang($id)
    {
        $query = "SELECT COUNT(*)
                  FROM barang
                  WHERE id_gudang = :id";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return (int) $stmt->fetchColumn();
    }
}