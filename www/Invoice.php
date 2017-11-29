<?php
/**
 * Created by PhpStorm.
 * User: amarz
 * Date: 29.11.2017
 * Time: 12:39
 */

class Invoice extends BPM
{
    public $number;
    public $collection = 'InvoiceCollection';
    public $amount;
    public $doctorId;
    public $contactId;

    public function createInvoice()
    {

        $invoice_xml = $this->getXmlInvoice();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_USERPWD => BPM_USER . ":" . BPM_PWD,
            CURLOPT_HTTPHEADER => array('Content-Type:application/atom+xml;type=entry', 'Accept:application/atom+xml'),
            CURLOPT_POSTFIELDS => $invoice_xml,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => URL . $this->collection,

        ));

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function getXmlInvoice()
    {
        $invoice_xml = '<?xml version="1.0" encoding="utf-8"?>
                <entry xmlns="http://www.w3.org/2005/Atom">
                    <content type="application/xml">
                        <properties xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices/metadata">
                            <Number xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">' . $this->number . '</Number>
                            <Amount xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">' . $this->amount . '</Amount >
                            <UsrDoctorId xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">' . $this->doctorId . '</UsrDoctorId >
                            <ContactId xmlns="http://schemas.microsoft.com/ado/2007/08/dataservices">' . $this->contactId . '</ContactId >
                        </properties>
                    </content>
                </entry>';
        return $invoice_xml;
    }

    public static function getInvoiceData()
    {
        // i need an array of objects !
        $invoicesArray = BPM::getRecords('InvoiceCollection');
        for ($i = 0; $i < count($invoicesArray['FEED']['ENTRY']); $i++) {
            $object[$i]['number']    = $invoicesArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:NUMBER'];
            $object[$i]['id']        = $invoicesArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:ID']['content'];
            $object[$i]['amount']    = $invoicesArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:AMOUNT']['content'] ;

            $doctorId   = $invoicesArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:USRDOCTORID']['content'];
            $doctorData = BPM::getContactNameAndIdByGuid($doctorId);
            $object[$i]['doctor']    = $doctorData['name'];
            $object[$i]['doctorId']  =  $doctorId;

            $contactId   =  $invoicesArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:CONTACTID']['content'];
            $contactData = BPM::getContactNameAndIdByGuid($contactId);
            $object[$i]['contact']    = $contactData['name'];
            $object[$i]['contactId']  = $contactId;

        }

        return $object;
    }

}
