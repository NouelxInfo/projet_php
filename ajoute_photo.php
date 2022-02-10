<?php
include("boite_outils.php");
include("mesfonctions.php");

?>
    <html>
    <head><title>Ajout de la photo ...</title></head>
    <body>
    <?php
    if (isset($_POST['description'])) {
        $description = addslashes($_POST['description']);
    } else {
        $description = "";
    }


    if (isset($_POST['date_photo'])) {
        $date_photo = verifie_date($_POST['date_photo']);
    } else {
        $date_photo = date('Y-m-d');
    }


    $fichier = sauve_photo('photo');

    // Si il n'y a pas d'erreur:
    if ($fichier != null) {
        // On ouvre une connexion au SGBD
        $connect = connection();

        if (isset($login)) {
            $requete = "INSERT INTO photo(fichier,date_photo,description,proprietaire)
             VALUES('$fichier','$date_photo','$description','$login')";
        }
        $stmt = $connect->prepare($requete);
        $stmt->execute();

        print "<h3>Photo ajoutée:</h3>";
        affiche_photo($login,$date_photo,stripslashes($description),$fichier);
    }	else {
        print "<p><b>Echec de l'ajout de la photo !!!</b></p>";
    }
    ?>
    <hr>
    <p><a href='index.php'>Retour à l'accueil</a></p>
    </body>
    </html>
<?php
// fermeture de la connection au SGBD
$connect = null;
?>