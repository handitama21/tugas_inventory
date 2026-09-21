<?php

class UserModel
{
    private $connect;
    private $table = "users";

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    public function getByNip($nip)
    {
        $query = "SELECT * FROM {$this->table} WHERE nip = :nip LIMIT 1";

        $stmt = $this->connect->prepare($query);
        $stmt->execute([
            ':nip' => $nip
        ]);

        return $stmt->fetch();
    }

    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id_user = :id LIMIT 1";

        $stmt = $this->connect->prepare($query);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch();
    }

    public function getAll()
    {
        $query = "SELECT id_user, nip, nama, email, status, role, created_at
                  FROM {$this->table}
                  ORDER BY id_user DESC";

        $stmt = $this->connect->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $query = "INSERT INTO {$this->table}
                  (nip, nama, email, password, status, role)
                  VALUES
                  (:nip, :nama, :email, :password, :status, :role)";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nip' => $data['nip'],
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':status' => $data['status'],
            ':role' => $data['role']
        ]);
    }

    public function update($id, $data)
    {
        $query = "UPDATE {$this->table}
                  SET nip = :nip,
                      nama = :nama,
                      email = :email,
                      status = :status,
                      role = :role
                  WHERE id_user = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':nip' => $data['nip'],
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            ':status' => $data['status'],
            ':role' => $data['role'],
            ':id' => $id
        ]);
    }

    public function updatePassword($id, $password)
    {
        $query = "UPDATE {$this->table}
                  SET password = :password
                  WHERE id_user = :id";

        $stmt = $this->connect->prepare($query);

        return $stmt->execute([
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }public function delete($id)
{
    $query = "DELETE FROM {$this->table}
              WHERE id_user = :id";

    $stmt = $this->connect->prepare($query);

    return $stmt->execute([
        ':id' => $id
    ]);
}
}