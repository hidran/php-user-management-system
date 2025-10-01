<?php

declare(strict_types=1);
function start_session(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_data'] = $user;
    $_SESSION['user_logged_in'] = true;
}

function tryAutoLogin(): void
{
    if (!empty($_SESSION['user_logged_in'])) {
        return;
    }
    $cookieName = getConfig('rememberMeCookieName');

    $cookie = $_COOKIE[$cookieName] ?? '';

    if (!$cookie || !str_contains($cookie, ':')) {
        return;
    }
    [$selector, $token] = explode(':', $cookie);

    $conn = getConnection();
    $st = $conn->prepare(
        'SELECT  t.id,t.expires_at,t.token_hash, u.id as uid, u.email, u.username, u.role_type FROM remember_tokens as t INNER JOIN users as u ON t.user_id=u.id WHERE selector=?'
    );
    $st->bind_param('s', $selector);
    $res = $st->execute();
    if (!$res) {
        $st->close();
        clearRememberMe();
        return;
    }
    $row = $st->get_result()->fetch_assoc();
    if (new DateTimeImmutable($row['expires_at']) <= new DateTimeImmutable('now')) {
        deleteRememberTokenById($row['id']);
        clearRememberMe();
    }
    $calcHash = hash('sha256', $token);
    if (!hash_equals($row['token_hash'], $calcHash)) {
        deleteRememberTokenById($row['id']);
        clearRememberMe();
        return;
    }
    session_regenerate_id(true);
    $_SESSION['user_data'] = [
        'id' => (int)$row['uid'],
        'email' => $row['email'],
        'username' => $row['username'],
        'role_type' => $row['role_type'],
    ];
    $_SESSION['user_logged_in'] = true;
    rotateRememberToken($conn, $row['id']);
}

function rotateRememberToken(mysqli $conn, int $id): void
{
    $token = base64url_encode(random_bytes(33));
    $tokenHash = hash('sha256', $token);
    $ttl = getConfig('rememberMeTTL');
    $expiresAt = (new DateTimeImmutable('+' . $ttl . ' seconds'))->format('Y-m-d H:i:s');
    $sql = 'SELECT selector FROM remember_tokens WHERE id=?';
    $st = $conn->prepare($sql);
    $st->bind_param('i', $id);
    $res = $st->execute();
    if (!$res) {
        return;
    }
    $row = $st->get_result()->fetch_assoc();
    $selector = $row['selector'];
    $sql = 'UPDATE remember_tokens SET token_hash=?, expires_at=? WHERE id=?';
    $st = $conn->prepare($sql);
    $st->bind_param('sss', $tokenHash, $expiresAt, $id);
    $st->execute();
    $st->close();
    $newToken = $selector . ':' . $token;
    $cookieName = getConfig('rememberMeCookieName');
    $cookieOptions = getRememberCookieOpts();
    setcookie($cookieName, $newToken, $cookieOptions);
}

function revokeAllRememberMeTokens(int $userId): void
{
    $conn = getConnection();
    $st = $conn->prepare('DELETE FROM remember_tokens WHERE user_id=?');
    $st->bind_param('i', $userId);
    $st->execute();
    $st->close();
}

function getCookieRememberMeSelector(): string
{
    $cookieName = getConfig('rememberMeCookieName');
    $cookie = $_COOKIE[$cookieName] ?? '';
    if (!$cookie || !str_contains($cookie, ':')) {
        return '';
    }
    [$selector,] = explode(':', $cookie);
    return $selector;
}

function revokeDeviceRememberMeToken(int $userId): void
{
    $conn = getConnection();
    $st = $conn->prepare('DELETE FROM remember_tokens WHERE user_id=? and selector=?');
    $selector = getCookieRememberMeSelector();
    $st->bind_param('is', $userId, $selector);
    $st->execute();
    $st->close();
}


function deleteRememberTokenById(int $id): void
{
    $conn = getConnection();
    $st = $conn->prepare('DELETE FROM remember_tokens WHERE id=?');
    $st->bind_param('i', $id);
    $st->execute();
    $st->close();
}

function clearRememberMe(): void
{
    $cookieName = getConfig('rememberMeCookieName');
    $cookieOptions = getRememberCookieOpts();
    $cookieOptions['expires'] = time() - 3600;
    setcookie($cookieName, '', $cookieOptions);
}

function base64url_encode(string $bin): string
{
    $res = base64_encode($bin);
    $res = strtr($res, '+/', '-_');
    $res = rtrim($res, '=');
    return $res;
}

function getRememberCookieOpts(): array
{
    $ttl = getConfig('rememberMeTTL');
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    return [

        'expires' => time() + $ttl,
        'path' => '/',
        'domain' => '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Strict'
    ];
}

function saveRememberMe(mysqli $conn, int $userId): bool
{
    $selector = base64url_encode(random_bytes(12));
    $token = base64url_encode(random_bytes(33));
    $tokenHash = hash('sha256', $token);
    $ttl = getConfig('rememberMeTTL');
    $expiresAt = (new DateTimeImmutable('+' . $ttl . ' seconds'))->format('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = mb_substr($_SERVER['HTTP_USER_AGENT'], 0, 255);
    $sql = 'INSERT INTO remember_tokens (user_id, token_hash, selector, expires_at, ip_address, user_agent) VALUES (?,?,?,?,?,?)';
    $st = $conn->prepare($sql);
    $st->bind_param('isssss', $userId, $tokenHash, $selector, $expiresAt, $ip, $userAgent);
    $res = $st->execute();
    $st->close();
    if (!$res) {
        return false;
    }
    $value = $selector . ':' . $token;
    $cookieName = getConfig('rememberMeCookieName');

    $cookieOptions = getRememberCookieOpts();
    setcookie($cookieName, $value, $cookieOptions);

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
