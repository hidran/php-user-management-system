<?php

require_once 'connection.php';
function getConfig($param, $default = null)
{

    $config = require 'config.php';

    return  $config[$param] ?? $default;
}
function getParam($param, $default = '')
{

    return $_REQUEST[$param] ?? $default;
}
function getRandName(): string
{
    $names = [
        'ROBERTO', 'GIOVANNI', 'GIULIA', 'MARIO', 'ALE'
    ];
    $lastnames = [
        'ROSSI', 'RE', 'ARIAS', 'SMITH', 'MENDOZA', 'CRUZ', 'WILDE'

    ];

    $rand1 =  random_int(0, count($names) - 1);
    $rand2 =  random_int(0, count($lastnames) - 1);

    return  $names[$rand1] . ' ' . $lastnames[$rand2];
}

//echo getRandName();
function getRandEmail(string $name): string
{

    $domains = ['google.com', 'yahoo.com', 'hotmail.it', 'libero.it'];

    $rand1 =  random_int(0, count($domains) - 1);

    return  strtolower(str_replace(' ', '.', $name) . random_int(10, 99) . '@' . $domains[$rand1]);
}
function getRandFiscalCode(): string
{

    $i = 16;
    $res = '';  // ABQZ

    while ($i > 0) {

        $res .= chr(random_int(65, 90));

        $i--;
    }
    return $res;
}
function getRandomAge(): int
{
    return random_int(0, 120);
}
function insertRandUser($totale, mysqli $conn): void
{

    while ($totale > 0) {

        $username = getRandName();
        $email = getRandEmail($username);
        $fiscalcode = getRandFiscalCode();
        $age = getRandomAge();

        $sql = 'INSERT INTO users (username, email, fiscalcode, age) VALUES ';
        $sql .= " ('$username','$email', '$fiscalcode', $age) ";
        echo $totale . ' ' . $sql . '<br>';
        $res = $conn->query($sql);
        if (!$res) {
            echo $conn->error . '<br>';
        } else {
            $totale--;
        }
    }
}

/**
 * @var \Mysqli $mysqli
 */
//insertRandUser(300, $mysqli);
function getUsers(array $params = []): array
{

    /**
     * @var $conn mysqli
     */

    $conn = getConnection();

    $records = [];

    $limit = $params['recordsPerPage'] ?? 10;
    $orderBy = $params['orderBy'] ?? 'id';
    $orderDir = $params['orderDir'] ?? 'DESC';
    $search = $params['search'] ?? '';
    $page = $params['page'] ?? 1;
    $start = $limit * ($page - 1);
    $sql = 'SELECT * FROM users';
    if ($search) {
        $sql .= ' WHERE';
        if (is_numeric($search)) {
            $sql .= " (id = $search OR age = $search)";
        } else {
            $sql .= " (fiscalcode like '%$search%' OR email like '%$search%' OR
             username like '%$search%'
            )";
        }
    }

    $sql .= " ORDER BY $orderBy $orderDir  LIMIT  $start,$limit ";
     // echo $sql;
    $res = $conn->query($sql);
    if ($res) {

        while ($row = $res->fetch_assoc()) {
            $records[] = $row;
        }
    }

    return $records;
}

function getTotalUserCount(string $search = ''): int
{

    /**
     * @var $conn mysqli
     */

    $conn = getConnection();


    $sql = 'SELECT COUNT(*) as total FROM users';
    if ($search) {
        $sql .= ' WHERE';
        if (is_numeric($search)) {
            $sql .= " id = $search OR age = $search";
        } else {
            $search = $conn->real_escape_string($search);
            $sql .= " fiscalcode like '%$search%' OR email like '%$search%' OR
             username like '%$search%'";
        }
    }


    //echo $sql;
    $res = $conn->query($sql);
    if ($res && $row = $res->fetch_assoc()) {

        return (int) $row['total'];
    }

    return 0;
}

function dd(mixed ...$data )
{
    var_dump($data);
    die;
}
function showSessionMsg(){
    if (!empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        $alertType = $_SESSION['messageType'];
        unset($_SESSION['messageType']);
        require_once 'view/message.php';
    }
}
