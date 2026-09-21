<?php

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: index.php?page=dashboard");
    exit;
}

$users = $users ?? [];
$editUser = $editUser ?? null;
$message = $message ?? '';
$error = $error ?? '';

function statusClass($status)
{
    return $status === 'aktif' ? 'status-aktif' : 'status-nonaktif';
}

function roleClass($role)
{
    return $role === 'admin' ? 'role-admin' : 'role-user';
}
?>

<link rel="stylesheet" href="public/css/users.css">

<div class="users-page">

    <div class="users-header">

        <div class="users-header-left">

            <a href="index.php?page=settings" class="btn-back">
                ← Kembali
            </a>

            <div>
                <h1>Kelola Pengguna</h1>
                <p>Tambah, edit, dan kelola pengguna sistem.</p>
            </div>

        </div>

        <button type="button" class="btn-add-user" onclick="openAddModal()">
            + Tambah Pengguna
        </button>

    </div>

    <?php if ($message !== ''): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <?php if ($error !== ''): ?>

        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <div class="users-card">

        <div class="users-card-header">

            <div>
                <h2>Daftar Pengguna</h2>
                <span><?= count($users) ?> pengguna</span>
            </div>

            <div class="search-user">

                <input
                    type="text"
                    id="searchUser"
                    placeholder="Cari pengguna..."
                >

            </div>

        </div>

        <div class="table-wrapper">

            <table id="usersTable">

                <thead>

                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (empty($users)): ?>

                        <tr>
                            <td colspan="8" class="empty-users">
                                Belum ada pengguna.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($users as $index => $user): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <span class="nip">
                                        <?= htmlspecialchars($user['nip']) ?>
                                    </span>
                                </td>

                                <td>
                                    <strong class="nama-user">
                                        <?= htmlspecialchars($user['nama']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>

                                <td>

                                    <span class="badge <?= roleClass($user['role']) ?>">
                                        <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                    </span>

                                </td>

                                <td>

                                    <span class="badge <?= statusClass($user['status']) ?>">
                                        <?= ucfirst(htmlspecialchars($user['status'])) ?>
                                    </span>

                                </td>

                                <td>
                                    <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                </td>

                                <td>

                                  <div class="action-buttons">

    <button
        type="button"
        class="btn-edit"
        onclick='openEditModal(<?= json_encode($user) ?>)'
    >
        Edit
    </button>

 <form
    method="POST"
    action="index.php?page=settings&action=delete-user"
    onsubmit="return confirmDelete(event, '<?= htmlspecialchars($user['nama'], ENT_QUOTES) ?>')"
>
    <input
        type="hidden"
        name="id_user"
        value="<?= $user['id_user'] ?>"
    >

    <button type="submit" class="btn-delete">
        Hapus
    </button>
</form>

</div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<div class="modal-overlay" id="userModal">

    <div class="user-modal">

        <div class="modal-header">

            <div>
                <h2 id="modalTitle">Tambah Pengguna</h2>
                <p id="modalDescription">
                    Tambahkan pengguna baru ke sistem.
                </p>
            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeUserModal()"
            >
                ×
            </button>

        </div>

        <form
            method="POST"
            action="index.php?page=settings&action=save-user"
            id="userForm"
        >

            <input
                type="hidden"
                name="id_user"
                id="id_user"
                value=""
            >

            <div class="form-group">

                <label for="nip">
                    NIP
                </label>

                <input
                    type="text"
                    name="nip"
                    id="nip"
                    placeholder="Masukkan NIP"
                    required
                >

            </div>

            <div class="form-group">

                <label for="nama">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama"
                    id="nama"
                    placeholder="Masukkan nama lengkap"
                    required
                >

            </div>

            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="Masukkan email"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Masukkan password"
                >

                <small id="passwordHelp">
                    Wajib diisi saat menambah pengguna.
                </small>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label for="role">
                        Role
                    </label>

                    <select name="role" id="role" required>

                        <option value="user">
                            User
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                    </select>

                </div>

                <div class="form-group">

                    <label for="status">
                        Status
                    </label>

                    <select name="status" id="status" required>

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-cancel"
                    onclick="closeUserModal()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn-save"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="public/js/users.js"></script>