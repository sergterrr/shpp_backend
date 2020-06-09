<?php
include_once("connectMySQL.php");
include_once("userId.php");
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
// Select data with table
$result = mysqli_query($conn, "SELECT id, text, checked, userId FROM " . ITEMSTABL . " WHERE userId = '$userId'");
$items = array();
// If the data is found, then replace 0/1 with false/true
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = array("id" => $row["id"], "text" => $row["text"], "checked" => (($row["checked"] == 0) ? false : true));
    }
}
echo json_encode(array("items" => $items));
// Break connection
mysqli_close($conn);
?>