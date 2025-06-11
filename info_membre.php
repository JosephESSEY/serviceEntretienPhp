<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['nom']) || !isset($_SESSION['prenom'])) {
  header('Location: ./login.php');
  exit;
}


require './traitement/connect.php';

$anne = date('Y');
$parpage = 5;
$current = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$queryCount = "SELECT COUNT(DISTINCT m.id) as nb
    FROM membres m
    CROSS JOIN mois mo
    LEFT JOIN cotisation c ON m.id = c.id_membre AND mo.id_mois = c.id_mois AND c.anne = '$anne'";

if (!empty($search)) {
  $queryCount = "SELECT COUNT(*) as nb FROM cotisation
                 JOIN membres ON membres.id=cotisation.id_membre
                 WHERE anne='$anne' AND (nom LIKE :search OR prenom LIKE :search OR telephone LIKE :search)";
}

$pst = $con->prepare($queryCount);
if (!empty($search)) {
  $pst->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$pst->execute();
$nbm = $pst->fetch(PDO::FETCH_ASSOC)['nb'];

$offset = ($current - 1) * $parpage;
$pages = ceil($nbm / $parpage);

$query = "SELECT 
            m.nom, 
            m.prenom, 
            GROUP_CONCAT(mo.nom_mois ORDER BY FIELD(mo.nom_mois, 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre') SEPARATOR ', ') AS mois_arrieres, 
            SUM(c.montant) AS total_arrieres,
            COUNT(mo.id_mois) * 800 AS montant_du
          FROM 
            membres m
          CROSS JOIN 
            mois mo
          LEFT JOIN 
            cotisation c 
          ON 
            mo.id_mois = c.id_mois 
            AND m.id = c.id_membre 
            AND c.anne = :anne
          WHERE 
            c.id_cotisation IS NULL 
          GROUP BY 
            m.id
          HAVING 
            SUM(c.montant) < 800 OR SUM(c.montant) IS NULL
          ORDER BY 
            m.nom ASC";




if (!empty($search)) {
  $query = "SELECT 
  m.nom, 
  m.prenom, 
   GROUP_CONCAT(mo.nom_mois ORDER BY FIELD(mo.nom_mois, 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre') SEPARATOR ', ') AS mois_arrieres, 
  SUM(c.montant) AS total_arrieres,
  COUNT(mo.id_mois) * 800 AS montant_du
FROM 
  membres m
CROSS JOIN 
  mois mo
LEFT JOIN 
  cotisation c 
ON 
  mo.id_mois = c.id_mois 
  AND m.id = c.id_membre 
  AND c.anne = :anne
WHERE 
  c.id_cotisation IS NULL
  AND m.nom like :search OR m.prenom like :search
GROUP BY 
  m.id
   HAVING 
  SUM(c.montant) < 800 OR SUM(c.montant) IS NULL
ORDER BY 
  m.nom ASC";

}

$query .= " LIMIT :offset, :parpage";
$pst = $con->prepare($query);
if (!empty($search)) {
  $pst->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$pst->bindValue(':offset', $offset, PDO::PARAM_INT);
$pst->bindValue(':parpage', $parpage, PDO::PARAM_INT);
$pst->bindValue(':anne', $anne, PDO::PARAM_INT);
$pst->execute();
$rs = $pst->fetchAll(PDO::FETCH_ASSOC);
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
              <h3 class="fw-bold mb-3">Données des membres</h3>

            </div>
            <div class="ms-md-auto py-2 py-md-0">
              <a href="./pdf_donnee_des_membres.php" class="btn btn-label-info btn-round me-2"><i
                  class="icon-printer"></i> Imprimer</a>
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
                    <div class="col-7 col-stats">
                      <div class="numbers">
                        <p class="card-category">Mensualite Total</p>
                        <h4 class="card-title"><?php
                        require './traitement/connect.php';

                        $anne = date("Y");

                        $totals = $con->prepare("SELECT SUM(montant) as Mt FROM cotisation WHERE anne=:anne");

                        $totals->execute([
                          "anne" => $anne
                        ]);
                        $total = $totals->fetch(PDO::FETCH_ASSOC)["Mt"];
                        echo number_format($total, "1", ",") . ' ' . 'fcfa';
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

                        $anne = date("Y");

                        $restes = $con->prepare("SELECT MIN(restant) as rt FROM retraits_mensuels WHERE anne=:anne");

                        $restes->execute([
                          "anne" => $anne
                        ]);
                        $reste = $restes->fetch(PDO::FETCH_ASSOC)["rt"];
                        if ($reste == null) {
                          echo "0,00 fcfa";
                        } else {
                          echo number_format($reste, "1", ",") . ' ' . 'fcfa';
                          ;
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
                        $restant = $total - $reste;
                        echo number_format($restant, "1", ",") . ' ' . 'fcfa';
                        ?></h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


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
                  <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Nom</th>
                          <th>Prenom</th>
                          <th>Mois Arriéré(s)</th>
                          <th>Montant Arrieré(s)</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        if ($rs) {
                          foreach ($rs as $res) {
                            ?>
                            <tr>
                              <td><?= $res['nom'] ?></td>
                              <td><?= $res['prenom'] ?></td>
                              <td><?= $res['mois_arrieres'] ?></td>
                              <td>
                                <?= isset($res["montant_du"]) ? number_format($res['montant_du'], "0", ",") . " " . "fcfa" : '0 FCFA' ?>
                              </td>
                            </tr>
                          <?php }
                        } else { ?>
                          <td colspan="6" class="text-center">Aucune information disponible</td>
                        <?php } ?>

                      </tbody>
                    </table>
                  </div>
                  <?php if ($rs) { ?>
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