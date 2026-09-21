<?php

session_start();

$page = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

require_once __DIR__ . "/config/database.php";

require_once __DIR__ . "/app/models/UserModel.php";
require_once __DIR__ . "/app/controllers/AuthController.php";

require_once __DIR__ . "/app/models/DashboardModel.php";
require_once __DIR__ . "/app/controllers/DashboardController.php";

require_once __DIR__ . "/app/models/InventoryModel.php";
require_once __DIR__ . "/app/controllers/InventoryController.php";

require_once __DIR__ . "/app/models/KategoriModel.php";
require_once __DIR__ . "/app/controllers/KategoriController.php";

require_once __DIR__ . "/app/models/GudangModel.php";
require_once __DIR__ . "/app/controllers/GudangController.php";

require_once __DIR__ . "/app/models/SupplierModel.php";
require_once __DIR__ . "/app/controllers/SupplierController.php";

require_once __DIR__ . "/app/models/TransaksiModel.php";
require_once __DIR__ . "/app/models/TransaksiDetailModel.php";

require_once __DIR__ . "/app/controllers/TransaksiController.php";
require_once __DIR__ . "/app/controllers/TransaksiDetailController.php";

require_once __DIR__ . "/app/models/HistoryTransaksiModel.php";
require_once __DIR__ . "/app/controllers/HistoryTransaksiController.php";

require_once __DIR__ . "/app/controllers/SettingsController.php";

require_once __DIR__ . "/app/middleware/AuthMiddleware.php";


$authController = new AuthController($connect);

$dashboardController = new DashboardController($connect);

$inventoryController = new InventoryController($connect);

$kategoriController = new KategoriController($connect);

$gudangController = new GudangController($connect);

$supplierController = new SupplierController($connect);

$transaksiController = new TransaksiController($connect);

$transaksiDetailController = new TransaksiDetailController($connect);

$historyController = new HistoryTransaksiController($connect);

$settingsController = new SettingsController($connect);


AuthMiddleware::check($connect);


if ($page === 'login') {

    $authController->login();


} elseif ($page === 'logout') {

    $authController->logout();


} elseif ($page === 'dashboard') {

    $dashboardController->index();


} elseif ($page === 'inventory') {

    if ($action === 'tambah') {

        $inventoryController->tambah();

    } elseif ($action === 'simpan') {

        $inventoryController->simpan();

    } elseif ($action === 'edit') {

        $inventoryController->edit();

    } elseif ($action === 'update') {

        $inventoryController->update();

    } elseif ($action === 'delete') {

        $inventoryController->delete();

    } elseif ($action === 'detail') {

        $inventoryController->detail();

    } else {

        $inventoryController->index();

    }


} elseif ($page === 'kategori') {

    if ($action === 'save') {

        $kategoriController->save();

    } elseif ($action === 'delete') {

        $kategoriController->delete();

    } else {

        $kategoriController->index();

    }


} elseif ($page === 'storage') {

    if ($action === 'tambah') {

        $gudangController->tambah();

    } elseif ($action === 'simpan') {

        $gudangController->simpan();

    } elseif ($action === 'edit') {

        $gudangController->edit();

    } elseif ($action === 'update') {

        $gudangController->update();

    } elseif ($action === 'delete') {

        $gudangController->delete();

    } else {

        $gudangController->index();

    }


} elseif ($page === 'supplier') {

    if ($action === 'tambah') {

        $supplierController->tambah();

    } elseif ($action === 'simpan') {

        $supplierController->simpan();

    } elseif ($action === 'edit') {

        $supplierController->edit();

    } elseif ($action === 'update') {

        $supplierController->update();

    } elseif ($action === 'delete') {

        $supplierController->delete();

    } else {

        $supplierController->index();

    }


} elseif ($page === 'transaksi') {

    if ($action === 'simpan') {

        $transaksiController->simpan();

    } else {

        $transaksiController->index();

    }


} elseif ($page === 'transaksi-detail') {

    if ($action === 'detail') {

        $transaksiDetailController->detail();

    } else {

        $transaksiDetailController->getByTransaksi();

    }


} elseif ($page === 'history') {

    if ($action === 'detail') {

        $historyController->detail();

    } else {

        $historyController->index();

    }


} elseif ($page === 'settings') {

    if ($action === 'users') {

        $settingsController->users();

    } elseif ($action === 'save-user') {

        $settingsController->saveUser();

    } elseif ($action === 'delete-user') {

        $settingsController->deleteUser();

    } elseif ($action === 'profile') {

        $settingsController->profile();

    } elseif ($action === 'kategori') {

        $settingsController->kategori();

    } elseif ($action === 'gudang') {

        $settingsController->gudang();

    } elseif ($action === 'notifikasi') {

        $settingsController->notifikasi();

    } else {

        $settingsController->index();

    }


} else {

    echo "Halaman tidak ditemukan.";

}