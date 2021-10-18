<?php
require_once('includes/config.php');

$curl = curl_init();
$symbol = "MSFT";

curl_setopt_array($curl, array(
	CURLOPT_URL => "http://api.marketstack.com/v1/tickers?symbols=$symbol&access_key=$token",
//            http://api.marketstack.com/v1/tickers?symbols=AAPL&access_key=7636d77b2e219a1319e7f9b167abf4b7
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 90,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET"
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if($err){
	echo "cURL Error :" . $err;
}else{
	//echo $response;
}

// convert the response to php array or object
    $name = json_decode($response, true);
    //echo $array->data[0]->name;
    echo $name['data'][0]['name'];
?>
