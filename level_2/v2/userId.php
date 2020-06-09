<?php
/**
 * Check for session existence.
 * @return bool|mixed id of the user for whom the session is created if it is active
 */
function sessionUserId()
{
    session_start();
    if (isset($_SESSION['userId'])) {
        return $_SESSION['userId'];
    }
    return false;
}

?>