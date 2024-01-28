<?php
// for dev purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'functions.php';
$page = $_SERVER['PHP_SELF'];
//records per page
$recordsPerPageOptions = getConfig('recordsPerPageOptions', [5, 10, 20]);
$recordsPerPageDefault = getConfig('recordsPerPage', 10);
$recordsPerPage = (int)getParam('recordsPerPage', $recordsPerPageDefault);
//search
$search = getParam('search', '');
$search = strip_tags(trim($search));
//order by
$orderByColumns = getConfig('orderByColumns', []);
$orderBy = getParam('orderBy', 'id');
$orderDir = getParam('orderDir', 'ASC');
if (!in_array($orderDir, ['ASC', 'DESC'])) {
    $orderDir = 'ASC';
}
$orderBy = in_array($orderBy, $orderByColumns) ? $orderBy : null;

require_once 'view/top.php';
require_once 'view/nav.php';
?>

<!-- Begin page content -->
<main class='flex-shrink-0'>
    <div class='container'>
        <h1>USER MANAGEMENT SYSTEM</h1>
        <?php

        $action = getParam('action');
        switch ($action) {


            default:


                $params = [
                    'orderBy' => $orderBy,
                    'recordsPerPage' => $recordsPerPage,
                    'orderDir' => $orderDir,
                    'search' => $search
                ];
                $users = getUsers($params);

                $orderDirClass = $orderDir;

                $orderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';
                require 'view/userList.php';
                break;
        }
        ?>

    </div>
</main>

<?php
require_once 'view/footer.php';
