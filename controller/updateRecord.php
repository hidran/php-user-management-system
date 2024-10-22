<?php
session_start();
require '../functions.php';
require '../model/User.php';
$action = getParam('action');
switch ($action) {
    case 'delete':
        $id = (int)getParam('id', 0);
        $res = deleteUser($id);
        $message = $res? 'USER '. $id. ' DELETED'
        : 'ERROR DELETING USER ' . $id;
        $_SESSION['message'] = $message;
        $_SESSION['success'] = $res;
        $params = $_GET;
        unset($params['id'], $params['action']);
        $queryString = http_build_query($params);
        header('Location:../index.php?'.$queryString);
        break;
    case 'update':
        # code...
        break;
    default:
        # code...
        break;
}