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
// Create a connection to the base
$conn = connect();
if (!$conn) {
    exit();
}
// Delete user item by id
if (mysqli_query($conn, "DELETE FROM " . ITEMSTABL . " WHERE id='" . (int)$item['id'] . "'")) {
    echo json_encode(array("ok" => true));
} else {
    echo json_encode(array("error" => 500));
}
// Break connection
mysqli_close($conn);
?>
