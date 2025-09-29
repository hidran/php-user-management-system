<?php

declare(strict_types=1);
session_start();
require '../functions.php';

$userName = post_string('username', 60);
$email = cleanEmail('email');
$conn = getConnection();
dd(find_user_by_email($conn, $email));
$password = post_string('password', 255);
