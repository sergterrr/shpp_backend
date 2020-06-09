<?php
include_once("newBSandTabl.php");
createBSAndTables();    // Creates a database and a table if they are not
// Processing received data
$newUser = json_decode(file_get_contents("php://input"), true);
$login = $newUser['login'];
$pass = md5($newUser['pass']);
// Create a connection to the base
$conn = connect();
if (!$conn) {
    exit();
}
// If there is a user with the same name in the table, then give an error
$sql = "SELECT login FROM " . USERSTABL . " WHERE login = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $login);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) > 0) {
    echo json_encode(array("error" => 400));
    exit();
}
// Add new user
$sql = "INSERT INTO " . USERSTABL . " (login, pass) VALUES (?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $login, $pass);
if (mysqli_stmt_execute($stmt)) {
    echo json_encode(array('ok' => true));
} else {
    echo json_encode(array("error" => 500));
    exit();
}
// Break connection
mysqli_close($conn);
?>