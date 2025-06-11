<?php
session_start();
require './connect.php';
$location = "../login.php";

$pst = $con->prepare("UPDATE admin SET password=:password");
$pst->execute([
    "password" => password_hash($_POST['password'], PASSWORD_BCRYPT)
]);
$_SESSION['nom'] = [];
$_SESSION['prenom'] = [];
$_SESSION["error"] = [];
$_SESSION["profil"] = [];
session_destroy();

$_SESSION['message'] = 'Mot de passe modifier avec success';
header('location:' . $location);






?>