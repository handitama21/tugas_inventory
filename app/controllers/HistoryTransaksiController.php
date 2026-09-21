<?php

class HistoryTransaksiController
{
    private $historyModel;

    public function __construct($connect)
    {
        $this->historyModel = new HistoryTransaksiModel($connect);
    }

    public function index()
    {
        $transaksi = $this->historyModel->getAll();

        if (!is_array($transaksi)) {
            $transaksi = [];
        }

        require __DIR__ . '/../views/history/index.php';
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?page=history");
            exit;
        }

        $transaksi = $this->historyModel->getById($id);

        if (!$transaksi) {
            header("Location: index.php?page=history");
            exit;
        }

        $detail = $this->historyModel->getDetail($id);

        if (!is_array($detail)) {
            $detail = [];
        }

        require __DIR__ . '/../views/history/detail.php';
    }
}