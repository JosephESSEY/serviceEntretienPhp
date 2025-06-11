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

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Modifier mon mot de passe</h3>

                        </div>


                    </div>
                    <?php
                    if (isset($_SESSION['message'])) {
                        ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['message'] ?>
                        </div>

                        <?php

                    } elseif (isset($_SESSION['error'])) {
                        ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'] ?>
                        </div>
                    <?php }
                    unset($_SESSION['message']);
                    unset($_SESSION['error'])

                        ?>
                    <div class="row row-card-no-pd d-flex justify-content-center">

                        <div class="col-md-12">

                            <div class="card centered">


                                <form class="row g-3" action="./traitement/updatepassword.php" method="post">
                                    <div class="col-md-12 select-box">
                                        <div class="col-md-12">
                                            <label for="password" class="form-label">Nouveau mot de passe</label>
                                            <input type="text" class="form-control mb-3 select-option"
                                                placeholder="Saisissez le nouveau mot de passe" id="soValue" name="password"
                                                style="cursor: pointer;">
                                        </div>
                                    </div>
                                   

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Voulez-vous vraiment modifier le mot de passe')">Modifier</button>
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