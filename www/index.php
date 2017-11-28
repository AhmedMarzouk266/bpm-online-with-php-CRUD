<?php

    require_once "config.php";

    BPM::getRecords('ContactCollection');

    $contact = new BPM();

    $contact->name='Test 24';
    $contact->phone='000 000 000';
    $contact->createRecord('ContactCollection');

    $guid='7dbe5aad-e7c8-4252-a81a-1660895d62db';
    //a88baee0-ef60-4f4b-92c5-a48c58915221 // messi
    //8a465f1c-6793-4e2d-99c9-b701fc7534fd // new post contact
    BPM::deleteRecord('ContactCollection',$guid);

    $contact2 = new BPM();
    $contact2->guid ='4130dbdf-e0a9-415e-b795-417b30efb355';
    $contact2->name='Updated oop contact';
    $contact2->updateRecord('ContactCollection')


?>

