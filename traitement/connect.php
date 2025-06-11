<?php
try {
    //  $con = new PDO("mysql:host=sql210.infinityfree.com;dbname=if0_37937935_unitetechnique", "if0_37937935", "Admin20242025");
    $con = new PDO("mysql:host=localhost;dbname=unitetechnique", "root", "");

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    die("Echec de connexion a la base de donnée" . $e->getMessage());
}
?>