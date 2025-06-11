<?php
session_start();
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
    $location = './login.php';
    header('location:' . $location);
}

require './traitement/connect.php';
$pst = $con->prepare('SELECT * FROM retraits_evenements JOIN autres_cotisations ON autres_cotisations.id_ac=retraits_evenements.id_cot WHERE id_cot=:id_ac');
$pst->execute([
    "id_ac" => intval(htmlspecialchars($_GET['id']))
]);
$exist = $pst->fetch(PDO::FETCH_ASSOC);




$pst = $con->prepare('SELECT SUM(montant) As tot FROM contributions_cotisations JOIN autres_cotisations ON autres_cotisations.id_ac=contributions_cotisations.cotisation_id WHERE cotisation_id=:id_ac');
$pst->execute([
    "id_ac" => intval(htmlspecialchars($_GET['id']))
]);
$tot = $pst->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Administration</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>


    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />


    <link rel="stylesheet" href="assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php' ?>
        <div class="main-panel">
            <?php include 'mainheader.php' ?>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">
                                <?php

                                $pst = $con->prepare('SELECT * FROM contributions_cotisations JOIN autres_cotisations ON autres_cotisations.id_ac=contributions_cotisations.cotisation_id WHERE cotisation_id=:id_ac');
                                $pst->execute([
                                    "id_ac" => intval(htmlspecialchars($_GET['id']))
                                ]);
                                $exists = $pst->fetch(PDO::FETCH_ASSOC);

                                ?>
                                <?= isset($exists['motif_acotisation']) ? "Détail sur" . ' ' . $exists['motif_acotisation'] : '' ?>
                            </h3>

                        </div>

                        <div class="ms-md-auto py-2 py-md-0">
                            <?php if ($exists) { ?>
                                <a href="./pdf_detail_autre_cotisation.php?id=<?= $_GET['id'] ?>"
                                    class="btn btn-label-info btn-round me-2"><i class="icon-printer"></i>
                                    Imprimer</a>
                            <?php } ?>
                            <a href="./listeplanification.php" class="btn btn-danger btn-round">Annuler</a>
                        </div>


                    </div>


                    <div class="row row-card-no-pd d-flex justify-content-center">
                        <div class="col-sm-6 col-md-3">
                            <?php if ($exists && $exists) { ?>
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="icon-pie-chart text-warning"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Total</p>
                                                    <h4 class="card-title"><?php
                                                    require './traitement/connect.php';


                                                    $total = $tot['tot'];
                                                    echo number_format($tot['tot'], "0", ".") . ' ' . 'fcfa';
                                                    ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="icon-wallet text-success"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Retire</p>
                                                    <h4 class="card-title"><?php

                                                    $pst = $con->prepare('SELECT sum(montant) as reste FROM retraits_evenements JOIN autres_cotisations ON autres_cotisations.id_ac=retraits_evenements.id_cot WHERE id_cot=:idac');
                                                    $pst->execute([
                                                        "idac" => intval(htmlspecialchars($_GET['id']))
                                                    ]);
                                                    $reste = $pst->fetch(PDO::FETCH_ASSOC);

                                                    if ($reste['reste'] == 0) {
                                                        echo "0,00 fcfa";
                                                    } else {
                                                        echo number_format($reste["reste"], "0", ".") . ' ' . 'fcfa';
                                                    }
                                                    ?>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="card card-stats card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="icon-close text-danger"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Restant</p>
                                                    <h4 class="card-title"><?php
                                                    $restant = $total - $reste['reste'];
                                                    echo number_format($restant, "0", ".") . ' ' . 'fcfa';
                                                    ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Table pour les grands écrans -->
                                    <div class="col-md-12 d-none d-md-block">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Liste des participations</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="basic-datatables"
                                                        class="display table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Nom</th>
                                                                <th>Prenom</th>
                                                                <th>Montant total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            require_once './traitement/connect.php';

                                                            $pst = $con->prepare("SELECT *,SUM(montant) as tots FROM contributions_cotisations JOIN membres
                                ON membres.id=contributions_cotisations.membres_id JOIN autres_cotisations ON 
                                autres_cotisations.id_ac=contributions_cotisations.cotisation_id WHERE cotisation_id=:id
                                group by nom,prenom
                                ");
                                                            $pst->execute([
                                                                "id" => intval(htmlspecialchars($_GET['id']))
                                                            ]);
                                                            $res = $pst->fetchAll(PDO::FETCH_ASSOC);

                                                            foreach ($res as $rs) {
                                                                ?>
                                                                <tr>
                                                                    <td><?= $rs['nom'] ?></td>
                                                                    <td><?= $rs['prenom'] ?></td>
                                                                    <td><?= $rs['tots'] ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row d-md-none">
                                        <?php
                                        foreach ($res as $rs) {
                                            ?>
                                            <div class="col-12 mb-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h5 class="card-title"><?= $rs['nom'] ?>         <?= $rs['prenom'] ?></h5>
                                                        <p class="card-text"><strong>Montant Total :</strong> <?= $rs['tots'] ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            <?php } else { ?>
                <div class=" ">
                    <div class="">
                        <div class="card-header">Aucune information Disponible</div>
                    </div>
                </div>



            <?php } ?>

            <script src="assets/js/core/jquery-3.7.1.min.js"></script>
            <script src="assets/js/core/popper.min.js"></script>
            <script src="assets/js/core/bootstrap.min.js"></script>
            <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
            <script src="assets/js/plugin/chart.js/chart.min.js"></script>

            <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

            <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

            <script src="assets/js/plugin/datatables/datatables.min.js"></script>


            <!-- jQuery Vector Maps -->
            <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
            <script src="assets/js/plugin/jsvectormap/world.js"></script>

            <!-- Sweet Alert -->
            <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

            <!-- Kaiadmin JS -->
            <script src="assets/js/kaiadmin.min.js"></script>

            <!-- Kaiadmin DEMO methods, don't include it in your project! -->
            <script src="assets/js/setting-demo.js"></script>
            <script src="assets/js/demo.js"></script>

</body>

</html>