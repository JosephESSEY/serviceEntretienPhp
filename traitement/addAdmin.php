<?php
session_start();
require 'connect.php';

$pst = $con->prepare('SELECT * FROM admin WHERE membreId=:id');
$pst->execute([
    "id" => $_POST['idm']
]);
$exist = $pst->fetch(PDO::FETCH_ASSOC);

if ($exist) {
    $_SESSION['error'] = "Le compte existe déja";
    $location = '../ajouterAdmin.php';
    header('location:' . $location);
} else {
    $passord = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $pst=$con->prepare('INSERT INTO admin VALUES(null,:membreId,:password)');
    $pst->execute([
        "membreId" => $_POST["idm"],
        "password" => $passord
    ]);
    $_SESSION['message'] = "Enrégsitrement effectué avec succes";
    $location = '../ajouterAdmin.php';
    header('location:' . $location);
}

?>