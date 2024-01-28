<table class="table table-dark table-striped">
    <caption>USERS LIST</caption>
    <thead>
        <tr>
            <th> <a href="?orderBy=id">ID</a></th>
            <th><a href="?orderBy=username">NAME</a></th>
            <th><a href="?orderBy=fiscalcode">FISCAL CODE</a></th>
            <th><a href="?orderBy=email">EMAIL</a> </th>
            <th><a href="?orderBy=age">AGE</a></th>
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