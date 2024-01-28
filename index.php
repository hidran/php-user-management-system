<?php
// for dev purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'functions.php';
require_once 'view/top.php';
require_once 'view/nav.php';
?>

<!-- Begin page content -->
<main class='flex-shrink-0'>
    <div class='container'>
        <h1>USER MANAGEMENT SYSTEM</h1>
        <?php
        $page = $_SERVER['PHP_SELF'];
        $action = getParam('action');
        switch ($action) {


            default:

                $orderByColumns = getConfig('orderByColumns', []);
                $orderBy = getParam('orderBy', 'id');
                $orderDir = getParam('orderDir', 'ASC');
                if (!in_array($orderDir, ['ASC', 'DESC'])) {
                    $orderDir = 'ASC';
                }
                $orderBy = in_array($orderBy, $orderByColumns) ? $orderBy : null;
                $recordsPerPage = getConfig('recordsPerPage', 10);
                $params = [
                    'orderBy' => $orderBy,
                    'recordsPerPage' => $recordsPerPage,
                    'orderDir' => $orderDir
                ];
                $users = getUsers($params);
                $orderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';
                require 'view/userList.php';
                break;
        }
        ?>

    </div>
</main>

<?php
require_once 'view/footer.php';
