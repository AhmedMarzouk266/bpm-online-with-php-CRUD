<?php
/**
 * Created by PhpStorm.
 * User: amarz
 * Date: 28.11.2017
 * Time: 10:48
 */

class BPM
{
    public $name ;
    public $phone;
    public $guid ;

    public static function getRecords($collection)
    {

        $curl = curl_init();
        $baseUrl = URL . $collection;

        curl_setopt_array($curl, array(
            CURLOPT_USERPWD => BPM_USER.":".BPM_PWD,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $baseUrl
        ));
        // save results to $resp :
        $resp = curl_exec($curl);

        // save results to XML file :
        $file = 'resultsFile.xml';
        if ($handle = fopen($file, 'wt')) {
            fwrite($handle, $resp);
            fclose($handle);
        } else {
            echo "could not open the file";
        }
        curl_close($curl);

        $id = '8a465f1c-6793-4e2d-99c9-b701fc7534fd';
        $id_exists = self::checkId($id);

        if ($id_exists) {
            echo "true";
        } else {
            echo "false";
        };

    }

    public function createRecord($collection)
    {


        $contact_xml = $this->getXml();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_USERPWD => BPM_USER.":".BPM_PWD,
            CURLOPT_HTTPHEADER => array('Content-Type:application/atom+xml;type=entry', 'Accept:application/atom+xml'),
            CURLOPT_POSTFIELDS => $contact_xml,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => URL . $collection,

        ));

        $result = curl_exec($curl);
        curl_close($curl);

        echo "<pre>$result</pre>";
    }

    public function updateRecord($collection){
        $url = URL.$collection."(guid'{$this->guid}')";
        $contact_xml = $this->getXml();
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
    }

    public static function deleteRecord($collection,$guid){

        $url = URL.$collection."(guid'{$guid}')";

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_USERPWD => BPM_USER.":".BPM_PWD,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER =>array('Content-Type:application/atom+xml;type=entry','Accept:application/atom+xml'),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url

        ));

        $result = curl_exec($ch);
        curl_close($ch);

        echo "<pre>$result</pre>";
    }

    public static function checkId($id)
    {
        // this function takes the id and search for the id in the recieved data
        // if the id exists it return TRUE if not it returns FALSE.
        if (strlen($id) != 36) {
            return false;
        } else {
            $handle = fopen('resultsFile.xml', 'r');
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

    public function getXml(){
        $contact_xml = '<?xml version="1.0" encoding="utf-8"?>
                <entry xmlns="http://www.w3.org/2005/Atom">
                    <content type="application/xml">
                        <properties xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
                            <Name xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">'.$this->name.'</Name>
                            <Phone xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">'.$this->phone.'</Phone>
                        </properties>
                    </content>
                </entry>';
        return $contact_xml;
    }
}
