<?php

    require_once "config.php";
    $contentArray = BPM::getRecords('ContactCollection');

    // form handling !
if(isset($_POST['name']) && isset($_POST['phone'])){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $contact = new BPM();
    $contact->name = $name;
    $contact->phone = $phone;
    $contact->collection = 'ContactCollection';
    if(isset($_POST['guid'])){
        $guid = $_POST['guid'];
        $contact->guid = $guid;
    }
    $contact->saveRecord();

    header("Refresh: .2;url='http://bpmonline.loc'");
//    http://profi.artorg.com.ua/
//    http://bpmonline.loc
}

if(isset($_GET['guid_delete'])){
    $guid = $_GET['guid_delete'];
    BPM::deleteRecord('ContactCollection',$guid);
    header("Refresh: .2;url='http://bpmonline.loc'");
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>bpm'online</title>
</head>
<body class="container">
<br/>
<h1 style="text-align: center">BPM'ONLINE with PHP (CRUD Operations)</h1><br/><br/>
<div class="row">

    <div class="col-md-6">
        <h2>BPM'Online Form</h2><hr/>

        <h2>Add/Update Contact</h2>

        <form action="" method="post">
            <div class="form-group">
                <label> Name </label>
                <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
            </div>
            <div class="form-group">
                <label> Phone </label>
                <input type="text" class="form-control" id="phone" placeholder="Enter Phone" name="phone">
            </div>
            <div class="form-group">
                <label> To Update contact Enter Guid : </label>
                <input type="text" class="form-control" id="guid" placeholder="Enter ID" name="guid">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <div class="col-md-6">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Guid</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php for($i=0;$i<count($contentArray['FEED']['ENTRY']);$i++){ ?>
                <tr>
                    <th scope="row"><?=$i?></th>
                    <td><?=$contentArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:NAME']?></td>
                    <td><?=$contentArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:ID']['content']?></td>
                    <td><a class="btn" onclick="return confirm('Are you sure you want to delete?');" href="http://bpmonline.loc?guid_delete=<?=$contentArray['FEED']['ENTRY'][$i]['CONTENT'][$i]['M:PROPERTIES'][$i]['D:ID']['content']?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                </tr>
            <?  }?>
            </tbody>
        </table>
    </div>
</div>





</body>
</html>



