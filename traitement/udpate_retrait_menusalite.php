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

                $pst = $con->prepare("UPDATE retraits_mensuels SET montant=:montant,restant=:restant WHERE id_rm=:id");
                $pst->execute([
                    "montant" => $_POST['montant'],
                    "restant" => $restes,
                    "id" => intval(htmlspecialchars($_GET['id']))

                ]);

                $_SESSION['message'] = "Modification effectue avec success";
                header('location:' . $location);

            } else {
                $location = "../retraitMensualite.php";
                $_SESSION['error']="Désolé,la somme est insuffisante";
                header('location:' . $location);
           
            }

        }

    }
}
?>