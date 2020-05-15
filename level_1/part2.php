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
        "method" => strstr($string, " ", true),
        "uri" => strstr(substr(strstr($string, " "), 1), " ", true),
		"headers" => $arr,
        "body" => preg_split("/\n\n/", $string)[1],
    );
}

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));
?>