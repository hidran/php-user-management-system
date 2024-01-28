<?php
$params = "search=$search&recordsPerPage=$recordsPerPage&orderDir=$orderDir";
?>
<table class="table table-dark table-striped">
    <caption>USERS LIST</caption>
    <thead>
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