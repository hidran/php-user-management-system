<?php

$search = $search ?? '';
$orderBy = $orderBy ?? 'id';
$orderDir = $orderDir ?? 'DESC';
$recordsPerPage = $recordsPerPage ?? 10;
$currentPage = $currentPage ?? 1;
$totalRecords = getTotalUserCount($search);
$currentOrderDir = $currentOrderDir ?? 'ASC';
$page = $page ?? 1;
$params = "search=$search&recordsPerPage=$recordsPerPage&orderBy=$orderBy";
$navParams = $params . "&orderDir=$currentOrderDir";
$params .= "&orderDir=$orderDir";
$baseUrl = "$page?$params";
$navUrl = "$page?$navParams";
$totalPages = (int)ceil($totalRecords / $recordsPerPage);
$orderDirClass = $orderDirClass ?? '';
$users = $users ?? [];
$updateUrl = $updateUrl ?? '';
$maxLinks = $maxLinks ?? '5';

?>
<table class="table table-dark table-striped">
    <caption>USERS LIST</caption>
    <thead>
    <tr>
        <th colspan="8" class="text-center text-bg-dark">
            <?= $totalRecords ?> RECORDS FOUND.
            PAGE <?= $currentPage ?> of <?= $totalPages ?>
        </th>
    </tr>
    <tr>
        <th class="<?= $orderBy === 'id' ? $orderDirClass : '' ?>">
            <a href="?<?= $params ?>&orderBy=id">
                ID
            </a>
        </th>
        <th class="<?= $orderBy === 'username' ? $orderDirClass : '' ?>">
            <a href="?<?= $params ?>&orderBy=username">
                NAME
            </a>
        </th>
        <th class="<?= $orderBy === 'fiscalcode' ? $orderDirClass : '' ?>">
            <a href="?<?= $params ?>&orderBy=fiscalcode">
                FISCAL CODE
            </a>
        </th>
        <th class="<?= $orderBy === 'email' ? $orderDirClass : '' ?>">
            <a href="?<?= $params ?>&orderBy=email">
                EMAIL
            </a>
        </th>
        <th class="<?= $orderBy === 'age' ? $orderDirClass : '' ?>">
            <a href="?<?= $params ?>&orderBy=age">
                AGE
            </a>
        </th>
        <th class="<?= $orderBy === 'role_type' ? $orderDirClass : '' ?>">
            <a href="?<?= $params ?>&orderBy=role_type">
                ROLE TYPE
            </a>
        </th>
        <th>Avatar</th>
        <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($users) {
    foreach ($users as $user) { ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['username'] ?></td>
            <td><?= $user['fiscalcode'] ?></td>
            <td><a href="mailto:<?= $user['email'] ?>"> <?= $user['email'] ?></a></td>
            <td><?= $user['age'] ?></td>
            <td><?= $user['role_type'] ?></td>
            <td><?php
                if ($user['avatar']) {
                    $fileData = getImgThumbNail($user['avatar']);
                    if ($fileData['avatar']) {
                        ?>
                        <img width="<?= $fileData['width'] ?>" src="<?= $fileData['avatar'] ?>" alt="avatar">
                        <?php
                    }
                }
                ?>
            </td>
            <td>
                <?php
                if (user_can_update()): ?>
                    <div class="row">

                        <div class="col-6">
                            <a class="btn btn-success" href="?id=<?= $user['id'] ?>&action=edit&<?= $navParams ?>">
                                <i class="fa fa-pen"></i>
                                UPDATE
                            </a>
                        </div>
                        <?php
                        if (user_can_update()): ?>
                            <div class="col-6">
                                <a onclick="return confirm('DELETE USER?')" class="btn btn-danger"
                                   href="<?= $updateUrl ?>?id=<?= $user['id'] ?>&action=delete&<?= $navParams ?>">
                                    <i class="fa fa-trash"></i>
                                    DELETE
                                </a>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                <?php
                endif;
                ?>
            </td>
        </tr>

        <?php
    }
    ?>
    <tfoot>
    <tr>
        <td style="vertical-align: middle;" class="align-items-center text-center" colspan="8">
            <?php
            require 'view/navigation.php';
            echo createPagination($totalRecords, $recordsPerPage, $currentPage, $navUrl, $maxLinks);
            ?>
        </td>
    </tr>
    </tfoot>
    <?php
    } else { ?>
        <tr>
            <td class="text-center" colspan="8">
                <div class="alert alert-danger"> NO RECORDS FOUND</div>
            </td>
        </tr>
        <?php
    }

    ?>
    </tbody>
</table>
