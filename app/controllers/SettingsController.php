<?php

class SettingsController
{
    private $connect;
    private $userModel;

    public function __construct($connect)
    {
        $this->connect = $connect;
        $this->userModel = new UserModel($connect);
    }

    private function checkAdmin()
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public function index()
    {
        $this->checkAdmin();

        require __DIR__ . '/../views/settings/index.php';
    }

    public function users()
    {
        $this->checkAdmin();

        $users = $this->userModel->getAll();

        $message = $_SESSION['settings_message'] ?? '';
        $error = $_SESSION['settings_error'] ?? '';

        unset($_SESSION['settings_message']);
        unset($_SESSION['settings_error']);

        require __DIR__ . '/../views/settings/users.php';
    }

    public function saveUser()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=settings&action=users");
            exit;
        }

        $idUser = (int) ($_POST['id_user'] ?? 0);
        $nip = trim($_POST['nip'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $status = $_POST['status'] ?? 'aktif';

        if ($nip === '' || $nama === '' || $email === '') {
            $_SESSION['settings_error'] = 'NIP, nama, dan email wajib diisi.';
            header("Location: index.php?page=settings&action=users");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['settings_error'] = 'Format email tidak valid.';
            header("Location: index.php?page=settings&action=users");
            exit;
        }

        if (!in_array($role, ['admin', 'user'], true)) {
            $_SESSION['settings_error'] = 'Role tidak valid.';
            header("Location: index.php?page=settings&action=users");
            exit;
        }

        if (!in_array($status, ['aktif', 'nonaktif'], true)) {
            $_SESSION['settings_error'] = 'Status tidak valid.';
            header("Location: index.php?page=settings&action=users");
            exit;
        }

        try {

            if ($idUser > 0) {

                $userLama = $this->userModel->getById($idUser);

                if (!$userLama) {
                    $_SESSION['settings_error'] = 'Pengguna tidak ditemukan.';
                    header("Location: index.php?page=settings&action=users");
                    exit;
                }

                $this->userModel->update($idUser, [
                    'nip' => $nip,
                    'nama' => $nama,
                    'email' => $email,
                    'status' => $status,
                    'role' => $role
                ]);

                if ($password !== '') {
                    $this->userModel->updatePassword(
                        $idUser,
                        $password
                    );
                }

                if ($idUser === (int) $_SESSION['user_id']) {

                    if ($status === 'nonaktif') {

                        session_unset();
                        session_destroy();

                        header("Location: index.php?page=login");
                        exit;
                    }

                    $_SESSION['nama'] = $nama;
                    $_SESSION['nip'] = $nip;
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $role;
                }

                $_SESSION['settings_message'] =
                    'Data pengguna berhasil diperbarui.';

            } else {

                if ($password === '') {
                    $_SESSION['settings_error'] =
                        'Password wajib diisi saat menambah pengguna.';

                    header("Location: index.php?page=settings&action=users");
                    exit;
                }

                $this->userModel->create([
                    'nip' => $nip,
                    'nama' => $nama,
                    'email' => $email,
                    'password' => $password,
                    'status' => $status,
                    'role' => $role
                ]);

                $_SESSION['settings_message'] =
                    'Pengguna berhasil ditambahkan.';
            }

        } catch (PDOException $e) {

            if ($e->getCode() === '23000') {

                $_SESSION['settings_error'] =
                    'NIP atau email sudah digunakan oleh pengguna lain.';

            } else {

                $_SESSION['settings_error'] =
                    'Terjadi kesalahan saat menyimpan pengguna.';
            }
        }

        header("Location: index.php?page=settings&action=users");
        exit;
    }

    public function deleteUser()
    {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=settings&action=users");
            exit;
        }

        $idUser = (int) ($_POST['id_user'] ?? 0);

        if ($idUser <= 0) {
            $_SESSION['settings_error'] =
                'Pengguna yang akan dihapus tidak valid.';

            header("Location: index.php?page=settings&action=users");
            exit;
        }

        try {

            $user = $this->userModel->getById($idUser);

            if (!$user) {
                $_SESSION['settings_error'] =
                    'Pengguna tidak ditemukan.';

                header("Location: index.php?page=settings&action=users");
                exit;
            }

            $namaUser = $user['nama'];

            $berhasil = $this->userModel->delete($idUser);

            if (!$berhasil) {
                $_SESSION['settings_error'] =
                    'Pengguna gagal dihapus.';

                header("Location: index.php?page=settings&action=users");
                exit;
            }

            if ($idUser === (int) $_SESSION['user_id']) {

                session_unset();
                session_destroy();

                header("Location: index.php?page=login");
                exit;
            }

            $_SESSION['settings_message'] =
                'Pengguna "' . $namaUser . '" berhasil dihapus.';

        } catch (PDOException $e) {

            $_SESSION['settings_error'] =
                'Pengguna tidak dapat dihapus karena masih memiliki data yang terkait.';
        }

        header("Location: index.php?page=settings&action=users");
        exit;
    }

    public function profile()
    {
        $this->checkAdmin();

        require __DIR__ . '/../views/settings/profile.php';
    }

    public function kategori()
{
    $this->checkAdmin();

    header("Location: index.php?page=kategori");
    exit;
}
    public function gudang()
    {
        $this->checkAdmin();

        require __DIR__ . '/../views/settings/gudang.php';
    }

    public function notifikasi()
    {
        $this->checkAdmin();

        require __DIR__ . '/../views/settings/notifikasi.php';
    }
}