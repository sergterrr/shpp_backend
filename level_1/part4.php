<?php
function readHttpLikeInput() {
    $f = fopen( 'php://stdin', 'r' );
    $store = "";
    $toread = 0;
    while( $line = fgets( $f ) ) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/',$line,$m)) 
            $toread=$m[1]*1; 
        if ($line == "\r\n") 
              break;
    }
    if ($toread > 0) 
        $store .= fread($f, $toread);
    return $store;
}

$contents = readHttpLikeInput();

/**
 * Formation of the answer from input elements and input to the screen
 *
 * @param $statuscode statuscode of answer 
 * @param $statusmessage statuscode of answer 
 * @param $headers headers of answer 
 * @param $body statuscode of answer 
 */
function outputHttpResponse($statuscode, $statusmessage, $headers, $body) {
	$strAll = "HTTP/1.1 ".$statuscode." ".$statusmessage."\n".$headers."\n\n".$body;
	echo $strAll;
}

/**
 * Request parsing
 *
 * @param $method method request
 * @param $uri uri request
 * @param $headers headers request
 * @param $body body request
 */
function processHttpRequest($method, $uri, $headers, $body) {
// If uri and Content-Type match
if((preg_match("/^\/api\/checkLoginAndPassword$/", $uri)) && (in_array(array("Content-Type", "application/x-www-form-urlencoded"), $headers))){
		$statuscode = "200";
		$statusmessage = "OK";
		$fileName = "st\level_1\passwords.txt";
		// Сhecking file availability
		if(file_exists($fileName)){
			// Formation body on the search result
			$body=searchUsernameAndPassword($fileName, $body);
		}
		else{
			$statuscode = "500";
			$statusmessage = "Internal Server Error";
			$body="<h1 style=\"color:red\">NOT FOUND</h1>";
		}
	}
	// If uri or Content-Type not match
	else{
		$statuscode = "404";
		$statusmessage = "Not Found";
		$body = "<h1 style=\"color:red\">NOT FOUND</h1>";
	}
	$headers="Date: ".gmdate("D, d M Y H:i:s", time())." GMT
Server: Apache/2.2.14 (Win32)
Connection: Closed
Content-Type: text/html; charset=utf-8
Content-Length: ".strlen($body);
	// The formation of the answer.
	outputHttpResponse($statuscode, $statusmessage, $headers, $body); 
}

/**
 * Formation body on the search result login and password
 *
 * @param $fileName file to read
 * @param $body body request
 * 
 * return new body
 */
function searchUsernameAndPassword($fileName, $body){
	// Read file to string
	$file = file_get_contents("st\level_1\passwords.txt", true);
	// Formation of string by pattern
	$pattern = array("/^login=/", "/&password=/"); 
	$replace = array("", ":"); 
	$loginAndPassword=preg_filter($pattern, $replace, $body); 
	// If username and password match
	if(!(strpos($file, $loginAndPassword) === false)){
		return $body = "<h1 style=\"color:green\">FOUND</h1>";
	}
	else{
		return $body = "<h1 style=\"color:red\">NOT FOUND</h1>";
	}
}

/**
 * Formation an array of values ​​from the query as a string
 *
 * @param $string request as a string
 * 
 * return parsed request array
 */
function parseTcpStringAsHttpRequest($string) {
	// Formation of an array of headers
	$headersAndBody = preg_split("/\n/", $string, 2)[1];
	$headersStr = preg_split("/\n\n/", $headersAndBody)[0];
	$headersArr = explode("\n", $headersStr);
	$arr = array();
	// header check
	if(stristr($headersStr, ": ")){
		for($i = 0; $i < count($headersArr); $i ++){
			$arr[$i] = explode(": ", $headersArr[$i]);
		}
	}
	return array(
        "method" => strstr($string," ", true),
        "uri" => strstr(substr(strstr($string, " "),1), " ", true),
		"headers" => $arr,
        "body" => preg_split("/\n\n/", $string)[1],
    );
}

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);

?>