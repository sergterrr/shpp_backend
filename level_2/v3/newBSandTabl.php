<?php
include_once("connectMySQL.php");
/**
 * Creates a database and tables at the first registration if there were none
 */
function createBSAndTables()
{
    // Create connection and base
    try {
        $conn = new PDO("mysql:host=" . SERVERNAME . "", USERNAME, PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("CREATE DATABASE IF NOT EXISTS " . DBNAME . "");
    } catch (PDOException $e) {
        echo "error" . $e->getMessage();
    }
    $conn = null;

    try {
        // Create a connection to the base
        $conn = connect();
        // Create a user table
        $conn->exec("CREATE TABLE IF NOT EXISTS " . USERSTABL . " (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		login VARCHAR(50) NOT NULL,
		pass VARCHAR(50)  NOT NULL)");

        // Create a item table
        $conn->exec("CREATE TABLE IF NOT EXISTS " . ITEMSTABL . " (
		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		text VARCHAR(50) NOT NULL,
		checked boolean DEFAULT false,
		userId INT
		)");
    } catch (PDOException $e) {
        echo "error" . $e->getMessage();
    }
    // Break connection
    $conn = null;
}

?>