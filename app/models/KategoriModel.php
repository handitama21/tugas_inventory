<?php

class KategoriModel
{
    private $connect;
    private $table = "kategori";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getAll()
    {
        $query = "SELECT
                    id_kategori,
                    nama_kategori,
                    deskripsi,
                    created_at,
                    updated_at
                  FROM {$this->table}
                  ORDER BY id_kategori DESC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT
                    id_kategori,
                    nama_kategori,
                    deskripsi,
                    created_at,
                    updated_at
                  FROM {$this->table}
                  WHERE id_kategori = :id
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
                      nama_kategori,
                      deskripsi
                  )
                  VALUES
                  (
                      :nama_kategori,
                      :deskripsi
                  )";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_kategori' => $data['nama_kategori'],
            ':deskripsi' => $data['deskripsi']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET
                      nama_kategori = :nama_kategori,
                      deskripsi = :deskripsi
                  WHERE id_kategori = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_kategori' => $data['nama_kategori'],
            ':deskripsi' => $data['deskripsi'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table}
                  WHERE id_kategori = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}