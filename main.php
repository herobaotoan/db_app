<?php
//Go to login page if not logged-in
session_start();
if (!$_SESSION['loggedin']){
    header('location: login.php');
}

global $pdo;
$pdo = new PDO('mysql:host=localhost;dbname=lazada', $_SESSION['id'], $_SESSION['pwd']);
echo $_SESSION['id'];
echo $_SESSION['pwd'];
?>