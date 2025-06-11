<?php
session_start();
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
  $location = './login.php';
  header('location:' . $location);
}

$mois = [
  1 => "Janvier",
  2 => "Février",
  3 => "Mars",
  4 => "Avril",
  5 => "Mai",
  6 => "Juin",
  7 => "Juillet",
  8 => "Aout",
  9 => "Septembre",
  10 => "Octobre",
  11 => "Novembre",
  12 => "Décembre",
];

$mois_actuelle = (int) date('n')

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

      <div class="container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Enregistrement des mensualites</h3>

            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="mensualites.php" class="btn btn-danger btn-round">Annuler</a>
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

                <form class="row g-3" action="./traitement/addMensualite.php" method="post">
                  <div class="col-md-12">
                    <label for="montant" class="form-label">Montant</label>
                    <input type="number" class="form-control" id="montant" name="montant" min="200" value="200"
                      required>
                  </div>
                  <div class="col-md-12">
                    <label for="prenom" class="form-label">Date payement</label>
                    <input type="text" class="form-control" id="datepayement" name="datepayement"
                      value="<?= date("Y-m-d") ?>" readonly="true">
                  </div>
                  <div class="col-md-12">
                    <label for="mois" class="form-label">Mois</label>
                    <select name="id_mois" id="" class="form-control">
                      <?php foreach ($mois as $key => $value): ?>
                        <option value="<?= $key; ?>" <?= $key === $mois_actuelle ? "selected" : ""; ?>>
                          <?= $value; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>

                  </div>

                  <div class="col-md-12 select-box">
                    <div class="col-md-12">
                      <label for="id_membre" class="form-label">Participant</label>
                      <input type="text" class="form-control mb-3 select-option" placeholder="Sélectionner un membre"
                        id="soValue" readonly name="id" style="cursor: pointer;">

                    </div>
                    <div class="col-md-12 content" style="position:relative">
                      <input type="hidden" id="idm" class="form-control mb-3" name="idm">
                      <input type="text" id="optionSearch" class="form-control mb-3" placeholder="Rechercher" id=""
                        name="id">
                      <ul class="options">
                        <?php
                        require './traitement/connect.php';

                        $pst = $con->prepare('SELECT * FROM membres ORDER BY nom,prenom');
                        $pst->execute();
                        $res = $pst->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($res as $rs) {
                          ?>
                          <li data-id="<?= $rs['id'] ?>"><?= $rs['nom'] . ' ' . $rs['prenom'] ?></li>
                        <?php } ?>
                      </ul>
                    </div>
                  </div>

                  <div class="col-12">

                    <button type="submit" class="btn btn-primary">Ajouter maintenant</button>

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

</body>

</html>