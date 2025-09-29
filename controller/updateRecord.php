<?php

declare(strict_types=1);
require_once '../includes/session.php';
require '../functions.php';
require_once '../includes/acl.php';
if (!is_user_logged_in() || !user_can_update()) {
    redirect('../login.php');
}

require '../model/User.php';
$action = getParam('action');
switch ($action) {
    case 'delete':
        if (!user_can_delete()) {
            redirect('../login.php');
        }

        $id = (int)getParam('id', 0);
        $user = getUserById($id);
        if (!$user) {
            setFlashMessage('USER NOT FOUND');
            redirectWithParams();
        }

        $res = deleteUser($id);
        if ($res && $user && $user['avatar']) {
            deleteUserImages($user['avatar']);
        }
        $message = $res ? 'USER ' . $id . ' DELETED'
            : 'ERROR DELETING USER ' . $id;
        $_SESSION['message'] = $message;
        $_SESSION['messageType'] = $res ? 'success' : 'danger';
        $params = $_GET;
        unset($params['id'], $params['action']);
        $queryString = http_build_query($params);
        header('Location:../index.php?' . $queryString);
        break;
    case 'update':
        $id = (int)$_POST['id'];
        $avatarPath = $_POST['oldAvatar'];
        $userData = [
            'id' => $id,
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'fiscalcode' => trim($_POST['fiscalcode']),
            'age' => (int)$_POST['age'],
            'role_type' => $_POST['role_type'] ?? 'user',
            'password' => $_POST['password']

        ];
        $errors = validateUserData($userData);
        if ($errors) {
            setFlashMessage(implode(',', $errors));
            redirectWithParams();
        }


        if ($_FILES['avatar']['name']) {
            $fileErrors = validateFileUpload($_FILES['avatar']);

            if (!empty($fileErrors)) {
                setFlashMessage(implode('<br>', $fileErrors));
                redirectWithParams();
            }
            $res = handleAvatarUpload($_FILES['avatar'], $id);
            if ($res) {
                deleteUserImages($avatarPath);
                $avatarPath = $res;
                createThumbnailAndIntermediate($avatarPath);
            }
        }
        $userData['avatar'] = $avatarPath;
        // dd($userData['fiscalcode'], strlen($userData['fiscalcode']));
        $res = updateUser($userData, $id);
        $message = $res ? 'USER ' . $id . ' UPDATED'
            : 'ERROR UPDATING USER ' . $id;
        $_SESSION['message'] = $message;
        $_SESSION['messageType'] = $res ? 'success' : 'danger';
        $params = $_GET;
        unset($params['id'], $params['action']);
        $queryString = http_build_query($params);
        header('Location:../index.php?' . $queryString);
        break;

    case 'store':


        $userData = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'fiscalcode' => trim($_POST['fiscalcode']),
            'age' => (int)$_POST['age'],
            'role_type' => $_POST['role_type'] ?? 'user',
            'password' => $_POST['password'],
            'avatar' => null
        ];
        $errors = validateUserData($userData);
        if ($errors) {
            setFlashMessage(implode(',', $errors));
            redirectWithParams();
        }
        $avatarPath = '';
        if ($_FILES['avatar']['name'] && is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            $avatarPath = handleAvatarUpload($_FILES['avatar']);
            if ($avatarPath) {
                createThumbnailAndIntermediate($avatarPath);
            }
        }
        $userData['avatar'] = $avatarPath;
        $res = storeUser($userData);

        $message = $res ? 'USER ' . $res . ' CREATED'
            : 'ERROR CREATING USER ';
        $_SESSION['message'] = $message;
        $_SESSION['messageType'] = $res ? 'success' : 'danger';
        $params = $_GET;
        unset($params['action']);
        $queryString = http_build_query($params);
        header('Location:../index.php?' . $queryString);
        break;
    default:
        # code...
        break;
}
