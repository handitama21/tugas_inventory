<?php

class DashboardModel
{
    private $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getTotalBarang()
    {
        $query = "SELECT COUNT(*) FROM barang";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getStokMenipis()
    {
        $query = "SELECT COUNT(*)
                  FROM barang
                  WHERE stok > 0
                  AND stok <= 10";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getTotalSupplier()
    {
        $query = "SELECT COUNT(*) FROM supplier";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getTotalGudang()
    {
        $query = "SELECT COUNT(*) FROM gudang";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getBarangTerbaru()
    {
        $query = "SELECT
                    b.id_barang,
                    b.kode_barang,
                    b.nama_barang,
                    b.stok,
                    b.kondisi,
                    b.status,
                    b.created_at,
                    k.nama_kategori,
                    g.nama_gudang,
                    g.lokasi
                  FROM barang b
                  INNER JOIN kategori k
                      ON b.id_kategori = k.id_kategori
                  INNER JOIN gudang g
                      ON b.id_gudang = g.id_gudang
                  ORDER BY b.created_at DESC
                  LIMIT 5";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJumlahStokMenipisDanHabis()
    {
        $query = "SELECT COUNT(*)
                  FROM barang
                  WHERE stok <= 10";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}