<?php
session_start();
require './connect.php';

$pst = $con->prepare("DELETE FROM contributions_cotisations WHERE id_cont=:idcont");
$pst->execute([
    "idcont" => intval(htmlspecialchars($_GET['idcont']))
]);
$_SESSION['message'] = 'Suppression effectue avec success';
$loaction = "../liste_participants_autre_cotisations.php";
header('location:' . $loaction);

?>