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
	// Get method and sum of numbers
	if ((preg_match("/^GET$/", $method)) && (preg_match("/(^\/sum\?nums=)((\d+,)+)\d+$/", $uri))){
		$statuscode = "200";
		$statusmessage = "OK";
		$body=array_sum(explode( ",", preg_replace("/(^\/sum\?nums=)/", "", $uri)));
	}
	// Get method and not the sum of numbers
	else if((preg_match("/^GET$/", $method))&&(!preg_match("/^\/sum/", $uri))){
		$statuscode = "404";
		$statusmessage = "Not Found";
		$body = "not found";

	}
	// Not get method and not numbers
	else if((!preg_match("/^GET$/", $method)) || (!preg_match("/(^\/\w+)(\?nums=)/", $uri))){
		$statuscode = "400";
		$statusmessage = "Bad Request";
		$body = "not found";
	}
	$headers = "Date: ".date(DATE_RFC822)."
Server: Apache/2.2.14 (Win32)
Connection: Closed
Content-Type: text/html; charset=utf-8
Content-Length: ".strlen($body);
	// The formation of the answer.
	outputHttpResponse($statuscode, $statusmessage, $headers, $body); 
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