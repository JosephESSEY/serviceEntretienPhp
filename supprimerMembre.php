<?php
session_start();
require './traitement/connect.php';

if (isset($_GET['id']) && $_GET['id'] > 0) {
  $id = $_GET['id'];
  $location = 'index.php';

  $pst=$con->prepare("DELETE FROM membres WHERE id=:id");
  $pst->execute([
    "id"=>intval($_GET['id'])
  ]);
  $_SESSION['message']="Suppression effectué avec succes";
  header("location:".$location);

}

?>