<?php

// Get cURL resource
ob_start();
ini_set('max_execution_time', 300);

$curl = curl_init();
$baseUrl =
'https://005419-marketing.bpmonline.com/0/ServiceModel/EntityDataService.svc/ContactCollection?$top=168';


curl_setopt_array($curl, array(
    CURLOPT_USERPWD => 'Ahmed Marzouk' . ":" .'Artorg123',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $baseUrl
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
// Close request to clear up some resources

$file = 'testFile.xml';
if($handle = fopen($file,'wt')){
    fwrite($handle,$resp);
    fclose($handle);
}else{
    echo "could not open the file";
}

$xml = simplexml_load_file($file);
$result = json_encode($xml);

$jsonFile = 'jsonFile.json';
if($handle = fopen($jsonFile,'wt')){
    fwrite($handle,$result);
    fclose($handle);
}else{
    echo "could not open the file";
}


$array = json_decode($result,TRUE);

foreach ($array as $element){
    echo "<br/><br/>";
    var_dump($element);
}

curl_close($curl);

echo gettype($resp)."<br/>";
var_dump($resp);





?>


<!-- https://005419-marketing.bpmonline.com/0/ServiceModel/EntityDataService.svc/-->
<!-- https://005419-marketing.bpmonline.com/0/ServiceModel/GeneratedObjectWebFormService.svc/SaveWebFormObjectData-->
<!-- https://005419-marketing.bpmonline.com/0/ServiceModel/AuthService.svc/Login/-->
