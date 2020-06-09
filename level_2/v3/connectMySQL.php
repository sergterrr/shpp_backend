<?php
include_once("constants.php");
/**
 * Creates a connection to the database
 * @return PDO connection
 */
function connect()
{
    $conn = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME . "", USERNAME, PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

?>