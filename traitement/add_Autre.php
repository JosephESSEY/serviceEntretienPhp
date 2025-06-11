<?php
session_start();
require_once './connect.php';

$location = '../particpation_autre.php';

$montant = $_POST['montant'];
$idm = $_POST['idm'];
$idcot = $_POST['idcot'];
$dates = date("Y-m-d");



if (empty($_POST['montant'])) {
    $pst = $con->prepare('SELECT * FROM autres_cotisations WHERE id_ac=:id_ac');
    $pst->execute([
        "id_ac" => $_POST['idcot']
    ]);

    $value = $pst->fetch(PDO::FETCH_ASSOC);
    $montant1 = $value['montant_acotisation'];

    $pst = $con->prepare('INSERT INTO contributions_cotisations VALUES(null, :cotisation_id, :membres_id, :montant, :date_contribution)');
    $pst->execute([
        "cotisation_id" => $idcot,
        "membres_id" => $idm,
        "montant" => $montant1,
        "date_contribution" => $dates
    ]);
    $_SESSION['message'] = "Enrégistrement effectué avec success";
    header("location:" . $location);
} else {
    $pst = $con->prepare('INSERT INTO contributions_cotisations VALUES(null, :cotisation_id, :membres_id, :montant, :date_contribution)');
    $pst->execute([
        "cotisation_id" => $idcot,
        "membres_id" => $idm,
        "montant" => $montant,
        "date_contribution" => $dates
    ]);
    $_SESSION['message'] = "Enrégistrement effectué avec success";
    header("location:" . $location);
}







?>