<?php

session_start();
require '../functions.php';
require '../model/User.php';
$action = getParam('action');
switch ($action) {
    case 'delete':
        $id = (int)getParam('id', 0);
        $res = deleteUser($id);
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
            'age' => (int)$_POST['age']

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
                $avatarPath = $res;
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
