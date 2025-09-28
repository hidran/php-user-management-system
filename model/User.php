<?php

declare(strict_types=1);


function deleteUser(int $id): bool
{
    $conn = getConnection();
    $sql = 'DELETE FROM users WHERE id=' . $id;
    $res = $conn->query($sql);
    return $res && $conn->affected_rows;
}

function getUserById(int $id): array
{
    $conn = getConnection();
    $sql = 'SELECT * FROM users WHERE id =?';
    $stm = $conn->prepare($sql);
    $stm->bind_param('i', $id);
    $stm->execute();
    $result = $stm->get_result();
    $user = $result->fetch_assoc();
    $stm->close();
    return $user;
}

function updateUser(array $data, int $id): bool
{
    $conn = getConnection();
    $types = 'sssis';
    $values = [
        $data['username'],
        $data['email'],
        $data['fiscalcode'],
        $data['age'],
        $data['avatar']
    ];
    $sql = 'UPDATE users SET username = ?, email = ?, fiscalcode = ?, age = ?,avatar=? ';
    if ($data['password']) {
        $sql .= ', password = ? ';
        $types .= 's';
        $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
    }
    if ($data['role_type']) {
        $sql .= ', role_type = ? ';
        $types .= 's';
        $values[] = $data['role_type'];
    }
    $values[] = $id;
    $sql .= ' WHERE id = ?';
    $types .= 'i';
    $stm = $conn->prepare($sql);
    $stm->bind_param(
        $types,
        ...$values

    );
    $res = $stm->execute();

    $stm->close();
    return $res;
}

function storeUser(array $data): int
{
    $conn = getConnection();
    $sql = 'INSERT INTO users (username,email,fiscalcode,age,avatar, password,role_type) values( ?, ?, ?,?,?,?,?)';
    $stm = $conn->prepare($sql);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $stm->bind_param(
        'sssisss',
        $data['username'],
        $data['email'],
        $data['fiscalcode'],
        $data['age'],
        $data['avatar'],
        $password,
        $data['role_type']

    );
    $stm->execute();

    $stm->close();
    return $conn->insert_id;
}
