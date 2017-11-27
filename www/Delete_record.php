<?php

ob_start();
ini_set('max_execution_time', 300);

$id  = '82556a83-6104-487a-a273-84f3813f4cb1';
$url = "https://014100-sales-enterprise.bpmonline.com/0/ServiceModel/EntityDataService.svc/ContactCollection(guid'{$id}')";

# Setup request to send json via POST.

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_USERPWD => 'Ahmed_Marzouk' . ":" .'Artorg123',
    CURLOPT_CUSTOMREQUEST => 'DELETE',
    CURLOPT_HTTPHEADER =>array('Content-Type:application/atom+xml;type=entry','Accept:application/atom+xml'),
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url

));

$result = curl_exec($ch);

curl_close($ch);

echo "<pre>$result</pre>";

?>