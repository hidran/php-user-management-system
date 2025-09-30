<?php

declare(strict_types=1);
function start_session(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_data'] = $user;
    $_SESSION['user_logged_in'] = true;
}

function base64url_encode(string $bin): string
{
    $res = base64_encode($bin);
    $res = strtr($res, '+/', '-_');
    $res = rtrim($res, '=');
    return $res;
}

function saveRememberMe(mysqli $conn, int $userId): bool
{
    $selector = base64url_encode(random_bytes(12));
    $token = base64url_encode(random_bytes(33));
    $tokenHash = hash('sha256', $token);
    $ttl = getConfig('rememberMeTTL');
    $expiresAt = (new DateTimeImmutable('+' . $ttl . ' seconds'))->format('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = 'INSERT INTO remember_tokens (user_id, token_hash, selector, expires_at, ip_address) VALUES (?,?,?,?,?)';
    $st = $conn->prepare($sql);
    $st->bind_param('issss', $userId, $tokenHash, $selector, $expiresAt, $ip);
    $res = $st->execute();
    $st->close();
    return $res;
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
