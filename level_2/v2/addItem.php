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
// Create a connection to the base
$conn = connect();
if (!$conn) {
    exit();
}
// Insert into data table
$sql = "INSERT INTO " . ITEMSTABL . " (text, userId) VALUES (?, '$userId')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $newItem['text']);
if (mysqli_stmt_execute($stmt)) {
} else {
    echo json_encode(array("error" => 500));
    exit();
}
$last_id = mysqli_insert_id($conn);
echo json_encode(array('id' => $last_id));
// Break connection
mysqli_close($conn);
?>