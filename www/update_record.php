<?php

ob_start();
ini_set('max_execution_time', 300);

$id  = '82556a83-6104-487a-a273-84f3813f4cb1'; //Engineer IT ID
$url = "https://014100-sales-enterprise.bpmonline.com/0/ServiceModel/EntityDataService.svc/ContactCollection(guid'{$id}')";

$contact_xml = '<?xml version="1.0" encoding="utf-8"?>
<entry xmlns="http://www.w3.org/2005/Atom">
    <content type="application/xml">
        <properties xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
            <Name xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">Updated Contact Merge</Name>
        </properties>
    </content>
</entry>';

# Setup request to send json via POST.

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_USERPWD => 'Ahmed_Marzouk' . ":" .'Artorg123',
    CURLOPT_CUSTOMREQUEST => 'MERGE',
    CURLOPT_HTTPHEADER =>array('Content-Type:application/atom+xml;type=entry','Accept:application/atom+xml'),
    CURLOPT_POSTFIELDS => $contact_xml,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url

));

$result = curl_exec($ch);

curl_close($ch);

echo "<pre>$result</pre>";

?>