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
// User item update
$sql = "UPDATE " . ITEMSTABL . " SET text =  ? , checked = ? WHERE id = ? AND userId = '$userId.'";
$stmt = mysqli_prepare($conn, $sql);
$item['checked'] = $item['checked'] == true ? 1 : 0;
mysqli_stmt_bind_param($stmt, 'sii', $item['text'], $item['checked'], $item['id']);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(array("ok" => true));
} else {
    echo json_encode(array("error" => 500));
    exit();
}
// Break connection
mysqli_close($conn);

?>