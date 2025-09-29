<?php

declare(strict_types=1);
require_once '../includes/session.php';
require '../functions.php';
require_once '../includes/auth.php';
require_once '../includes/csrf.php';
require_once '../model/User.php';
$redirectUrl = '../login.php';
$userName = post_string('username', 60);
$email = cleanEmail('email');
$conn = getConnection();
$password = post_string('password', 255);

$result = verify_signup($conn, $email, $password, $userName, csrf_token());
if (!$result['success']) {
    setFlashMessage($result['message'], 'danger');
    redirect($redirectUrl);
} else {
    $data['username'] = $userName;
    $data['email'] = $email;
    $data['fiscalcode'] = getRandFiscalCode();
    $data['age'] = 0;
    $data['avatar'] = '';
    $data['password'] = $password;
    $data['role_type'] = 'user';
    try {
        $res = storeUser($data);
        if ($res) {
            setFlashMessage('User signed up correctly');
            redirect($redirectUrl);
        } else {
            setFlashMessage('Error signing up user');
            redirect($redirectUrl);
        }
    } catch (Exception $e) {
        setFlashMessage($e->getMessage());
        redirect($redirectUrl);
    }
}

