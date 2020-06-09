<?php
include_once("headers.php");
switch ($_GET['action']) {
    case ('register'):
        include_once("register.php");
        break;
    case ('login'):
        include_once("login.php");
        break;
    case ('logout'):
        include_once("logout.php");
        break;
    case ('addItem'):
        include_once("addItem.php");
        break;
    case ('getItems'):
        include_once("getItems.php");
        break;
    case ('deleteItem'):
        include_once("deleteItem.php");
        break;
    case ('changeItem'):
        include_once("changeItem.php");
        break;
}
?>