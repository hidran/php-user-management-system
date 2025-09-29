<?php

declare(strict_types=1);
$orderBy = $orderBy ?? 'ASC';
$recordsPerPage = $recordsPerPage ?? 10;
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$currentOrderDir = $currentOrderDir ?? 'DESC';
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
