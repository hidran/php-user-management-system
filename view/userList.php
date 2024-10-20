<?php
$params = "search=$search&recordsPerPage=$recordsPerPage&orderBy=$orderBy";
$navParams = $params."&orderDir=$currentOrderDir";
$params .= "&orderDir=$orderDir";
$baseUrl = "$page?$params";
$navUrl ="$page?$navParams";
$totalPages = (int)ceil($totalRecords / $recordsPerPage);
?>
<table class="table table-dark table-striped">
    <caption>USERS LIST</caption>
    <thead>
        <tr>
            <th colspan="5" class="text-center text-bg-dark">
                <?= $totalRecords ?> RECORDS FOUND.
                PAGE <?=$currentPage?> of <?=$totalPages?>
            </th>
        </tr>
        <tr>
            <th class="<?= $orderBy === 'id' ? $orderDirClass : '' ?>">
                <a href="<?= $params ?>&orderBy=id">
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
                </tr>

            <?php
            }
            ?>
    <tfoot>
        <tr>
            <td style="vertical-align: middle;" class="align-items-center text-center" colspan="5">
                <?php
                require 'view/navigation.php';
                echo createPagination($totalRecords, $recordsPerPage, $currentPage, $navUrl,$maxLinks);
                ?>
            </td>
        </tr>
    </tfoot>
<?php

        } else { ?>
    <tr>
        <td class="text-center" colspan="5">
            <div class="alert alert-danger"> NO RECORDS FOUND</div>
        </td>
    </tr>
<?php
        }

?>
</tbody>
</table>