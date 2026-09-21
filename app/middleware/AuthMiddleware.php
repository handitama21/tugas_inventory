<?php

class AuthMiddleware
{
    public static function check($connect)
    {
        if (!isset($_SESSION['user_id'])) {
            if (($_GET['page'] ?? 'login') !== 'login') {
                header("Location: index.php?page=login");
                exit;
            }

            return;
        }

        $idUser = (int) $_SESSION['user_id'];

        $query = "SELECT
                    id_user,
                    nama,
                    nip,
                    email,
                    role,
                    status
                  FROM users
                  WHERE id_user = :id
                  LIMIT 1";

        $stmt = $connect->prepare($query);

        $stmt->execute([
            ':id' => $idUser
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            session_unset();
            session_destroy();

            header("Location: index.php?page=login");
            exit;
        }

        if ($user['status'] !== 'aktif') {
            session_unset();
            session_destroy();

            header("Location: index.php?page=login");
            exit;
        }

        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['nip'] = $user['nip'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
    }
}