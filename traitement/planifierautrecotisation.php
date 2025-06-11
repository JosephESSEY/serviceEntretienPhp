<?php
session_start();
require './connect.php';
$motif = $_POST['motif'];
$montant = $_POST['montant'];
$datepayements = date('Y-m-d');
$anne = date('Y');

$pst = $con->prepare("SELECT * from autres_cotisations where motif_acotisation=:motif");
$pst->execute([
    "motif" => $motif
]);

$exist = $pst->fetch(PDO::FETCH_ASSOC);


if ($exist) {
    $_SESSION['error'] = "Désolé,la cotisation existe déja";
    $location = "../planifier.php";
    header("location:" . $location);
} else {
    $pst = $con->prepare(  "INSERT INTO autres_cotisations VALUES(null,:motif_acotisation, :date_acot, :montant_acotisation, :anne)");
    $pst->execute([
        "motif_acotisation" => $motif,
        "date_acot" => $datepayements,
        "montant_acotisation" => $montant,
        "anne" => $anne

    ]);
    $location = "../listeplanification.php";
    $_SESSION['message']="Enrégistrement effectué avec success";
    header("location:" . $location);
}
?>