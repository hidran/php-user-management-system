<?php
session_start();
require_once 'connection.php';
// for dev purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'functions.php';
$page = $pageUrl = $_SERVER['PHP_SELF'];
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
$currentOrderDir = getParam('orderDir', 'DESC');
$currentPage = getParam('page', 1);
if (!in_array($currentOrderDir, ['ASC', 'DESC'])) {
    $currentOrderDir = 'DESC';
}
$orderBy = in_array($orderBy, $orderByColumns) ? $orderBy : null;

require_once 'view/top.php';
require_once 'view/nav.php';
?>

<!-- Begin page content -->
<main class='flex-shrink-0'>
	<div class='container text-center'>
		<h1>USER MANAGEMENT SYSTEM</h1>
		<?php
          showSessionMsg();
$action = getParam('action');
switch ($action) {
    case 'edit':
        require_once 'model/User.php';
        $id = getParam('id');
        $user = getUserById($id);
        require_once 'view/userForm.php';
        break;
            case 'insert':
               
               
                $user = [
                    'avatar' => '',
                    'username'=> '',
                    'email' => '',
                    'fiscalcode' => '',
                    'age' => 0,
                    'id'=> 0

                ];
                require_once 'view/userForm.php';
                break;
    default:
        require_once 'controller/displayUsers.php';
        break;
}

?>

	</div>
</main>

<?php
require_once 'view/footer.php';
?>