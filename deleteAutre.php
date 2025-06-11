<?php
require './traitement/connect.php';

$id=$_GET['id'];
$pst=$con->prepare("DELETE FROM autres_cotisations WHERE id_ac=:id_ac");
$pst->execute([
    'id_ac'=>$id
]);
$message='Suppression effectué avec succes';

$location='./listeplanification.php';
header("location:".$location);

?>