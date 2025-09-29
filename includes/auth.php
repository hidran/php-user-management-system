<?php

declare(strict_types=1);
function start_session(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_date'] = $user;
    $_SESSION['user_logged_in'] = true;
}

function verify_login(mysqli $conn, string $email, string $password, string $token): array
{
    $res = ['success' => true, 'message' => ''];
    if (!csrf_validate($token)) {
        $res['success'] = false;
        $res['message'] = 'Invalid token';
        return $res;
    }
    if (!validatePassword($password) || !verifyEmail($email)) {
        $res['success'] = false;
        $res['message'] = 'Invalid email or password';
        return $res;
    }
    $user = find_user_by_email($conn, $email);

    if (!$user || !password_verify($password, $user['password'])) {
        $res['success'] = false;
        $res['message'] = 'Wrong password or user doesn´t exist';
        return $res;
    }
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        update_password_hash($conn, $user['id'], password_hash($password, PASSWORD_DEFAULT));
    }
    unset($user['password']);
    $res['user'] = $user;
    return $res;
}

function update_password_hash(mysqli $conn, int $id, string $password_hash): void
{
    $st = $conn->prepare('UPDATE users set password=? WHERE id=?');
    $st->bind_param('si', $password_hash, $id);
    $st->execute();
    $st->close();
}

function verify_signup(mysqli $conn, string $email, string $password, $username, string $token): array
{
    $res = ['success' => true, 'message' => ''];
    if (!csrf_validate($token)) {
        $res['success'] = false;
        $res['message'] = 'Invalid token';
        return $res;
    }
    if (!validateUserName($username)) {
        $res['success'] = false;
        $res['message'] = 'Invalid user name';
        return $res;
    }

    if (!validatePassword($password) || !verifyEmail($email)) {
        $res['success'] = false;
        $res['message'] = 'Invalid email or password';
        return $res;
    }
    if (find_user_by_email($conn, $email)) {
        $res['success'] = false;
        $res['message'] = 'User already exists';
        return $res;
    }
    return $res;
}
