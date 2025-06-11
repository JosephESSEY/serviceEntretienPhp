<?php
session_start();
require './connect.php';
$motif = $_POST['motif'];
$montant = $_POST['montant'];
$datepayements = date('Y-m-d');
$anne = date('Y');

if(!empty($_GET['id']) && $_GET['id']>0){
    $id=intval(htmlspecialchars($_GET['id']));
}



    $pst = $con->prepare(  "UPDATE autres_cotisations SET motif_acotisation=:motif_acotisation,date_acot=:date_acot,montant_acotisation=:montant_acotisation,anne=:anne WHERE id_ac=:id_ac");
    $pst->execute([
        "motif_acotisation" => $motif,
        "date_acot" => $datepayements,
        "montant_acotisation" => $montant,
        "anne" => $anne,
        "id_ac"=>$id

    ]);
    $location = "../listeplanification.php";
    $_SESSION['message']='Modification effectué avec success';
    header("location:" . $location);
?>