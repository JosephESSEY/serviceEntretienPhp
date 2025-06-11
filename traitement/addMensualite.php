<?php
session_start();

require_once './connect.php';

$anne = date('Y');
$pst = $con->prepare("INSERT INTO cotisation VALUES(null,:montant,:datepayement,:anne,:id_membre,:id_mois)");
$pst->execute([
    'montant' => $_POST['montant'],
    "datepayement" => $_POST['datepayement'],
    "anne" => $anne,
    "id_membre" => $_POST['idm'],
    "id_mois" => $_POST['id_mois']
]);

$pst = $con->prepare("SELECT * from cotisationtotal where id_m=:id_m and anne=:anne");
$pst->execute([
    "id_m" => $_POST['idm'],
    "anne" => $anne
]);


$exist = $pst->fetch(PDO::FETCH_ASSOC);


if ($exist) {
    $pst = $con->prepare("UPDATE cotisationtotal set montant=montant+:montant where id_m=:id_m and anne=:anne");
    $pst->execute([
        "id_m" => $_POST['idm'],
        "anne" => $anne,
        "montant" => $_POST['montant']
    ]);
    $location = '../mensualites.php';
    $_SESSION['message']="Modification effectué avec success";
    header('location:' . $location);

} else {
    $pst = $con->prepare("SELECT SUM(montant) as mt from cotisation  where id_membre=:id_membre and anne=:anne");
    $pst->execute([
        "id_membre" => $_POST['idm'],
        "anne" => $anne
    ]);
    $finds = $pst->fetch(PDO::FETCH_ASSOC);
    $total = $finds['mt'];
    if ($finds) {


        $pst = $con->prepare("INSERT into cotisationtotal VALUES(null,:id_m,:montant,:anne)");
        $pst->execute([
            "id_m" => $_POST['idm'],
            "montant" => $total,
            "anne" => $anne
        ]);
        $location = '../mensualites.php';
        $_SESSION['message']="Enrégsitrement effectué avec success";
        header('location:' . $location);
    }
}









?>