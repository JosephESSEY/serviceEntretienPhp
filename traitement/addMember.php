<?php
session_start();
require "../traitement/connect.php";

if (isset($_POST['valider'])) {
    $location = '../index.php';

    if (isset($_FILES['fichier']['name']) && $_FILES['fichier']['name'] !=null ) {

        $file_info = pathinfo($_FILES['fichier']['name']);

        $extensionauthorise = ["jpeg", "jpg", "png"];
        $temporaire = $_FILES['fichier']['tmp_name'];
        $chemin = "../assets/images/" . basename($_FILES['fichier']['name']);

        if ($_FILES['fichier']['size'] <= 1000000) {
            $extensionfichier = $file_info['extension'];
            if (in_array($extensionfichier, $extensionauthorise)) {
                if (move_uploaded_file($temporaire, $chemin)) {

                    $exists = $con->prepare("SELECT * FROM membres WHERE nom=:nom AND prenom=:prenom");
                    $exists->execute([
                        "nom" => $_POST['nom'],
                        "prenom" => $_POST['prenom']
                    ]);

                    $exist = $exists->fetch(PDO::FETCH_ASSOC);
                    if (!$exist) {
                        $pst = $con->prepare("INSERT INTO membres VALUES(null, :nom, :prenom, :datenaiss, :telephone, :photo)");
                        $pst->execute([
                            "nom" => $_POST['nom'],
                            "prenom" => $_POST['prenom'],
                            "datenaiss" => $_POST['datenaiss'],
                            "telephone" => $_POST['contact'],
                            "photo" => $chemin

                        ]);
                        $_SESSION['message'] = 'Enrégistrement effectue avec success';
                        header('location:' . $location);
                    } else {
                        $location = '../ajouterMembre.php';
                        $_SESSION['error'] = "Désolé,le membre a été déja enrégistre";
                        header("location:" . $location);

                    }
                } else {
                    $_SESSION['error'] = "Désolé,echec d'envoie!!!La taille doit etre inférieure ou égale a 1MO";
                }

            } else {
                $_SESSION['error'] = "Désolé,l'extension du fichier n'est pas valide";
            }

        } else {
            $_SESSION["error"] = "Taille de l'image trop grande!La taille ne dois pas depasser 1 MO";
        }

    } else {
        $exists = $con->prepare("SELECT * FROM membres WHERE nom=:nom AND prenom=:prenom");
        $exists->execute([
            "nom" => $_POST['nom'],
            "prenom" => $_POST['prenom']
        ]);

        $exist = $exists->fetch(PDO::FETCH_ASSOC);
        if (!$exist) {
            $pst = $con->prepare("INSERT INTO membres VALUES(null, :nom, :prenom, :datenaiss, :telephone,:photo)");
            $pst->execute([
                "nom" => $_POST['nom'],
                "prenom" => $_POST['prenom'],
                "datenaiss" => $_POST['datenaiss'],
                "telephone" => $_POST['contact'],
                "photo"=> NULL
            ]);
            $_SESSION['message'] = 'Enrégistrement effectue avec success';
            header('location:' . $location);
        } else {
            $location = '../ajouterMembre.php';
            $_SESSION['error'] = "Désolé,le membre a été déja enrégistre";
            header("location:" . $location);

        }
    }
}
?>