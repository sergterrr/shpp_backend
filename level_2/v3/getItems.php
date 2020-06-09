<?php
include_once("connectMySQL.php");
include_once("userId.php");
// Check for session existence
$userId = sessionUserId();
if (!$userId) {
    echo json_encode(array("error" => 400));
    exit();
}
try {
    // Create a connection to the base
    $conn = connect();
    // Select data with table
    $result = $conn->query("SELECT id, text, checked FROM " . ITEMSTABL . " WHERE userId = '$userId'");
    $result->setFetchMode(PDO::FETCH_ASSOC);
    $items = array();
    // If the data is found, then replace 0/1 with false/true
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch()) {
            $items[] = array("id" => $row["id"], "text" => $row["text"], "checked" => (($row["checked"] == 0) ? false : true));
        }
    }

    echo json_encode(array("items" => $items));
} catch (PDOException $e) {
    echo 'error:' . $e->getMessage();
}
// Break connection
$conn = null;
?>