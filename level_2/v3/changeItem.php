<?php
include_once("connectMySQL.php");
include_once("userId.php");
// Processing received data
$item = json_decode(file_get_contents("php://input"), true);
// Check for session existence
$userId = sessionUserId();
if (!$userId) {
    echo json_encode(array("error" => 400));
    exit();
}
try {
    // Create a connection to the base
    $conn = connect();
    // User item update
    $sql = "UPDATE " . ITEMSTABL . " SET text =  ? , checked = ? WHERE id = ? AND userId = '$userId.'";
    $sth = $conn->prepare($sql);
    $item['checked'] = $item['checked'] == true ? 1 : 0;
    $sth->bindParam(1, $item['text'], PDO::PARAM_STR);
    $sth->bindParam(2, $item['checked'], PDO::PARAM_INT);
    $sth->bindParam(3, $item['id'], PDO::PARAM_INT);
    $sth->execute();
    echo json_encode(array("ok" => true));
} catch (PDOException $e) {
    echo 'error:' . $e->getMessage();
}
// Break connection
$conn = null;
?>
