<?php
include_once("headers.php");
include_once("waitFile.php");
$fileData = "data.json";
$fileCount = "counterItems.txt";
$count = 0;
$itemsArray = array();
// Check for file availability. In the absence of a file is created.
// If there is data in the file, then it is transmitted in json format
if (!file_exists($fileData)) {
    fopen($fileData, "w");
    fopen($fileCount, "w");
} else {
    $items = (file_get_contents($fileData, true));
    $itemsArray = json_decode($items, true);
    $count = file_get_contents($fileCount, true);
}
echo json_encode(array("items" => $itemsArray));

?>