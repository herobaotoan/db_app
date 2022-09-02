<?php
$user = 'root';
$pass = '123456';
global $pdo;
$pdo = new PDO('mysql:host=localhost;dbname=lazada', $user, $pass);

session_start();
$_SESSION['loggedin'] = false;
$invalid = false;
if (!empty($_POST)) {
    //Register
    switch ($_POST) {
    case isset($_POST['A']):
        $password = $_POST['password'];
        $username = strtolower($_POST['username']);
        $name = $_POST['name'];
        $role = (int)$_POST['role'];
        $adress = $_POST['adress'];
    
        //Check username
        $rows = $pdo->query("SELECT Username FROM users");
        foreach ($rows as $row) {
            if ($username == $row['Username']){
                $invalid = true;
                break;
            }
        }
        if ($invalid){
            echo "<script type='text/javascript'>alert('Username has been taken');</script>";
        } else {
            //Create user and assign roles
            $pdo->query("CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';");
            $pdo->query("GRANT ALL PRIVILEGES ON lazada. * TO '$username'@'localhost';");

            //Hash password before store in database
            $password = password_hash($password, PASSWORD_DEFAULT);
            if ($role == 3) {
                $pdo->query("INSERT INTO users (Uname, Username, Pwd, URole) VALUES ('$name','$username','$password',$role);");
            } else {
                $pdo->query("INSERT INTO users (Uname, Username, Pwd, URole, Adress) VALUES ('$name','$username','$password',$role,'$adress');");
            }
            echo "<script type='text/javascript'>alert('Registation completed!');</script>";
        }
    break;
    case isset($_POST['B']):
        $password = $_POST['password'];
        $username = strtolower($_POST['username']);
        //Check username and password in database
        $rows = $pdo->query("SELECT * FROM users");
        foreach ($rows as $row) {
            if (($username == $row['Username']) and (password_verify($password, $row['Pwd']))){
                $invalid = true;
                break;
            }
        }
        if ($invalid){
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $username;
            $_SESSION['pwd'] = $password;
            header('location: main.php');
        } else {
            echo "<script type='text/javascript'>alert('Username or Password incorrect');</script>";
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
    <!-- Register form -->
    <div class="container-fluid bg-primary text-light p-2">
        <h1>Resgister</h1>
    </div>
    <form method="post" action="login.php">
        <div class="container-fluid p-2">
        <div class="row gx-5">
            <div class="col-1">
            <label for="Username" class="form-label">Username:</label>
            </div>
            <div class="col-3 pb-3">
            <input type="text" class="form-control" name="username" required>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-1">
            <label for="Name" class="form-label">Name:</label>
            </div>
            <div class="col-3 pb-3">
            <input type="text" class="form-control" name="name" required>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-1">
            <label for="Password" class="form-label">Password:</label>
            </div>
            <div class="col-3 pb-3">
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-1">
            <label for="Role" class="form-label">Role:</label>
            </div>
            <div class="col-3 pb-3">
            <select name="role" id="role">
                <option value="1">Vendor</option>
                <option value="2">Customer</option>
                <option value="3">Shipper</option>
            </select>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col-1">
            <label for="Adress" class="form-label">Adress:</label>
            </div>
            <div class="col-3 pb-3">
            <input type="text" class="form-control" name="adress" placeholder="Leave a blank if you want to register as a Shipper">
            </div>
        </div>
        <div class="row gx-5">
            <div class = "col-1">
            <input type="submit" name="A" value="Register" class="btn btn-primary">
            </div>
        </div>
        </div>   
    </form>
    <hr>
    <!-- Login form -->
    <div class="container-fluid bg-primary text-light p-2">
      <h1>Login</h1>
    </div>
    <form method="post" action="login.php">
        <div class="container-fluid p-2">
          <div class="row gx-5">
            <div class="col-1">
              <label for="username" class="form-label">Username:</label>
            </div>
            <div class="col-3 pb-3">
              <input type="text" class="form-control" placeholder="Username" name="username" required>
            </div>
          </div>
          <div class="row gx-5">
            <div class="col-1">
              <label for="Password" class="form-label">Password:</label>
            </div>
            <div class="col-3 pb-3">
              <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
          </div>
          <div class="row gx-5">
            <div class = "col-1">
              <input type="submit" name="B" value="Log In" class="btn btn-primary">
            </div>
          </div>
        </div>   
    </form>

</body>
</html>