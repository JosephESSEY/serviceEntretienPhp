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
              <h3 class="fw-bold mb-3">Enregistrement des membres</h3>

            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="index.php" class="btn btn-danger btn-round">Cancel</a>
            </div>
          </div>
          <div class="row row-card-no-pd d-flex justify-content-center">



            <div class="col-md-12">
              <div class="card centered">
                <?php if (isset($message)) { ?>
                  <div class="alert alert-success">
                    <?= $message ?>
                  </div>
                <?php } ?>

                <form class="row g-3" action="./traitement/addMember.php" enctype="multipart/form-data" method="post">
                  <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                  </div>
                  <div class="col-md-6">
                    <label for="prenom" class="form-label">Prenom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                  </div>

                  <div class="col-md-6">
                    <label for="datenaiss" class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" id="datenaiss" name="datenaiss" required>
                  </div>
                  <div class="col-md-6">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="tel" class="form-control" id="contact" name="contact" required>
                  </div>
                  <div class="col-md-12">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="fichier" >
                  </div>
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="valider">Ajouter maintenant</button>
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