<?php
include_once("constants.php");
/**
 * Creates a connection to the database
 * @return false|mysqli connection
 */
function connect()
{
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASS, DBNAME);
    if (!$conn) {
        echo json_encode(array("error" => 500));
    }
    return $conn;
}

?>