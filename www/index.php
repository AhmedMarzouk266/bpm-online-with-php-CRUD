<?php

    require_once "config.php";

//    BPM::getRecords('ContactCollection');

    $contact = new BPM();
    $contact->collection='ContactCollection';
    $contact->guid ='4130dbdf-e0a9-415e-b795-417b30efb359';
    $contact->name='Test 25';
    $contact->phone='000 000 000';
    $contact->saveRecord();

//    $guid='a88baee0-ef60-4f4b-92c5-a48c58915221'; // messi
//    BPM::deleteRecord('ContactCollection',$guid);

//    $contact2 = new BPM();
//    $contact2->collection='ContactCollection';
//    $contact2->guid ='4130dbdf-e0a9-415e-b795-417b30efb355';
//    $contact2->name='Updated oop contact 2';
//    $contact2->saveRecord();

?>

