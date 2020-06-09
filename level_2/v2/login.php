<?php
include_once("connectMySQL.php");
// Processing received data
$newUser = json_decode(file_get_contents("php://input"), true);
$login = $newUser['login'];
$pass = md5($newUser['pass']);
// Create a connection to the base
$conn = connect();
if (!$conn) {
    exit();
}
// If the username and password match then create a session
$sql = "SELECT id, login, pass FROM " . USERSTABL . " WHERE login = ? AND pass= ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $login, $pass);
mysqli_stmt_execute($stmt);
$result=mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    session_start();
    $_SESSION['userId'] = (mysqli_fetch_assoc($result))['id'];
    echo json_encode(array("ok" => true));
} else {
    echo json_encode(array("error" => 400));
    exit();
}
mysqli_close($conn);
?>