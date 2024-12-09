<?php

$params = [
    'orderBy' => $orderBy,
    'recordsPerPage' => $recordsPerPage ?? 10,
    'orderDir' => $currentOrderDir,
    'search' => $search,
    'page' => $currentPage ?? 1
];
$totalRecords = getTotalUserCount($search);

$users = $totalRecords ? getUsers($params) : [];

$orderDirClass = $currentOrderDir;

$orderDir = $currentOrderDir === 'ASC' ? 'DESC' : 'ASC';

require 'view/userList.php';
