<?php

function verify_signup(mysqli $conn, string $email, string $password, $username): array
{
    $res = ['success' => true, 'message' => ''];
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
