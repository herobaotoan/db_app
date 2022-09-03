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
        <h1>Welcome <?php echo $_SESSION['name'] ?></h1>
    </div>
    <?php
    //If user is vendor: display add product form
    if ($_SESSION['role'] == 2){
        echo <<<GFG
        <div class="container-fluid bg-primary text-light p-2">
            <h1>Add product</h1>
        </div>
        <form method="post" action="main.php">
            <div class="container-fluid p-2">
            <div class="row gx-5">
                <div class="col-1">
                <label for="Name" class="form-label">Name:</label>
                </div>
                <div class="col-3 pb-3">
                <input type="text" class="form-control" placeholder="Ex: Iphone, TV, Car ,etc." name="name" required>
                </div>
            </div>
            <div class="row gx-5">
                <div class="col-1">
                <label for="Price" class="form-label">Price:</label>
                </div>
                <div class="col-3 pb-3">
                <input type="text" class="form-control" placeholder="Ex: 50.5, 99.9, etc." name="price" required>
                </div>
            </div>
            <div class="row gx-5">
                <div class = "col-1">
                <input type="submit" name="A" value="Add Product" class="btn btn-primary">
                </div>
            </div>
            </div>   
        </form>
        <hr>
        GFG;

        //Display vendor's product
        echo <<<GFG
        <div class="container-fluid bg-primary text-light p-2">
        <h1>List of Products</h1>
        </div>
        <table class="table table-striped table-info">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        GFG;
        //Get product's information
        $rows = $pdo->query("SELECT * FROM products WHERE Vid = $Uid;");
        foreach ($rows as $row) {
            //Get product's status
            $id = $row['Pid'];
            $status = 'Available';
            $rows2 = $pdo->query("SELECT * FROM orders WHERE Pid = $id;");
            foreach ($rows2 as $row2) {
                if ($row2['Pid'] == $id){
                    $status = $row2['Pstatus'];
                }
            }
            $Pname = $row['Pname'];
            $price = $row['Price'];
            echo<<<GFG
                    <tr>
                    <form method="post" action="main.php">
                        <td>
                            $id
                            <input type="hidden" name="id" value=$id>
                        </td>
                        <td>
                            $Pname
                            <input type="text" placeholder="Change name" name="name" required>
                        </td>
                        <td>
                            $price
                            <input type="number" placeholder="Change price" name="price" required>
                        </td>
                        <td>
                            $status
                            <input type="hidden" name="status" value=$status>
                        </td>
                        <td><input type="submit" name="B" class="btn btn-success" value="Edit"></td>
                    </form>
                    </tr>
            GFG;
        }
        echo "</tbody></table>";
    }
    ?>
</body>
</html>
