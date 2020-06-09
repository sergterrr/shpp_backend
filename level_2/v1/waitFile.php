<?php
/** Waits while the file is busy
 * @param $filename - file name
 */
function sleepIfUnavailable ($filename)
{
    while (!is_writable($filename)) {
        usleep(2000);
    }
}

?>