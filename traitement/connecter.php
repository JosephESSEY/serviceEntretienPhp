<?php
session_start();
include './connect.php';

$password = $_POST['password'];
$username = $_POST['username'];

$pst = $con->prepare("SELECT * FROM admin 
                      JOIN membres ON membres.id = admin.membreId 
                      WHERE nom = :nom");
$pst->execute(["nom" => $username]);

$values = $pst->fetch(PDO::FETCH_ASSOC);

if ($values) {
    if (password_verify($password, $values['password'])) {
        $_SESSION['message'] = "Vous êtes connecté avec succès";
        $_SESSION['nom'] = $values['nom'];
        $_SESSION['prenom'] = $values['prenom'];
        $_SESSION['profil'] = $values['photo'];
        header('location: ../index.php');
        exit();
    } else {
        $_SESSION['error'] = "Mot de passe incorrect";
        header('location: ../login.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Nom d'utilisateur incorrect";
    header('location: ../login.php');
    exit();
}
?>
