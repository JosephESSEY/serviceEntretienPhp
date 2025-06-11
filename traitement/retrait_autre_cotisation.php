<?php
session_start();
require './connect.php';

$dates = date('Y-m-d');
$anne = date('Y');

$pst = $con->prepare("SELECT * from autres_cotisations where motif_acotisation=:mot");
$pst->execute([
    "mot" => $_POST['cotcon']
]);

$exist = $pst->fetch(PDO::FETCH_ASSOC);

if ($exist) {
    $id_ac = $exist['id_ac'];
    $pst = $con->prepare("SELECT * from retraits_evenements where id_cot=:id_cot");
    $pst->execute([
        "id_cot" => $_POST['id_cot_con']
    ]);

    $value = $pst->fetch(PDO::FETCH_ASSOC);

    if ($value) {
        $pst = $con->prepare("SELECT sum(montant) as tot from retraits_evenements where id_cot=:idcot");
        $pst->execute([
            "idcot" => $id_ac
        ]);

        $values = $pst->fetch(PDO::FETCH_ASSOC);

        if ($values) {
            $retrait_total = $values['tot'];
            $pst = $con->prepare("SELECT sum(montant) as tots from contributions_cotisations where cotisation_id=:idac");
            $pst->execute([
                "idac" => $id_ac
            ]);

            $res = $pst->fetch(PDO::FETCH_ASSOC);

            if ($res) {
                $total_autre_cotisation = $res['tots'];
                $reste = $total_autre_cotisation - $retrait_total;

                if ($reste >= $_POST['montant']) {

                    $pst = $con->prepare("INSERT into retraits_evenements VALUES(null,:id_cot,:montant,:date_retrait,:description,:anne)");
                    $pst->execute([
                        "id_cot" => $id_ac,
                        "montant" => $_POST['montant'],
                        "date_retrait" => $dates,
                        'description' => $_POST['motif'],
                        "anne" => $anne
                    ]);
                    $_SESSION['message'] = "Retrait effectué avec succes";
                    $location = '../liste_retrait_autres_cotisations.php';
                    header('location:' . $location);


                } else {
                    $_SESSION['error'] = "Somme insuffisante";
                    $location = "../retrait_autres_cotisations.php";
                    header('location:' . $location);

                }
            }

        }
    } else {

        $pst = $con->prepare("SELECT sum(montant) as tots from contributions_cotisations where cotisation_id=:cotisation_id");
        $pst->execute([
            "cotisation_id" => $_POST['id_cot_con']
        ]);

        $value = $pst->fetch(PDO::FETCH_ASSOC);

        if ($value) {
            $total_autre_cotisation = $value['tots'];
            $reste = $total_autre_cotisation - $_POST['montant'];
            if ($total_autre_cotisation >= $_POST['montant']) {

                $pst = $con->prepare("INSERT into retraits_evenements VALUES(null,:id_cot,:montant,:date_retrait,:description,:anne) ");
                $pst->execute([
                    "id_cot" => $id_ac,
                    "montant" => $_POST['montant'],
                    "date_retrait" => $dates,
                    'description' => $_POST['motif'],
                    "anne" => $anne
                ]);
                $_SESSION['message'] = "Retrait effectué avec succes";
                $location = '../liste_retrait_autres_cotisations.php';
                header('location:' . $location);

            } else {
                $_SESSION['error']= "Somme insuffisante";
                $location = "../retrait_autres_cotisations.php";
                header('location:' . $location);
            }
        }




    }
}



?>