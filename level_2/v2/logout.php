<?php
include_once("headers.php");

session_start();

//Clear session array
$_SESSION = array();

// Delete session cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
echo json_encode(array("ok" => true));
session_destroy();
?>