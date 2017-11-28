<?php

// Get cURL resource
ob_start();
ini_set('max_execution_time', 300);

$curl = curl_init();
$baseUrl =
'https://014100-sales-enterprise.bpmonline.com/0/ServiceModel/EntityDataService.svc/ContactCollection?select=Id';


curl_setopt_array($curl, array(
    CURLOPT_USERPWD => 'Ahmed_Marzouk' . ":" .'Artorg123',
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


curl_close($curl);

$id='678835c2-6850-49f6-a1ba-33eae62396b1';
$id_exists = checkId($id);
if($id_exists){echo "true";}else{echo "false";};


//echo gettype($resp)."<br/>";
//var_dump($resp);

function checkId($id){
    // this function takes the id and search for the id in the recieved data
    // if the id exists it return TRUE if not it returns FALSE.
    if(strlen($id)!= 36){
        return false;
    }else{
        $handle = fopen('testFile.xml', 'r');
        $valid = false; // init as false
        while (($buffer = fgets($handle)) !== false) {
            if (strpos($buffer, $id) !== false) {
                $valid = TRUE;
                break; // Once you find the string, you should break out the loop.
            }
        }
        fclose($handle);
        return $valid;
    }
}




?>


<!-- https://005419-marketing.bpmonline.com/0/ServiceModel/EntityDataService.svc/-->
<!-- https://005419-marketing.bpmonline.com/0/ServiceModel/GeneratedObjectWebFormService.svc/SaveWebFormObjectData-->
<!-- https://005419-marketing.bpmonline.com/0/ServiceModel/AuthService.svc/Login/-->
