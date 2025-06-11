<?php
session_start();
require '../traitement/connect.php';

$pst = $con->prepare("SELECT * FROM contributions_cotisations join autres_cotisations
            ON autres_cotisations.id_ac=contributions_cotisations.cotisation_id 
            JOIN membres on membres.id=contributions_cotisations.membres_id  WHERE cotisation_id=:idcot && membres_id=:idm");
$pst->execute([
    'idcot' => intval($_GET['idcot']),
    'idm' => intval($_GET['idm'])
]);


$value = $pst->fetch(PDO::FETCH_ASSOC);

if (empty($_GET['idcot']) && empty($_GET['idcot']) && !$value) {
    $location = '../liste_participants_autre_cotisations.php';
    header('location:' . $location);
} else {

    if (!empty($_POST['montant'])) {

        $pst = $con->prepare("UPDATE contributions_cotisations SET montant=:montant WHERE id_cont=:idcont");
        $pst->execute([
            "montant" => $_POST['montant'],
            "idcont" => intval(htmlspecialchars($_GET['idcont']))
        ]);

        $_SESSION['message'] = "Modification effectué avec success";
        $location = '../liste_participants_autre_cotisations.php';
        header("location:" . $location);


    } else {
        $pst = $con->prepare('SELECT * FROM autres_cotisations WHERE id_ac=:id_ac');
        $pst->execute([
            "id_ac" => $_POST['idcot']
        ]);

        $value = $pst->fetch(PDO::FETCH_ASSOC);
        $montant1 = $value['montant_acotisation'];

        $pst = $con->prepare("UPDATE contributions_cotisations SET montant=:montant WHERE id_cont=:idcont");
        $pst->execute([
            "montant" => $montant1,
            "idcont" => intval(htmlspecialchars($_GET['idcont']))
        ]);
        $_SESSION['message'] = "Enrégistrement effectué avec success";
        $location = '../liste_participants_autre_cotisations.php';
        header("location:" . $location);

    }
}




?>