<?php
include_once("connectMySQL.php");
// Processing received data
$newUser = json_decode(file_get_contents("php://input"), true);
$login = $newUser['login'];
$pass = md5($newUser['pass']);
try {
    // Create a connection to the base
    $conn = connect();
    // If the username and password match then create a session
    $sql = "SELECT id, login, pass FROM " . USERSTABL . " WHERE login = ? AND pass= ?";
    $sth = $conn->prepare($sql);
    $sth->bindParam(1, $login, PDO::PARAM_STR);
    $sth->bindParam(2, $pass, PDO::PARAM_STR);
    $sth->execute();
    if ($sth->fetchColumn() > 0) {
        $sth->execute();
        session_start();
        $_SESSION['userId'] = $sth->fetch(PDO::FETCH_ASSOC)['id'];
        echo json_encode(array("ok" => true));
    } else {
        echo json_encode(array("error" => 400));
    }
} catch (PDOException $e) {
    echo "error" . $e->getMessage();
}
$conn = null;

?>
