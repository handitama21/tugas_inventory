<?php

class DashboardController
{
    private $connect;
    private $dashboardModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->dashboardModel = new DashboardModel($connect);
    }

    private function checkLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    public function index()
    {
        $this->checkLogin();

        $totalBarang = $this->dashboardModel->getTotalBarang();

        $stokMenipis = $this->dashboardModel->getStokMenipis();

        $totalSupplier = $this->dashboardModel->getTotalSupplier();

        $totalGudang = $this->dashboardModel->getTotalGudang();

        $barangTerbaru = $this->dashboardModel->getBarangTerbaru();

        $jumlahStokMenipisDanHabis =
            $this->dashboardModel->getJumlahStokMenipisDanHabis();

        require __DIR__ . '/../views/dashboard/index.php';
    }
}