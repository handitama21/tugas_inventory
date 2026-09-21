<?php

class AuthController
{
    private $userModel;

    public function __construct($connect)
    {
        $this->userModel = new UserModel($connect);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nip = trim($_POST['nip'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($nip === '' || $password === '') {
                $error = "NIP dan password wajib diisi.";
                require __DIR__ . '/../views/auth/login.php';
                return;
            }

            $user = $this->userModel->getByNip($nip);

            if (!$user) {
                $error = "NIP atau password salah.";
                require __DIR__ . '/../views/auth/login.php';
                return;
            }

            if ($user['status'] !== 'aktif') {
                $error = "Akun kamu sedang tidak aktif.";
                require __DIR__ . '/../views/auth/login.php';
                return;
            }

            if (!password_verify($password, $user['password'])) {
                $error = "NIP atau password salah.";
                require __DIR__ . '/../views/auth/login.php';
                return;
            }

            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['nip'] = $user['nip'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php?page=dashboard");
            exit;
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        header("Location: index.php?page=login");
        exit;
    }
}