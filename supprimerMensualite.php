<?php
session_start();
require_once './traitement/connect.php';


$anne = date('Y');


$pst = $con->prepare("DELETE FROM cotisation  where id_cotisation=:id_cotisation");
$pst->execute([
    "id_cotisation" => intval(htmlspecialchars($_GET['id']))
]);


$pst = $con->prepare("SELECT SUM(montant) as mt from cotisation where id_membre=:id_m and anne=:anne");
$pst->execute([
    "id_m" => $_POST['idm'],
    "anne" => $anne
]);


$tot = $pst->fetch(PDO::FETCH_ASSOC)['mt'];

$pst = $con->prepare("UPDATE cotisationtotal set montant=:montant where id_m=:id_m and anne=:anne");
$pst->execute([
    "id_m" => $_POST['idm'],
    "anne" => $anne,
    "montant" => $tot
]);

$_SESSION['message'] = "Suppression effectué avec succes";
$location = './mensualites.php';
header('location:' . $location);



?>