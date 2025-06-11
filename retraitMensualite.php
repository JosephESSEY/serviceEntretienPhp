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

<body>
  <div class="wrapper">
    <?php include 'sidebar.php' ?>
    <div class="main-panel">
      <?php include 'mainheader.php' ?>

      <div class="container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Retrait Mensualites</h3>

            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="listeretraitMensualite" class="btn btn-danger btn-round">Annuler</a>
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

                <form class="row g-3" action="./traitement/retrait_mensualite.php" method="post">
                  <div class="col-md-12">
                    <label for="motif" class="form-label">Motif du retrait</label>
                    <input type="text" class="form-control" id="motif" name="motif">
                  </div>
                  <div class="col-md-12">
                    <label for="prenom" class="form-label">Date Retrait</label>
                    <input type="text" class="form-control" id="dateretrait" name="dateretrait"
                      value="<?= date("Y-m-d") ?>" disabled="true">


                  </div>
                  <div class="col-md-12">
                    <label for="montant" class="form-label">Montant a retire</label>
                    <input type="number" class="form-control" id="montant" name="montant">
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary">Retirer maintenant</button>
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