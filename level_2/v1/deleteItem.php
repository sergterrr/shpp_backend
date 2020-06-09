<?php
include_once("headers.php");
include_once("waitFile.php");
$newItem = json_decode(file_get_contents("php://input"), true);
$fileData = "data.json";
$fileCount = "counterItems.txt";
// Check for file availability. In the absence of a file is created.
// If there is data in the file, then the data is formed into an array.
if (!file_exists($fileData)) {
    fopen($fileData, "w");
    fopen($fileCount, "w");
} else {
    $items = file_get_contents($fileData, true);
    $count = file_get_contents($fileCount, true);
    $itemsArray = json_decode($items, true);
}
$num = 0;
// Search by index value in an array
foreach ($itemsArray as $c) {
	//If the value is found, it is deleted from the array, and the array is written to the file.
    if ($c["id"] == $newItem["id"]) {
        array_splice($itemsArray, $num, 1);
        sleepIfUnavailable($fileData);
        file_put_contents($fileData, json_encode($itemsArray),  LOCK_EX);
        echo json_encode(array("ok" => true));
        break;
    }
    $num++;
}

?>

