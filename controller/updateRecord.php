<?php 
require '../functions.php';
require '../model/User.php';
$action = getParam('action');
switch ($action) {
    case 'delete':
        $id = (int)getParam('id', 0);
        $res = deleteUser($id);
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