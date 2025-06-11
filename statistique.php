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
    if ($total == null) {
      echo "0,00 fcfa";
    } else {
      echo number_format($total, "1", ".") . ' ' . 'fcfa';
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
            <i class="icon-wallet text-success"></i>
          </div>
        </div>
        <div class="col-7 col-stats">
          <div class="numbers">
            <p class="card-category">Retire</p>
            <h4 class="card-title"><?php

            $anne = date("Y");

            $restes = $con->prepare("SELECT SUM(montant) as rt FROM retraits_mensuels WHERE anne=:anne");

            $restes->execute([
              "anne" => $anne
            ]);
            $reste = $restes->fetch(PDO::FETCH_ASSOC)["rt"];
            if ($reste == null) {
              echo "0,00 fcfa";
            } else {
              echo number_format($reste, "1", ".") . ' ' . 'fcfa';
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
            if ($restant >= 0) {
              echo number_format($restant, "1", ".") . ' ' . 'fcfa';
            } else{
              echo "0.00 fcfa";
            }
            ?></h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>