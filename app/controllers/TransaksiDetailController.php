<?php

class TransaksiDetailController
{
    private $connect;
    private $detailModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->detailModel = new TransaksiDetailModel($connect);
    }

    public function getByTransaksi()
    {
        header('Content-Type: application/json');

        $idTransaksi = $_GET['id'] ?? null;

        if (!$idTransaksi) {
            echo json_encode([
                'success' => false,
                'message' => 'ID transaksi tidak ditemukan.'
            ]);
            exit;
        }

        $detail = $this->detailModel->getByTransaksi($idTransaksi);

        echo json_encode([
            'success' => true,
            'data' => $detail
        ]);

        exit;
    }

    public function detail()
    {
        header('Content-Type: application/json');

        $idDetail = $_GET['id'] ?? null;

        if (!$idDetail) {
            echo json_encode([
                'success' => false,
                'message' => 'ID detail transaksi tidak ditemukan.'
            ]);
            exit;
        }

        $detail = $this->detailModel->getById($idDetail);

        if (!$detail) {
            echo json_encode([
                'success' => false,
                'message' => 'Detail transaksi tidak ditemukan.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $detail
        ]);

        exit;
    }
}