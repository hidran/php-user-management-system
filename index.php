<?php
// for dev purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'functions.php';
$page =$pageUrl= $_SERVER['PHP_SELF'];
$updateUrl = 'controller/updateRecord.php';
//records per page
$recordsPerPageOptions = getConfig('recordsPerPageOptions', [5, 10, 20]);
$recordsPerPageDefault = getConfig('recordsPerPage', 10);
//order by
$orderByColumns = getConfig('orderByColumns', []);
$maxLinks = getConfig('maxLinks', 10);

$recordsPerPage = (int)getParam('recordsPerPage', $recordsPerPageDefault);
//search
$search = getParam('search', '');
$search = strip_tags(trim($search));

$orderBy = getParam('orderBy', 'id');
$currentOrderDir = getParam('orderDir', 'ASC');
$currentPage = getParam('page', 1);
if (!in_array($currentOrderDir, ['ASC', 'DESC'])) {
    $currentOrderDir = 'ASC';
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
        require_once 'controller/displayUsers.php';
        ?>

    </div>
</main>

<?php
require_once 'view/footer.php';
