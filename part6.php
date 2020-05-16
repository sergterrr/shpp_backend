<?php
/**
 * Saves the number of site visits to a file
 *
 * @param $fileName name of file to save
 */
function loginCounterOnSite($fileName){
// Check for the presence of a file with a counter
// If the file is missing then create the file. Set counter to 0
	if(!file_exists($fileName)){
		fopen($fileName, "w");
		$count=0;
	}
	// Read the counter value from the file
	else{
		$count=file_get_contents($fileName, true);
	}
	echo"<h1 style=\"color:green\">Number of visits to the site: $count</h1>";
	// Save the new counter value
	file_put_contents($fileName, ++$count);
}
loginCounterOnSite("counter.txt");
?>