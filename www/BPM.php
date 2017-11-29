<?php
/**
 * Created by PhpStorm.
 * User: amarz
 * Date: 28.11.2017
 * Time: 10:48
 */

class BPM
{
    public $collection;
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
        curl_close($curl);
        $resultArray = self::XMLtoArray($resp);
        return $resultArray;

    }

    public static function getRecordByGuid($collection,$guid){
        $curl = curl_init();
        $baseUrl = URL . $collection.'?$filter=Id+eq+guid'."'".$guid."'"; curl_setopt_array($curl, array(
            CURLOPT_USERPWD => BPM_USER.":".BPM_PWD,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $baseUrl
        ));
        // save results to $resp :
        $resp = curl_exec($curl);
        curl_close($curl);
        $contentArray = BPM::XMLtoArray($resp);
        return $contentArray;
    }

    public static function getContactNameAndIdByGuid($guid){
        $contactRecord   = self::getRecordByGuid('ContactCollection',$guid);
        $contact         = [];
        $contact['name'] = $contactRecord['FEED']['ENTRY']['CONTENT']['M:PROPERTIES']['D:NAME'];
        $contact['id']   = $contactRecord['FEED']['ENTRY']['CONTENT']['M:PROPERTIES']['D:ID']['content'];
        return $contact;
    }

    public static function getContactData()
    {
        // i need an array of objects !
        $contactsArray = BPM::getRecords('ContactCollection');
        for ($i = 0; $i < count($contactsArray['FEED']['ENTRY']); $i++) {
            $object[$i]['name'] = $contactsArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:NAME'];
            $object[$i]['id']   = $contactsArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:ID']['content'];
        }
        return $object;
    }


    private function createRecord()
    {


        $contact_xml = $this->getXml();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_USERPWD => BPM_USER.":".BPM_PWD,
            CURLOPT_HTTPHEADER => array('Content-Type:application/atom+xml;type=entry', 'Accept:application/atom+xml'),
            CURLOPT_POSTFIELDS => $contact_xml,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => URL . $this->collection,

        ));

        $result = curl_exec($curl);
        curl_close($curl);

        //echo "<pre>$result</pre>";
    }

    private function updateRecord(){
        $url = URL.$this->collection."(guid'{$this->guid}')";
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
        //echo "<pre>$result</pre>";
    }

    public function saveRecord(){
        $id_exists = self::checkId($this->guid);
        if($id_exists){
            $this->updateRecord();
        }else{
            $this->createRecord();
        }
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


    public static function XMLtoArray($XML)
    {
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $XML, $vals);
        xml_parser_free($xml_parser);
        // wyznaczamy tablice z powtarzajacymi sie tagami na tym samym poziomie
        $_tmp='';
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_level!=1 && $x_type == 'close') {
                if (isset($multi_key[$x_tag][$x_level]))
                    $multi_key[$x_tag][$x_level]=1;
                else
                    $multi_key[$x_tag][$x_level]=0;
            }
            if ($x_level!=1 && $x_type == 'complete') {
                if ($_tmp==$x_tag)
                    $multi_key[$x_tag][$x_level]=1;
                $_tmp=$x_tag;
            }
        }
        // jedziemy po tablicy
        foreach ($vals as $xml_elem) {
            $x_tag=$xml_elem['tag'];
            $x_level=$xml_elem['level'];
            $x_type=$xml_elem['type'];
            if ($x_type == 'open')
                $level[$x_level] = $x_tag;
            $start_level = 1;
            $php_stmt = '$xml_array';
            if ($x_type=='close' && $x_level!=1)
                $multi_key[$x_tag][$x_level]++;
            while ($start_level < $x_level) {
                $php_stmt .= '[$level['.$start_level.']]';
                if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                    $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
                $start_level++;
            }
            $add='';
            if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
                if (!isset($multi_key2[$x_tag][$x_level]))
                    $multi_key2[$x_tag][$x_level]=0;
                else
                    $multi_key2[$x_tag][$x_level]++;
                $add='['.$multi_key2[$x_tag][$x_level].']';
            }
            if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
                if ($x_type == 'open')
                    $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                else
                    $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            if (array_key_exists('attributes', $xml_elem)) {
                if (isset($xml_elem['value'])) {
                    $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                    eval($php_stmt_main);
                }
                foreach ($xml_elem['attributes'] as $key=>$value) {
                    $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                    eval($php_stmt_att);
                }
            }
        }
        return $xml_array;
    }

}
