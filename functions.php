<?php

declare(strict_types=1);
require_once 'connection.php';
function getConfig($param, $default = null)
{
    $config = require 'config.php';

    return $config[$param] ?? $default;
}

function getParam(string $param, string $default = ''): string
{
    return $_REQUEST[$param] ?? $default;
}

function getPostParam($param, $default = ''): string
{
    return $_POST[$param] ?? $default;
}

function post_string(string $key, $max = 255): string
{
    $v = trim($_POST[$key] ?? '');
    return mb_substr($v, 0, $max);
}

function cleanEmail(string $email = ''): string
{
    $email = post_string($email, 254);
    $filterEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$filterEmail) {
        $email = '';
    }
    return $email;
}

function verifyEmail(string $email = ''): bool
{
    if (!$email) {
        return false;
    }
    return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateUserName(string $username): bool
{
    if (!$username) {
        return false;
    }
    $length = getParam('minUserNameLength');
    return mb_strlen($username) >= $length;
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

    $rand1 = random_int(0, count($names) - 1);
    $rand2 = random_int(0, count($lastnames) - 1);

    return $names[$rand1] . ' ' . $lastnames[$rand2];
}

//echo getRandName();
function getRandEmail(string $name): string
{
    $domains = ['google.com', 'yahoo.com', 'hotmail.it', 'libero.it'];

    $rand1 = random_int(0, count($domains) - 1);

    return strtolower(str_replace(' ', '.', $name) . random_int(10, 99) . '@' . $domains[$rand1]);
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
 * @param array $params
 * @return array
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
        return (int)$row['total'];
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

function handleAvatarUpload(array $file, ?int $userId = null): ?string
{
    $config = require 'config.php';
    $uploadDir = $config['uploadDir'] ?? 'avatar';
    $uploadDirPath = realpath(__DIR__) . '/' . $uploadDir . '/';
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
    if (!in_array($mimeType, $config['mimeTyped'] ?? ['image/jpeg'], false)) {
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
            $error = 'The file was only partially uploaded.';
            break;
        case UPLOAD_ERR_NO_FILE:
            $error = 'No file was uploaded.';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $error = 'Missing temporary folder.';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $error = 'Failed to write file to disk.';
            break;
        case UPLOAD_ERR_EXTENSION:
            $error = 'File upload stopped by extension.';
            break;
        default:
            $error = 'Unknown file upload error.';
            break;
    }
    return $error;
}

function getUploadDir(): string
{
    $uploadDir = getConfig('uploadDir', 'avatar');
    $uploadDir = realpath(__DIR__) . '/' . trim($uploadDir, '/') . '/';
    return $uploadDir;
}

function createThumbnailAndIntermediate(string $avatarPath): void
{
    $config = require 'config.php';
    $fileName = basename($avatarPath);
    $uploadDirPath = getUploadDir();
    $thumbnailPath = $uploadDirPath . 'thumbnail_' . $fileName;
    $intermediatePath = $uploadDirPath . 'intermediate_' . $fileName;
    $sourcePath = $uploadDirPath . $fileName;
    $thumbnailWidth = $config['thumbnailWidth'] ?? 120;
    $intermediateWidth = $config['intermediateWidth'] ?? 800;
    $mimeType = mime_content_type($sourcePath);
    resizeImage($sourcePath, $thumbnailPath, $thumbnailWidth, $mimeType);
    resizeImage($sourcePath, $intermediatePath, $intermediateWidth, $mimeType);
}


function resizeImage(string $sourcePath, string $targetPath, int $width, string $mimeType): void
{
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
    $originalWidth = imagesx($sourceImage);
    $originalHeight = imagesy($sourceImage); // 2000 * 120/3000
    $newHeight = (int)floor($originalHeight * ($width / $originalWidth));
    $newImage = imagecreatetruecolor($width, $newHeight);
    imagecopyresampled(
        $newImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        $width,
        $newHeight,
        $originalWidth,
        $originalHeight
    );

    switch ($mimeType) {
        case 'image/jpeg':
            imagejpeg($newImage, $targetPath, getConfig('jpegQuality', 90));
            break;
        case 'image/png':
            imagepng($newImage, $targetPath);
            break;
        case 'image/gif':
            imagegif($newImage, $targetPath);
            break;
        default:
            return;
    }

    imagedestroy($sourceImage);
    imagedestroy($newImage);
}


function setFlashMessage(string $message, string $type = 'info'): void
{
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = $type;
}

function redirectWithParams(): never
{
    $params = $_GET;
    if (isset($params['id'])) {
        unset($params['id']);
    }
    if (isset($params['action'])) {
        unset($params['action']);
    }
    $queryString = http_build_query($params);
    header('Location:../index.php?' . $queryString);
    exit;
}

function redirect(string $url = '/'): never
{
    header("Location:$url");
    exit;
}

function convertMaxUploadSizeToBytes(): int
{
    $maxUploadSize = ini_get('upload_max_filesize'); // 2M, 2G
    $number = (int)$maxUploadSize;
    $unit = strtoupper(substr($maxUploadSize, -1));

    switch ($unit) {
        case 'G':
            $number *= (1024 ** 3);
            break;
        case 'M':
            $number *= (1024 ** 2);
            break;
        case 'K':
            $number *= 1024;
            break;
    }

    return $number;
}

function find_user_by_email(mysqli $conn, string $email): ?array
{
    $sql = 'SELECT * FROM users WHERE email = ? LIMIT 1';
    $st = $conn->prepare($sql);
    $st->bind_param('s', $email);
    if (!$st->execute()) {
        return null;
    }
    $row = $st->get_result()->fetch_assoc();

    $st->close();
    return $row;
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
    if (!validatePassword($data['password'])) {
        $errors['password'] = 'Invalid password. it should be at least 6 chars';
    }
    return $errors;
}

function validatePassword(string $password): bool
{
    return strlen($password) >= 6;
}

function getImgThumbNail(string $path, string $size = 's'): array
{
    $imgWidth = getConfig($size === 's' ? 'thumbnailWidth' : 'intermediateWidth', 120);
    $fileData = ['width' => $imgWidth, 'avatar' => ''];
    $prefix = $size === 's' ? 'thumbnail_' : 'intermediate_';
    $fileName = $prefix . basename($path);
    $thumbnail = getConfig('uploadDir', 'avatar')
        . '/' . $fileName;

    $uploadDir = getUploadDir() . '/' . $fileName;
    if (file_exists($uploadDir)) {
        $fileData['avatar'] = $thumbnail;
        $fileData['width'] = $imgWidth;
    }

    return $fileData;
}

function deleteUserImages(string $avatarPath): void
{
    if (!$avatarPath) {
        return;
    }
    $uploadDir = getUploadDir();
    $fileName = basename($avatarPath);
    $avatarFile = $uploadDir . $fileName;
    $thumbnail = $uploadDir . 'thumbnail_' . $fileName;
    $intermediate = $uploadDir . 'intermediate_' . $fileName;
    if (file_exists($avatarFile)) {
        unlink($avatarFile);
    }
    if (file_exists($thumbnail)) {
        unlink($thumbnail);
    }
    if (file_exists($intermediate)) {
        unlink($intermediate);
    }
}
