<?php
include_once("connectMySQL.php");
include_once("userId.php");
// Processing received data
$newItem = json_decode(file_get_contents("php://input"), true);
// Check for session existence
$userId = sessionUserId();
if (!$userId) {
    echo json_encode(array("error" => 400));
    exit();
}
try {
    // Create a connection to the base
    $conn = connect();
    // Insert into data table
    $sql = "INSERT INTO " . ITEMSTABL . " (text, userId) VALUES (?, '$userId')";
    $sth = $conn->prepare($sql);
    $sth->bindParam(1, $newItem['text'], PDO::PARAM_STR);
    $sth->execute();
    $last_id = $conn->lastInsertId();
    echo json_encode(array('id' => $last_id));
} catch (PDOException $e) {
    echo "error" . $e->getMessage();
}
// Break connection
$conn = null;
?>
