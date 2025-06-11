<?php
session_start();
if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
  $location = './login.php';
  header('location:' . $location);
}


require './traitement/connect.php';

$parpage = 5;
$current = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$queryCount = "SELECT COUNT(*) as nb FROM autres_cotisations";
if (!empty($search)) {
  $queryCount .= " WHERE motif_acotisation LIKE :search";
}

$pst = $con->prepare($queryCount);
if (!empty($search)) {
  $pst->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$pst->execute();
$nbm = $pst->fetch(PDO::FETCH_ASSOC)['nb'];

$offset = ($current - 1) * $parpage;
$pages = ceil($nbm / $parpage);


$query = "SELECT * FROM autres_cotisations";
if (!empty($search)) {
  $query .= " WHERE motif_acotisation LIKE :search ";
}
$query .= " ORDER BY id_ac DESC LIMIT :offset, :parpage ";

$pst = $con->prepare($query);
if (!empty($search)) {
  $pst->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$pst->bindValue(':offset', $offset, PDO::PARAM_INT);
$pst->bindValue(':parpage', $parpage, PDO::PARAM_INT);
$pst->execute();
$res = $pst->fetchAll(PDO::FETCH_ASSOC);
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
              <h3 class="fw-bold mb-3">Gestion des cotisations</h3>

            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="./planifier.php" class="btn btn-primary btn-round">Nouveau</a>
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
            <div class="col-sm-6 col-md-3">
              <div class="card card-stats card-round">
                <div class="card-body">
                  <div class="row">
                    <div class="col-5">
                      <div class="icon-big text-center">
                        <i class="icon-pie-chart text-warning"></i>
                      </div>
                    </div>
                    <?php include './statistique.php' ?>

                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-header d-flex justify-content-between">
                          <form class="d-flex w-25 mt-3 w-100" action="" method="GET">
                            <input class="form-control form-control-sm me-2 h-25" type="search" name="search"
                              placeholder="Rechercher..." aria-label="Rechercher">
                            <button class="btn btn-sm btn-primary" type="submit">Rechercher</button>
                          </form>
                        </div>
                        <div class="card-body">
                          <div class="table-responsive d-none d-md-block">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                              <thead>
                                <tr>
                                  <th>Motif Cotisation</th>
                                  <th>Date Planification</th>
                                  <th>Montant Cotisation</th>
                                  <th>Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if ($res) {
                                  foreach ($res as $rs) { ?>
                                    <tr>
                                      <td><?= $rs['motif_acotisation'] ?></td>
                                      <td><?= date_format(date_create($rs['date_acot']), "d-m-Y") ?></td>
                                      <td><?= $rs['montant_acotisation'] ?></td>
                                      <td>
                                        <!-- Button group for better alignment and spacing -->
                                        <div class="btn-group" role="group" aria-label="Actions">
                                          <a href="./info_autre_cotisation.php?id=<?= $rs['id_ac'] ?>"
                                            class="btn btn-outline-warning" title="Voir"><i class="far fa-eye"
                                              style="font-size: 20px;"></i></a>
                                          <a href="./modifierAutre.php?id=<?= $rs['id_ac'] ?>"
                                            class="btn btn-outline-secondary" title="Modifier"><i class="far fa-edit"
                                              style="font-size: 20px;"></i></a>
                                          <a href="./deleteAutre.php?id=<?= $rs['id_ac'] ?>" class="btn btn-outline-danger"
                                            title="Supprimer"
                                            onclick="return confirm('Voulez-vous vraiment effectuer la suppression?')">
                                            <i class="far fa-trash-alt"></i>
                                          </a>
                                        </div>
                                      </td>
                                    </tr>
                                  <?php }
                                } else { ?>
                                  <tr>
                                    <td colspan="4" class="text-center">Aucune information disponible</td>
                                  </tr>
                                <?php } ?>
                              </tbody>
                            </table>
                          </div>

                          <div class="d-block d-md-none">
                            <?php if ($res) {
                              foreach ($res as $rs) { ?>
                                <div class="card mb-3">
                                  <div class="card-body">
                                    <h5 class="card-title"><?= $rs['motif_acotisation'] ?></h5>
                                    <p class="card-text"><strong>Date Planification :</strong>
                                      <?= date_format(date_create($rs['date_acot']), "d-m-Y") ?></p>
                                    <p class="card-text"><strong>Montant Cotisation :</strong>
                                      <?= $rs['montant_acotisation'] ?></p>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                      <a href="./info_autre_cotisation.php?id=<?= $rs['id_ac'] ?>"
                                        class="btn btn-outline-warning" title="Voir"><i class="far fa-eye"
                                          style="font-size: 20px;"></i> Voir</a>
                                      <a href="./modifierAutre.php?id=<?= $rs['id_ac'] ?>" class="btn btn-outline-secondary"
                                        title="Modifier"><i class="far fa-edit" style="font-size: 20px;"></i> Modifier</a>
                                      <a href="./deleteAutre.php?id=<?= $rs['id_ac'] ?>" class="btn btn-outline-danger"
                                        title="Supprimer"
                                        onclick="return confirm('Voulez-vous vraiment effectuer la suppression?')"><i
                                          class="far fa-trash-alt"></i> Supprimer</a>
                                    </div>
                                  </div>
                                </div>
                              <?php }
                            } else { ?>
                              <div class="card">
                                <div class="card-body">
                                  <p class="card-text">Aucune information disponible</p>
                                </div>
                              </div>
                            <?php } ?>
                          </div>

                          <?php if ($res) { ?>
                            <nav aria-label="Page navigation" class="mt-4">
                              <ul class="pagination justify-content-center">
                                <li class="page-item <?= $current == 1 ? 'disabled' : '' ?>">
                                  <a class="page-link"
                                    href="?page=<?= $current - 1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
                                </li>
                                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                                  <li class="page-item <?= $i == $current ? 'active' : '' ?>">
                                    <a class="page-link"
                                      href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                  </li>
                                <?php } ?>
                                <li class="page-item <?= $current == $pages ? 'disabled' : '' ?>">
                                  <a class="page-link"
                                    href="?page=<?= $current + 1 ?>&search=<?= urlencode($search) ?>">Suivant</a>
                                </li>
                              </ul>
                            </nav>
                          <?php } ?>
                        </div>
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