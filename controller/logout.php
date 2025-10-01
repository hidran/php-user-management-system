<?php

declare(strict_types=1);
require_once '../includes/session.php';
require_once '../functions.php';
require_once '../includes/auth.php';
require_once '../includes/csrf.php';
require_once '../includes/acl.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(419);
    exit('Invalid request method');
}
$fromAll = getParam('fromAll');
if (!csrf_validate(post_string('csrf_token'))) {
    http_response_code(419);
    exit('Invalid token');
}
clearRememberMe();
if ($fromAll) {
    revokeAllRememberMeTokens(get_user_id());
} else {
    revokeDeviceRememberMeToken(get_user_id());
}
$_SESSION = [];
$p = session_get_cookie_params();
setcookie(session_name(), '', time() - 4200, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
session_destroy();

redirect('/login.php');
