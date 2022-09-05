<?php
//Go to login page if not logged-in
session_start();
if (!$_SESSION['loggedin']){
    header('location: login.php');
}
$Uid = $_SESSION['id'];

global $pdo;
$pdo = new PDO('mysql:host=localhost;dbname=lazada', $_SESSION['username'], $_SESSION['pwd']);
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
    //Buy Product
    case isset($_POST['C']):
        $Pid = $_POST['id'];
        $Status = $_POST['status'];
        if ($Status != 'Available'){
            echo "<script type='text/javascript'>alert('Product cannot be Purchased at current status');</script>";
        } else {
            //Transaction
            $Status = "Pending";
            $pdo->query("INSERT INTO orders VALUES ($Pid, $Uid, '$Status');");
            for ($time = 30; $time < 0; $time--){
                // echo "<h1>$time</h1>";
                sleep(10);
            }
            // sleep(10);
            $pdo->query("DELETE FROM orders WHERE Pid = $Pid;");
        }
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
    if ($_SESSION['role'] == 2){
        require('vendor.php');
    }
    //Customer
    if ($_SESSION['role'] == 1){
        require('customer.php');
    }
    ?>
</body>
</html>
