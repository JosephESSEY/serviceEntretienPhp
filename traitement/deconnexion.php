<?php
session_start();
$_SESSION['nom'] = [];
$_SESSION['prenom'] = [];
$_SESSION['message'] = [];
$_SESSION["error"] = [];
$_SESSION["profil"] = [];
session_destroy();

$location = '../login.php';
header('location:'.$location);
?>