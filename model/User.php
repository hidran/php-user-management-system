<?php


function deleteUser(int $id): bool{
  $conn = getConnection();
  $sql = 'DELETE FROM users WHERE id='.$id;
  $res = $conn->query($sql);
  return $res && $conn->affected_rows;
}
function getUserById(int $id):array {
    $conn = getConnection();
    $sql = 'SELECT * FROM users WHERE id =?';
    $stm = $conn->prepare($sql);
    $stm->bind_param('i', $id);
    $stm->execute();
    $result = $stm->get_result();
    $user = $result->fetch_assoc();
    $stm->close();
    return $user;
}
function updateUser(array $data,int $id): bool
{
    $conn = getConnection();
    $sql = 'UPDATE users SET username = ?, email = ?, fiscalcode = ?, age = ? WHERE id = ?';
    $stm = $conn->prepare($sql);
    $stm->bind_param('sssii',
    $data['username'], 
    $data['email'],
     $data['fiscalcode'],
      $data['age'],
      $id
    );
   $res = $stm->execute();
  
    $stm->close();
    return $res;
}