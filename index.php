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


$queryCount = "SELECT COUNT(*) as nb FROM membres";
if (!empty($search)) {
  $queryCount .= " WHERE nom LIKE :search OR prenom LIKE :search OR telephone LIKE :search";
}

$pst = $con->prepare($queryCount);
if (!empty($search)) {
  $pst->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$pst->execute();
$nbm = $pst->fetch(PDO::FETCH_ASSOC)['nb'];

$offset = ($current - 1) * $parpage;
$pages = ceil($nbm / $parpage);

$query = "SELECT * FROM membres ";
$params = [];

if (!empty($search)) {
  $query .= "WHERE nom LIKE :search OR prenom LIKE :search OR telephone LIKE :search  ";
  $params[':search'] = "%$search%";
}

$query .= "ORDER BY nom,prenom LIMIT :offset, :parpage";

$pst = $con->prepare($query);

if (!empty($params)) {
  foreach ($params as $key => $value) {
    $pst->bindValue($key, $value, PDO::PARAM_STR);
  }
}

$pst->bindValue(':offset', $offset, PDO::PARAM_INT);
$pst->bindValue(':parpage', $parpage, PDO::PARAM_INT);

$pst->execute();

$lesmembres = $pst->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
  <title>Administration</title>
  <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: { families: ["Public Sans:300,400,500,600,700"] },
      custom: {
        families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
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
  <style>
    /* Gestion du tableau pour les petits écrans */
  </style>

</head>

<body>
  <div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main-panel">
      <?php include 'mainheader.php'; ?>

      <div class="container">
        <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
              <h3 class="fw-bold mb-3">Gestion des membres</h3>
            </div>

            <div class="ms-md-auto py-2 py-md-0 d-flex align-items-center">
              <a href="./pdf_liste_membre.php" class="btn btn-label-info btn-round me-2"><i class="icon-printer"></i>
                Imprimer</a>
              <a href="ajouterMembre.php" class="btn btn-primary btn-round">Nouveau</a>
            </div>
          </div>

          <?php if (isset($_SESSION['message'])) { ?>
            <div class="alert alert-success">
              <?= $_SESSION['message'] ?>
            </div>
          <?php } elseif (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger">
              <?= $_SESSION['error'] ?>
            </div>
          <?php }
          unset($_SESSION['message'], $_SESSION['error']); ?>

          <div class="row row-card-no-pd d-flex justify-content-center">
            <div class="col-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between">
                  <form class="d-flex w-100" action="" method="GET">
                    <input class="form-control form-control-sm me-2" type="search" name="search"
                      placeholder="Rechercher..." aria-label="Rechercher">
                    <button class="btn btn-sm btn-primary" type="submit">Rechercher</button>
                  </form>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table id="basic-datatables" class="table table-striped table-hover d-none d-md-table">
                      <thead>
                        <tr>
                          <th>Profil</th>
                          <th>Nom</th>
                          <th>Prénom</th>
                          <th>Date de naissance</th>
                          <th>Contact</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($lesmembres): ?>
                          <?php foreach ($lesmembres as $membre): ?>
                            <tr>
                              <td><img src="assets/<?= $membre['photo'] ?>" alt="" style="height: 60px;"></td>
                              <td><?= $membre['nom'] ?></td>
                              <td><?= $membre['prenom'] ?></td>
                              <td><?= date_format(date_create($membre['datenaiss']), "d-m-Y") ?></td>
                              <td><?= $membre['telephone'] ?></td>
                              <td>
                                <a href="modiferMembre.php?id=<?= $membre['id'] ?>" class="btn btn-outline-secondary"><i
                                    class="far fa-edit"></i></a>
                                <a href="supprimerMembre.php?id=<?= $membre['id'] ?>"
                                  onclick="return confirm('Voulez-vous vraiment effectuer cette suppression?')"
                                  class="btn btn-outline-danger"><i class="far fa-trash-alt"></i></a>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center">Aucune information disponible</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Cards for mobile -->
                  <div class="cards d-block d-md-none">
                    <?php if ($lesmembres): ?>
                      <?php foreach ($lesmembres as $membre): ?>
                        <div class="card mb-3">
                          <div class="card-body">
                            <div class="d-flex align-items-center flex-column">
                              <img src="assets/<?= $membre['photo'] ?>" alt="Photo de <?= $membre['nom'] ?>"
                                class="card-img me-3 mb-2" />
                              <div>
                                <h5 class="card-title"><?= $membre['nom'] ?>     <?= $membre['prenom'] ?></h5>
                                <p class="card-text mb-1">Date de naissance :
                                  <?= date_format(date_create($membre['datenaiss']), "d-m-Y") ?></p>
                                <p class="card-text">Contact : <?= $membre['telephone'] ?></p>
                              </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-end">
                              <a href="modiferMembre.php?id=<?= $membre['id'] ?>" class="btn btn-outline-secondary me-2"><i
                                  class="far fa-edit"></i> Modifier</a>
                              <a href="supprimerMembre.php?id=<?= $membre['id'] ?>"
                                onclick="return confirm('Voulez-vous vraiment effectuer cette suppression?')"
                                class="btn btn-outline-danger"><i class="far fa-trash-alt"></i> Supprimer</a>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <p class="text-center">Aucune information disponible</p>
                    <?php endif; ?>
                  </div>



                  <?php if ($lesmembres) { ?>
                    <nav aria-label="Page navigation" class="mt-4">
                      <ul class="pagination justify-content-center">
                        <li class="page-item <?= $current == 1 ? 'disabled' : '' ?>">
                          <a class="page-link"
                            href="?page=<?= $current - 1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
                        </li>
                        <?php for ($i = 1; $i <= $pages; $i++) { ?>
                          <li class="page-item <?= $i == $current ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
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