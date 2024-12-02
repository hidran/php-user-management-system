<?php

$params = [
    'orderBy' => $orderBy,
    'recordsPerPage' => $recordsPerPage,
    'orderDir' => $currentOrderDir,
    'search' => $search,
    'page' => $currentPage
];
$totalRecords = getTotalUserCount($search);

$users = $totalRecords ? getUsers($params) : [];

$orderDirClass = $currentOrderDir;

$orderDir = $currentOrderDir === 'ASC' ? 'DESC' : 'ASC';

require 'view/userList.php';
