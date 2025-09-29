<?php

declare(strict_types=1);
session_start();
require '../functions.php';
require_once '../includes/auth.php';
$conn = getConnection();
$email = cleanEmail('email');
$password = post_string('password', 255);
$res = verify_login($conn, $email, $password);
if (!$res['success']) {
    setFlashMessage($res['message'], 'danger');
    redirect('../login.php');
} else {
    setFlashMessage('Logged in correctly', 'success');
    start_session($res['user']);
    redirect('../index.php');
}
