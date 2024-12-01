<?php

return [
    'mysql_host' => 'db',
    'mysql_user' => 'root',
    'mysql_password' => 'hidran',
    'mysql_db' => 'corsophp',
    'recordsPerPage' => 10,
    'maxLinks' => 10,
    'orderByColumns' =>
    ['id', 'username', 'fiscalcode', 'age', 'email'],
    'recordsPerPageOptions' =>
    [
        5, 10, 15, 20, 50, 100
    ],
    'uploadDir' => 'avatar',
    'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
    'maxFileSize' => 2*1024*1024
];
