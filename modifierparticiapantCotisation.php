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


    .content1 {
        background: white;
        position: absolute;
        color: #000;
        border-radius: 7px;
        margin-top: 15px;
        width: 100%;
        z-index: 999;
        display: none;


    }

    .content1 input {
        margin-top: 20px;
    }

    .options1 {
        max-height: 145px;
        overflow-y: auto;
        scroll-behavior: smooth;
        padding: 0;
    }

    .options1 li {
        padding: 10px 15px;
        list-style: none;
        font-size: 15px;
        cursor: pointer;
        border-bottom: 1px solid gray;
    }

    .options1 li:hover {}



    .select-box1.active1 .content1 {
        display: block;
    }
</style>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php' ?>
        <div class="main-panel">
            <?php include 'mainheader.php' ?>

            <?php

            require './traitement/connect.php';

            $pst = $con->prepare("SELECT * FROM contributions_cotisations join autres_cotisations
                        ON autres_cotisations.id_ac=contributions_cotisations.cotisation_id 
                        JOIN membres on membres.id=contributions_cotisations.membres_id  WHERE cotisation_id=:idcot && membres_id=:idm");
            $pst->execute([
                'idcot' => intval($_GET['idcot']),
                'idm' => intval($_GET['idm'])
            ]);


            $value = $pst->fetch(PDO::FETCH_ASSOC);




            ?>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Modification des cotisations</h3>

                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="liste_participants_autre_cotisations.php"
                                class="btn btn-danger btn-round">Annuler</a>
                        </div>
                    </div>
                    <div class="row row-card-no-pd d-flex justify-content-center">



                        <div class="col-md-12">
                            <div class="card centered">

                                <form class="row g-3"
                                    action="./traitement/updateAutreCotisation.php?idcont=<?= $_GET['idcont'] ?>&idm=<?= $_GET['idm'] ?>&idcot=<?= $_GET['idcot'] ?>"
                                    method="post">
                                    <div class="col-md-12">
                                        <label for="montant" class="form-label">Montant(Optionnel)</label>
                                        <input type="number" class="form-control" id="montant" name="montant" min="100"
                                            value="<?= isset($value['montant']) ? $value['montant'] : "" ?>">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="prenom" class="form-label">Date payement</label>
                                        <input type="text" class="form-control" id="datepayement" name="datepayement"
                                            value="<?= date("Y-m-d") ?>" readonly="true">
                                    </div>
                                    <div class="col-md-12 select-box">
                                        <div class="col-md-12">
                                            <label for="id_membre" class="form-label">Participant</label>
                                            <input type="text" class="form-control mb-3 "
                                                placeholder="Sélectionner un membre" id="soValue" readonly name="id"
                                                style="cursor: pointer;"
                                                value="<?= isset($value['nom']) && isset($value['prenom']) ? $value['nom'] . ' ' . $value['prenom'] : 'Cliquer sur annuler pour recommencer' ?>">

                                        </div>
                                        <div class="col-md-12 content" style="position:relative">
                                            <input type="text" id="idm" class="form-control mb-3" name="idm"
                                                value="<?= isset($_GET['idm']) ? $_GET['idm'] : '' ?>">
                                            <input type="text" id="optionSearch" class="form-control mb-3"
                                                placeholder="Rechercher" id="" name="id">
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
                                    <div class="col-md-12 select-box1">

                                        <div class="col-md-12">
                                            <label for="id_cot" class="form-label">Cotisation Concernée</label>
                                            <input type="text" class="form-control mb-3 "
                                                placeholder="Sélectionner une cotisation" id="soValue1" readonly='true'
                                                name="idc" style="cursor: pointer;"
                                                value="<?= isset($value['motif_acotisation']) ? $value['motif_acotisation'] : 'Cliquer sur annuler pour recommencer' ?>">

                                        </div>

                                        <div class="col-md-12 content1" style="position:relative">
                                            <input type="text" id="idcot" class="form-control mb-3" name="idcot"
                                                value="<?= isset($_GET['idcot']) ? $_GET['idcot'] : '' ?>">
                                            <input type="text" id="optionSearch1" class="form-control mb-3"
                                                placeholder="Rechercher">
                                            <ul class="options1">
                                                <?php
                                                require './traitement/connect.php';

                                                $pst = $con->prepare('SELECT * FROM autres_cotisations');
                                                $pst->execute();
                                                $res = $pst->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($res as $rs) {
                                                    ?>
                                                    <li data-id1="<?= $rs['id_ac'] ?>"><?= $rs['motif_acotisation'] ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>

                                    </div>


                                    <div class="col-12">
                                        <?php if (!empty($_GET['idcot']) && !empty($_GET['idm']) && $value) { ?>
                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                        <?php } else { ?>


                                            <button type="submit" class="btn btn-primary" disabled>Ajouter
                                                maintenant</button>
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

            <script>



                const selectbox = document.querySelector(".select-box");
                const selectOption = document.querySelector(".select-option");
                const soValue = document.querySelector("#soValue");
                const optionsearch = document.querySelector("#optionSearch");
                const options = document.querySelector(".options");
                const optionList = document.querySelectorAll(".options li");


                selectOption.addEventListener('click', () => {
                    selectbox.classList.toggle('active');
                })

                optionList.forEach(element => {
                    element.addEventListener('click', () => {
                        text = element.textContent;
                        soValue.value = text;
                        document.querySelector('#idm').value = element.getAttribute('data-id')
                        selectbox.classList.remove('active')
                    })
                });

                optionsearch.addEventListener('input', () => {
                    var filter, li, i, textvalue;
                    filter = optionsearch.value.toUpperCase();
                    li = options.getElementsByTagName('li');
                    for (i = 0; i <= li.length; i++) {
                        liCount = li[i];
                        textvalue = liCount.textContent || liCount.innerText;



                        if (textvalue.indexOf(filter) > -1) {
                            li[i].style.display = '';
                        } else {
                            li[i].style.display = 'none';
                        }
                    }

                });





            </script>


            <script>



                const selectbox1 = document.querySelector(".select-box1");
                const selectOption1 = document.querySelector(".select-option1");
                const soValue1 = document.querySelector("#soValue1");
                const optionsearch1 = document.querySelector("#optionSearch1");
                const options1 = document.querySelector(".options1");
                const optionList1 = document.querySelectorAll(".options1 li");


                selectOption1.addEventListener('click', () => {
                    selectbox1.classList.toggle('active1');
                })

                optionList1.forEach(element => {
                    element.addEventListener('click', () => {
                        text = element.textContent;
                        soValue1.value = text;
                        document.querySelector('#idcot').value = element.getAttribute('data-id1')
                        selectbox1.classList.remove('active1')
                    })
                });

                optionsearch1.addEventListener('input', () => {
                    var filter, li, i, textvalue;
                    filter = optionsearch1.value;
                    li = options1.getElementsByTagName('li');
                    for (i = 0; i <= li.length; i++) {
                        liCount = li[i];
                        textvalue = liCount.textContent || liCount.innerText;



                        if (textvalue.indexOf(filter) > -1) {
                            li[i].style.display = '';
                        } else {
                            li[i].style.display = 'none';
                        }
                    }

                });





            </script>


</body>

</html>