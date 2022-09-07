<?php
//Go to login page if not logged-in
session_start();
//Default display query for customer
$PageNo = 50;
$where = "WHERE Pname LIKE '%%'";
$and = "";
$attributeSearch = false;
$keySearch = '';
$valueSearch = '';
//
if (!$_SESSION['loggedin']){
    header('location: login.php');
}
$Uid = $_SESSION['id'];

global $pdo;
$pdo = new PDO('mysql:host=localhost;dbname=lazada', $_SESSION['username'], $_SESSION['pwd']);

require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->lazada->products;
// echo $_SESSION['id'];
// echo $_SESSION['pwd'] . '-';
// echo $_SESSION['name'];
if (!empty($_POST)) {
    switch ($_POST) {
    //Add Product
    case isset($_POST['A']):
        $name = $_POST['name'];
        $price = $_POST['price'];
        // echo $name . '-' . $price . '-' . $Uid;
        $pdo->query("INSERT INTO products (Pname, Price, Vid) VALUES ('$name',$price,$Uid);");

    break;
    //Edit Prouct
    case isset($_POST['B']):
        $Pid = $_POST['id'];
        $Pname = $_POST['name'];
        $Price = $_POST['price'];
        //Check if product is ordered
        if ($_POST['status'] == 'Available'){
            $pdo->query("UPDATE products SET Pname = '$Pname', Price = $Price WHERE Pid = $Pid;");
            echo "<script type='text/javascript'>alert('Product edited successful!');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Product cannot be edited at current status');</script>";
        }
    break;
    case isset($_POST['Delete_info']):
        if ($_POST['status'] != 'Available'){
            echo "<script type='text/javascript'>alert('Product cannot be edited at current status');</script>";
        } else {
            $collection->updateOne(
                [$_POST['key']=>$_POST['value']],
                ['$unset'=> [
                    $_POST['key'] => null
                ]]
            );
        }
    break;
    case isset($_POST['Add_info']):
        if ($_POST['status'] != 'Available'){
            echo "<script type='text/javascript'>alert('Product cannot be edited at current status');</script>";
        } else {
            if (!$_POST['newly_added']){
                $collection->updateOne(
                    ['Pid'=>(int)$_POST['id']],
                    ['$set'=> [
                        $_POST['key'] => $_POST['value']
                    ]]
                );
            } else {
                $collection->insertOne(
                    ['Pid' => (int)$_POST['id'], $_POST['key'] => $_POST['value']]
                );
            }
        }
    break;
    //Buy Product
    case isset($_POST['C']):
        $Pid = $_POST['id'];
        $Status = $_POST['status'];
        if ($Status != 'Available'){
            echo "<script type='text/javascript'>alert('Product cannot be Purchased at current status');</script>";
        } else {
            $Status = "Ordered";
            $pdo->query("INSERT INTO orders VALUES ($Pid, $Uid, '$Status');");
        }
    break;
    case isset($_POST['PageNo']):
        $PageNo = $_POST['PageNo'];
    break;
    case isset($_POST['search_name']):
        $PrName = $_POST['name'];
        $where = "WHERE Pname LIKE '%$PrName%'";
    break;
    case isset($_POST['search_price']):
        $Pricelte = $_POST['price1'];
        $Pricegte = $_POST['price2'];
        if ($Pricegte == null && $Pricelte == null){
            $and = "";
        }
        else if ($Pricelte == null){
            $and = "AND Price >= $Pricelte";
        } else if ($Pricegte == null) {
            $and = "AND Price <= $Pricegte";
        } else {
            $and = "AND Price >= $Pricelte AND Price <= $Pricegte";
        }
    break;
    case isset($_POST['search_attribute']):
        $keySearch = $_POST['key'];
        $valueSearch = $_POST['value'];
        if ($keySearch == '') {
            $attributeSearch = false;
        } else { $attributeSearch = true; }
    break;
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Fake Lazada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container-fluid bg-success text-light p-2">
        <div class="row">
            <h1 class="col-auto">Welcome <?php echo $_SESSION['name'] ?></h1>
            <div class="col-auto">
                <a href='/login.php' class="btn btn-light">Log Out</a>
            </div>
        </div>
    </div>
    <?php
    //Vendor
    if ($_SESSION['role'] == 1){
        require('vendor.php');
    }
    //Customer
    if ($_SESSION['role'] == 2){
        require('customer.php');
    }
    ?>
</body>
</html>
