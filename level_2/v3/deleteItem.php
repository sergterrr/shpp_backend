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
    // Delete user item by id
    $conn->exec("DELETE FROM " . ITEMSTABL . " WHERE id='" . (int)$item['id'] . "'");
    echo json_encode(array("ok" => true));
} catch (PDOException $e) {
    echo "error" . $e->getMessage();
}
// Break connection
$conn = null;
?>
