<?php
session_start();
require '../traitement/connect.php';
$location = "../listeretraitMensualite.php";
$dater = date('Y-m-d');
$anne = date('Y');
$pst = $con->prepare("SELECT * from retraits_mensuels");
$pst->execute();
$value = $pst->fetchAll(PDO::FETCH_ASSOC);

if ($value) {

    $pst = $con->prepare("SELECT SUM(montant) as tot from cotisation");
    $pst->execute();

    $values = $pst->fetch(PDO::FETCH_ASSOC)['tot'];

    if ($values) {

        $total = $values;

        $pst = $con->prepare("SELECT SUM(montant) as tota from retraits_mensuels ");
        $pst->execute();
        $rs = $pst->fetch(PDO::FETCH_ASSOC)['tota'];
        if ($rs) {

            $retrait_total = $rs;
            $reste = $total - $retrait_total - (int) $_POST['montant'];
            $restes = $total - $retrait_total;
            if ($restes >= (int) $_POST['montant']) {

                $pst = $con->prepare("INSERT into retraits_mensuels VALUES(null,:montant,:restant,:date_retrait,:description,:anne)");
                $pst->execute([
                    "montant" => $_POST['montant'],
                    "restant" => $reste,
                    "date_retrait" => $dater,
                    "description" => $_POST['motif'],
                    "anne" => $anne
                ]);

                $_SESSION['message'] = "Retrait effectue avec success";
                header('location:' . $location);

            } else {
                $location = "../retraitMensualite.php";
                $_SESSION['error']="Désolé,somme insuffisante";
                header('location:' . $location);
                
            }

        }

    }
} else {
    

    $pst = $con->prepare("SELECT sum(montant) as tot from cotisation");
    $pst->execute();
    $value = $pst->fetch(PDO::FETCH_ASSOC)['tot'];

    if ($value) {


        $tots = $value;

        if ($tots >= (int) $_POST['montant']) {
            $montant = $value - (int) $_POST['montant'];



            $pst = $con->prepare("INSERT into retraits_mensuels VALUES(null,:montant,:restant,:date_retrait,:description,:anne)");
            $pst->execute([
                "montant" => $_POST['montant'],
                "restant" => $montant,
                'date_retrait' => $dater,
                "description" => $_POST['motif'],
                "anne" => $anne
            ]);
            $_SESSION['message'] = "Retrait effectue avec success";
            header('location:' . $location);
        } else {
            $location = "../retraitMensualite.php";
            $_SESSION['error']="Somme insuffisante";
            header('location:' . $location);
           
        }

    }

}

?>