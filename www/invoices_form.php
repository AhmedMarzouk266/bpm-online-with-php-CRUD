<?php
// invoices form processing :

require_once "config.php";
$invoicesData  = Invoice::getInvoiceData();
$contactsData  = BPM::getContactData();



if (isset($_POST['number'])) {

    $invoice = new Invoice();
    $invoice->doctorId  = $_POST['doctorId'];
    $invoice->contactId = $_POST['contactId'];
    $invoice->number    = $_POST['number'];
    $invoice->amount    = $_POST['amount'];

    if (isset($_POST['guid'])){
        $guid = $_POST['guid'];
        $invoice->guid = $guid;
    }

    $invoice->saveInvoice();

    //refresh the page :
    header("Refresh: .1;url='http://bpmonline.loc/invoices_form.php'");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
          integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

    <title>test invoices</title>
</head>
<body class="container">
<br/>
<h1 style="text-align: center">BPM'ONLINE with PHP (CRUD Operations) INVOICES</h1><br/><br/>

<div class="row">

    <div class="col-md-4">
        <h2>BPM'Online Form</h2>
        <hr/>
        <h2>Add/Update Invoice</h2>

        <form action="" method="post">
            <div class="form-group">
                <label> Number </label>
                <input type="text" class="form-control" id="number" placeholder="Enter Number" name="number">
            </div>
            <div class="form-group">
                <label> Amount </label>
                <input type="text" class="form-control" id="amount" placeholder="Enter Amount in Dollars" name="amount">
            </div>
            <div class="form-group">
                <label> Doctor </label>
                <select name="doctorId" class="custom-select form-control">
                    <?php foreach ($contactsData as $contact){?>
                        <option value="<?=$contact['id']?>"><?=$contact['name']?></option>
                    <?}?>
                </select>
            </div>
            <div class="form-group">
                <label> Contact </label>
                <select name="contactId" class="custom-select form-control">
                    <?php foreach ($contactsData as $contact){?>
                        <option value="<?=$contact['id']?>"><?=$contact['name']?></option>
                    <?}?>
                </select>
            </div>
            <div class="form-group">
                <label> To Update Invoice Enter Guid for the Invoice : </label>
                <input type="text" class="form-control" id="guid" placeholder="Enter ID" name="guid">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <div class="col-md-8">
        <h2>All Invoices</h2>

        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Number</th>
                <th>Invoice ID</th>
                <th>Amount</th>
                <th>Doctor</th>
                <th>Contact</th>
            </tr>
            </thead>
            <tbody>
            <?php $i=1;foreach ($invoicesData as $invoice) { ?>
                <tr>
                    <th scope="row"><?= $i ?></th>
                    <td><?=$invoice['number'] ?></td>
                    <td><?=$invoice['id'] ?></td>
                    <td><?=$invoice['amount'] ?></td>
                    <td><?=$invoice['doctor']?></td>
                    <td><?=$invoice['contact']?></td>
                </tr>
            <? $i++; } ?>
            </tbody>
        </table>

    </div>

</div>
</body>
</html>
