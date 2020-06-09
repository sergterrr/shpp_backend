<?php
include_once("connectMySQL.php");
/**
 * Creates a database and tables at the first registration if there were none
 */
function createBSAndTables()
{
    // Create connection
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASS);
    if (!$conn) {
        echo json_encode(array("error" => 500));
        exit();
    }
    // Create base
    if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS " . DBNAME . "")) {
        echo json_encode(array("error" => 500));
        exit();
    }
    // Create a connection to the base and check the duel
    $conn = connect();
    if (!$conn) {
        exit();
    }
    // Create a user table
    if (!mysqli_query($conn, "CREATE TABLE IF NOT EXISTS " . USERSTABL . " (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	login VARCHAR(50) NOT NULL,
	pass VARCHAR(50)  NOT NULL
	)")) {
        echo json_encode(array("error" => 500));
        exit();
    }
    // Create a item table
    if (!mysqli_query($conn, "CREATE TABLE IF NOT EXISTS " . ITEMSTABL . " (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	text VARCHAR(50) NOT NULL,
	checked BOOLEAN DEFAULT false,
	userId INT
	)")) {
        echo json_encode(array("error" => 500));
        exit();
    }
    // Break connection
    mysqli_close($conn);
}

?>