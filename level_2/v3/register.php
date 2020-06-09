<?php
include_once("newBSandTabl.php");
createBSAndTables();    // Creates a database and a table if they are not
// Processing received data
$newUser = json_decode(file_get_contents("php://input"), true);
$login = $newUser['login'];
$pass = md5($newUser['pass']);
try {
    // Create a connection to the base
    $conn = connect();
    //If there is a user with the same name in the table, then give an error
    $sql = "SELECT login FROM " . USERSTABL . " WHERE login = ?";
    $sth = $conn->prepare($sql);
    $sth->bindParam(1, $login, PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn() > 0) {
        echo json_encode(array("error" => 400));
    } else {
        // Add new user
        $sql = "INSERT INTO " . USERSTABL . " (login, pass) VALUES (?, ?)";
        $sth = $conn->prepare($sql);
        $sth->bindParam(1, $login, PDO::PARAM_STR);
        $sth->bindParam(2, $pass, PDO::PARAM_STR);
        $sth->execute();
        echo json_encode(array('ok' => true));
    }
} catch (PDOException $e) {
    echo 'error:' . $e->getMessage();
}
// Break connection
$conn = null;
?>