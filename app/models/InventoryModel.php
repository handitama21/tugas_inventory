<?php

class InventoryModel
{
    private $connect;
    private $table = "barang";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getAll()
    {
        $query = "SELECT
                    b.id_barang,
                    b.kode_barang,
                    b.nama_barang,
                    b.id_kategori,
                    b.id_gudang,
                    b.id_supplier,
                    b.serial_number,
                    b.stok,
                    b.kondisi,
                    b.status,
                    b.harga,
                    b.deskripsi,
                    b.created_at,
                    b.updated_at,
                    k.nama_kategori,
                    g.nama_gudang,
                    s.nama_supplier
                  FROM barang b
                  INNER JOIN kategori k
                      ON b.id_kategori = k.id_kategori
                  INNER JOIN gudang g
                      ON b.id_gudang = g.id_gudang
                  LEFT JOIN supplier s
                      ON b.id_supplier = s.id_supplier
                  ORDER BY b.id_barang DESC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT
                    b.*,
                    k.nama_kategori,
                    g.nama_gudang,
                    s.nama_supplier
                  FROM barang b
                  INNER JOIN kategori k
                      ON b.id_kategori = k.id_kategori
                  INNER JOIN gudang g
                      ON b.id_gudang = g.id_gudang
                  LEFT JOIN supplier s
                      ON b.id_supplier = s.id_supplier
                  WHERE b.id_barang = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalBarang()
    {
        $query = "SELECT COUNT(*) FROM barang";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getTotalStok()
    {
        $query = "SELECT COALESCE(SUM(stok), 0) FROM barang";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getAllKategori()
    {
        $query = "SELECT
                    id_kategori,
                    nama_kategori,
                    deskripsi
                  FROM kategori
                  ORDER BY nama_kategori ASC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKategoriById($id)
    {
        $query = "SELECT
                    id_kategori,
                    nama_kategori
                  FROM kategori
                  WHERE id_kategori = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllGudang()
    {
        $query = "SELECT
                    id_gudang,
                    nama_gudang,
                    lokasi
                  FROM gudang
                  ORDER BY nama_gudang ASC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGudangById($id)
    {
        $query = "SELECT
                    id_gudang,
                    nama_gudang,
                    lokasi
                  FROM gudang
                  WHERE id_gudang = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllSupplier()
    {
        $query = "SELECT
                    id_supplier,
                    nama_supplier,
                    kontak
                  FROM supplier
                  ORDER BY nama_supplier ASC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplierById($id)
    {
        $query = "SELECT
                    id_supplier,
                    nama_supplier
                  FROM supplier
                  WHERE id_supplier = :id
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastKode()
    {
        $query = "SELECT kode_barang
                  FROM barang
                  ORDER BY id_barang DESC
                  LIMIT 1";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function create($data)
    {
        $query = "INSERT INTO barang
                    (
                        kode_barang,
                        nama_barang,
                        id_kategori,
                        id_gudang,
                        id_supplier,
                        serial_number,
                        stok,
                        kondisi,
                        status,
                        harga,
                        deskripsi
                    )
                  VALUES
                    (
                        :kode_barang,
                        :nama_barang,
                        :id_kategori,
                        :id_gudang,
                        :id_supplier,
                        :serial_number,
                        :stok,
                        :kondisi,
                        :status,
                        :harga,
                        :deskripsi
                    )";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':kode_barang' => $data['kode_barang'],
            ':nama_barang' => $data['nama_barang'],
            ':id_kategori' => $data['id_kategori'],
            ':id_gudang' => $data['id_gudang'],
            ':id_supplier' => $data['id_supplier'],
            ':serial_number' => $data['serial_number'],
            ':stok' => $data['stok'],
            ':kondisi' => $data['kondisi'],
            ':status' => $data['status'],
            ':harga' => $data['harga'],
            ':deskripsi' => $data['deskripsi']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE barang
                  SET
                    nama_barang = :nama_barang,
                    id_kategori = :id_kategori,
                    id_gudang = :id_gudang,
                    id_supplier = :id_supplier,
                    serial_number = :serial_number,
                    stok = :stok,
                    kondisi = :kondisi,
                    status = :status,
                    harga = :harga,
                    deskripsi = :deskripsi
                  WHERE id_barang = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nama_barang' => $data['nama_barang'],
            ':id_kategori' => $data['id_kategori'],
            ':id_gudang' => $data['id_gudang'],
            ':id_supplier' => $data['id_supplier'],
            ':serial_number' => $data['serial_number'],
            ':stok' => $data['stok'],
            ':kondisi' => $data['kondisi'],
            ':status' => $data['status'],
            ':harga' => $data['harga'],
            ':deskripsi' => $data['deskripsi'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $query = "DELETE FROM barang
                  WHERE id_barang = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}