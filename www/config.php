<?php

// automatically load classes !
spl_autoload_register(function($classname){
// runs when you make a class
    $file_name = ROOT."/{$classname}.php";
    $file_name = str_replace('\\',"/",$file_name);
    if(file_exists($file_name)){
        require_once ($file_name);
    }
});

ob_start();
ini_set('max_execution_time', 300);

define('ROOT',$_SERVER["DOCUMENT_ROOT"]);
define('URL','https://014100-sales-enterprise.bpmonline.com/0/ServiceModel/EntityDataService.svc/');
define('BPM_USER','Ahmed_Marzouk');
define('BPM_PWD','Artorg123');

?>