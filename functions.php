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
        'ROBERTO',
        'GIOVANNI',
        'GIULIA',
        'MARIO',
        'ALE'
    ];
    $lastnames = [
        'ROSSI',
        'RE',
        'ARIAS',
        'SMITH',
        'MENDOZA',
        'CRUZ',
        'WILDE'

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
//insertRandUser(300, getConnection());
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

function dd(mixed ...$data)
{
    var_dump($data);
    die;
}
function showSessionMsg()
{
    if (!empty($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        $alertType = $_SESSION['messageType'];
        unset($_SESSION['messageType']);
        require_once 'view/message.php';
    }
}
function getUploadDirPath(): string
{
    $config = require 'config.php';
    $uploadDir = $config['uploadDir'] ?? 'avatar';
    $uploadDirPath = realpath(__DIR__) . '/' . $uploadDir . '/';
    return $uploadDirPath;
}
function handleAvatarUpload(array $file, ?int $userId = null): ?string
{
    $config = require 'config.php';
    $uploadDir = $config['uploadDir'] ?? 'avatar';
    $uploadDirPath = getUploadDirPath();
    $mimeMap = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif'
    ];
    $fileinfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileinfo->file($file['tmp_name']);
    //$extension = pathinfo($file['name']);
    $extension = $mimeMap[$mimeType];
    $fileName = ($userId ? $userId . '_' : '') . bin2hex(random_bytes(8)) . '.' . $extension;
    $res = move_uploaded_file($file['tmp_name'], $uploadDirPath . $fileName);
    return $res ? $uploadDir . '/' . $fileName : null;
}
function validateFileUpload(array $file): array
{
    $errors = [];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = getUploadError($file['error']);
        return $errors;
    }
    $config = require 'config.php';

    $fileinfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileinfo->file($file['tmp_name']);
    if (!in_array($mimeType, $config['mimeTyped'] ?? ['image/jpeg'])) {
        $errors[] = 'Invalid file type.Allowed types: ' . implode(',', $config['mimeTypes']);
    }
    if ($file['size'] > $config['maxFileSize']) {
        $errors[] = 'File size exceeds ' . $config['maxFileSize'];
    }
    return $errors;
}

function getUploadError(int $errorCode): string
{
    $error = '';

    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:

            $error = 'File size exceeds the allowed limit.';
            break;
        case UPLOAD_ERR_PARTIAL:
            $error  = 'The file was only partially uploaded.';
            break;
        case UPLOAD_ERR_NO_FILE:
            $error  = 'No file was uploaded.';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $error  = 'Missing temporary folder.';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $error = 'Failed to write file to disk.';
            break;
        case UPLOAD_ERR_EXTENSION:
            $error  = 'File upload stopped by extension.';
            break;
        default:
            $error = 'Unknown file upload error.';
            break;
    }
    return $error;
}

function setFlashMessage(string $message, string $type = 'info')
{
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $type;
}

function redirectWithParams(): void
{
    $params = $_GET;
    if (isset($params['id']))
        unset($params['id']);
    if (isset($params['action'])) {
        unset($params['action']);
    }
    $queryString = http_build_query($params);
    header('Location:../index.php?' . $queryString);
    exit;
}
function convertMaxUploadSizeToBytes(): int
{
    $maxUploadSize = ini_get('upload_max_filesize'); // 2M, 2G
    $number = (int)$maxUploadSize;
    $unit = strtoupper(substr($maxUploadSize, -1));

    switch ($unit) {
        case 'G':
            $number = $number * (1024 ** 3);
            break;
        case 'M':
            $number = $number * (1024 ** 2);
            break;
        case 'K':
            $number = $number * 1024;
            break;
    }

    return $number;
}
function formatBytes(int $bytes): string
{
    //20970000 
    $units = ['Bytes', 'Kilobytes', 'Megabytes', 'Gigabytes'];
    $power = floor(log($bytes, 1024));
    $number = round($bytes / 1024 ** $power, 2);
    return $number . ' ' . $units[$power];
}
function validateUserData(array $data): array
{
    $errors = [];
    if (
        empty($data['username']) || strlen($data['username']) > 64
        || strlen($data['username']) < 3
    ) {
        $errors['username'] = 'Invalid username: must be non-empty and min 3 and max 64 chars.';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }
    if (empty($data['fiscalcode']) || strlen($data['fiscalcode']) !== 16) {
        $errors['fiscalcode'] = 'Fiscal code must be exactly 16 characters.';
    }
    if ($data['age'] < 18 || $data['age'] > 120) {
        $errors['age'] = 'Age must be between 18 and 120.';
    }
    return $errors;
}
function createThumbnailAndIntermediate(string $originalPath): void
{
    $config = require 'config.php';
    $targetDir = $config['uploadDir'] ?? 'uploads';
    $thumbnailWidth = $config['thumbnailWidth'] ?? 120;
    $intermediateWidth = $config['intermediateWidth'] ?? 600;

    $fileName = basename($originalPath);
    $targetDir = getUploadDirPath();
    $sourcePath = $targetDir . $fileName;
    $mimeType = mime_content_type($sourcePath);
    $thumbnailPath = $targetDir . '/thumbnail_' . $fileName;
    $intermediatePath = $targetDir . '/intermediate_' . $fileName;
    resizeImage($sourcePath, $thumbnailPath, $thumbnailWidth, $mimeType);

    // Create Intermediate Image
    resizeImage($sourcePath, $intermediatePath, $intermediateWidth, $mimeType);
}

function resizeImage(string $sourcePath, string $destinationPath, int $newWidth, string $mimeType): void
{
    // Load the source image
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return;
    }

    // Get original dimensions
    $originalWidth = imagesx($sourceImage);
    $originalHeight = imagesy($sourceImage);

    // Calculate new dimensions
    $newHeight = (int)(($newWidth / $originalWidth) * $originalHeight);

    // Create a new resized image
    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Copy and resize
    imagecopyresampled(
        $resizedImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $originalWidth,
        $originalHeight
    );

    // Save the resized image
    switch ($mimeType) {
        case 'image/jpeg':
            imagejpeg($resizedImage, $destinationPath, 90); // Quality 90 for JPG
            break;
        case 'image/png':
            imagepng($resizedImage, $destinationPath);
            break;
        case 'image/gif':
            imagegif($resizedImage, $destinationPath);
            break;
    }

    // Free memory
    imagedestroy($sourceImage);
    imagedestroy($resizedImage);
}
