<?php
session_start();
require "../traitement/connect.php";
$location = '../index.php';
if (isset($_GET['id']) && $_GET['id'] > 0 && !empty($_GET['id'])) {
    $id = intval(htmlspecialchars($_GET['id']));
} else {
    $location = '../index.php';
    header("location:" . $location);
}
if (isset($_FILES['fichier']['name']) && !empty($_FILES['fichier']['name'])) {
    $file_info = pathinfo($_FILES['fichier']['name']);
    $extensionfichier = $file_info['extension'];
    $extensionauthorise = ["jpeg", "jpg", "png"];
    $temporaire = $_FILES['fichier']['tmp_name'];
    $chemin = "../assets/images/" . basename($_FILES['fichier']['name']);

    if ($_FILES['fichier']['size'] <= 1000000) {
        if (in_array($extensionfichier, $extensionauthorise)) {
            if (move_uploaded_file($temporaire, $chemin)) {

                $pst = $con->prepare("UPDATE membres SET nom=:nom,prenom=:prenom,datenaiss=:datenaiss,telephone=:contact,photo=:photo WHERE id=:id");
                $pst->execute([
                    "nom" => $_POST['nom'],
                    "prenom" => $_POST['prenom'],
                    "datenaiss" => $_POST['datenaiss'],
                    "contact" => $_POST['contact'],
                    "photo" => $chemin,
                    "id" => $id
                ]);

                $_SESSION['message'] = 'Modification effectue avec success';
                header('location:' . $location);

            } else {
                $_SESSION['error']= "Désolé,echec d'envoie!!!La taille doit etre inférieure ou égale a 1MO";
            }

        } else {
            $_SESSION['error'] = "Désolé,l'extension du fichier n'est pas valide";
        }

    } else {
        $_SESSION['error'] = "Taille de l'image trop grande!La taille ne dois pas depasser 1 MO";
    }

} else {
    $pst = $con->prepare("UPDATE membres SET nom=:nom,prenom=:prenom,datenaiss=:datenaiss,telephone=:contact WHERE id=:id");
    $pst->execute([
        "nom" => $_POST['nom'],
        "prenom" => $_POST['prenom'],
        "datenaiss" => $_POST['datenaiss'],
        "contact" => $_POST['contact'],
        "id" => $id
    ]);
    $_SESSION['message'] = "Modification effectue avec success";
    $location = "../index.php";
    header("location:" . $location);
}

?>