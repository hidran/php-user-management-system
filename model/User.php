<?php

require_once '../connection.php';
function deleteUser(int $id): bool{
  $conn = getConnection();
  $sql = 'DELETE FROM users WHERE id='.$id;
  $res = $conn->query($sql);
  return $res && $conn->affected_rows;
}