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
function insertRandUser($totale, mysqli $conn)
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
//insertRandUser(30, $mysqli);
function getUsers(array $params = [])
{

    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];

    $records = [];

    $limit = $params['recordsPerPage'] ?? 10;
    $orderBy = $params['orderBy'] ?? 'id';
    $orderDir = $params['orderDir'] ?? '';
    $search = $params['search'] ?? '';

    $sql = "SELECT * FROM users ORDER BY $orderBy $orderDir  LIMIT  0,$limit ";
    echo $sql;
    $res = $conn->query($sql);
    if ($res) {

        while ($row = $res->fetch_assoc()) {
            $records[] = $row;
        }
    }

    return $records;
}
function dd(mixed $data = null)
{
    var_dump($data);
    die;
}
//var_dump(getUsers());
