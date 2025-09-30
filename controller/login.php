<?php

declare(strict_types=1);
require_once '../includes/session.php';
require '../functions.php';
require_once '../includes/auth.php';
require_once '../includes/csrf.php';

$conn = getConnection();
$email = cleanEmail('email');
$password = post_string('password', 255);
$remember = (int)post_string('remember');
$res = verify_login($conn, $email, $password, csrf_token());
if (!$res['success']) {
    setFlashMessage($res['message'], 'danger');
    redirect('../login.php');
} else {
    if ($remember) {
        saveRememberMe($conn, $res['user']['id']);
    }
    setFlashMessage('Logged in correctly', 'success');
    start_session($res['user']);
    redirect('../index.php');
}
