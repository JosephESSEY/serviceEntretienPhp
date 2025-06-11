<?php
session_start();
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
    $location = './login.php';
    header('location:'.$location);
  }
  

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

<style>
    .content {
        background: white;
        position: absolute;
        color: #000;
        border-radius: 7px;
        margin-top: 15px;
        width: 100%;
        z-index: 999;
        display: none;


    }

    .content input {
        margin-top: 20px;
    }

    .options {
        max-height: 145px;
        overflow-y: auto;
        scroll-behavior: smooth;
        padding: 0;
    }

    .options li {
        padding: 10px 15px;
        list-style: none;
        font-size: 15px;
        cursor: pointer;
        border-bottom: 1px solid gray;
    }

    .options li:hover {}



    .select-box.active .content {
        display: block;
    }
</style>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php' ?>
        <div class="main-panel">
            <?php include 'mainheader.php' ?>

            <?php
            require_once "./traitement/connect.php";



            $pst = $con->prepare('SELECT * FROM cotisation join membres on membres.id=cotisation.id_membre WHERE id_cotisation=:id_cotisation and id_membre=:id_membre ');
            $pst->execute([
                "id_cotisation" => $_GET['id'],
                "id_membre" => $_GET['idm']
            ]);
            $cot = $pst->fetch(PDO::FETCH_ASSOC);

            ?>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Modification des informations</h3>

                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="mensualites.php" class="btn btn-danger btn-round">Annuler</a>
                        </div>
                    </div>
                    <div class="row row-card-no-pd d-flex justify-content-center">



                        <div class="col-md-12">
                            <div class="card centered">

                                <form class="row g-3"
                                    action="./traitement/updatemensualite.php?id=<?= $_GET['id'] ?>&idm=<?= $_GET['idm'] ?>"
                                    method="post">
                                    <div class="col-md-12">
                                        <label for="montant" class="form-label">Montant</label>
                                        <input type="number" class="form-control" id="montant" name="montant" min="100"
                                            value="<?= isset($cot['montant']) ? $cot['montant'] : '' ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="prenom" class="form-label">Date payement</label>
                                        <input type="text" class="form-control" id="datepayement" name="datepayement"
                                            value="<?= date("Y-m-d") ?>" readonly="true">
                                    </div>
                                    <div class="col-md-12 select-box">
                                        <div class="col-md-12">
                                            <label for="id_membre" class="form-label">Participant</label>
                                            <input type="text" class="form-control mb-3 select-option"
                                                placeholder="retourner selectionner un membre dans le tableau"
                                                id="soValue" readonly name="id" style="cursor: pointer;" required
                                                value="<?= isset($cot['nom']) && isset($cot['prenom']) ? $cot['nom'] . ' ' . $cot['prenom'] : '' ?>">

                                        </div>
                                        <div class="col-md-12 content" style="position:relative">
                                            <input type="hidden" id="idm" class="form-control mb-3" name="idm"
                                                value="<?= $_GET['idm'] ?>">
                                            <input type="text" id="optionSearch" class="form-control mb-3"
                                                placeholder="Rechercher" name="id">
                                            <ul class="options">
                                                <?php
                                                require './traitement/connect.php';

                                                $pst = $con->prepare('SELECT * FROM membres');
                                                $pst->execute();
                                                $res = $pst->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($res as $rs) {
                                                    ?>
                                                    <li data-id="<?= $rs['id'] ?>"><?= $rs['nom'] . ' ' . $rs['prenom'] ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <?php if (isset($_GET["id"]) && !empty($_GET['id']) && $cot && isset($_GET['idm']) && !empty($_GET['idm'])) { ?>
                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                        <?php } else { ?>

                                            <button type="submit" class="btn btn-primary" disabled>Modifier</button>
                                        <?php } ?>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <script src="assets/js/core/jquery-3.7.1.min.js"></script>
            <script src="assets/js/core/popper.min.js"></script>
            <script src="assets/js/core/bootstrap.min.js"></script>
            <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
            <script src="assets/js/plugin/chart.js/chart.min.js"></script>

            <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

            <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

            <script src="assets/js/plugin/datatables/datatables.min.js"></script>
            <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
            <script src="assets/js/plugin/jsvectormap/world.js"></script>

            <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

            <script src="assets/js/kaiadmin.min.js"></script>

            <script src="assets/js/setting-demo.js"></script>
            <script src="assets/js/demo.js"></script>



</body>

</html>