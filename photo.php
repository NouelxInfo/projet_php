<?php
include('boite_outils.php');
include('mesfonctions.php');
// Ce fichier a trois usages: le principal est la visualisation d'une photo.
// Celle-ci peut �tre compl�t�e par la modifications des donn�es d'une photo
// ou par l'ajout d'un commentaire.

// Connexion au SGBD
$connect = connection();


if (isset($_POST['but']) && $_POST['but'] == 'ajout_commentaire') {
    // On r�cup�re toutes les infos via la m�thode POST
    $id_photo = $_POST['id'];
    $contenu = addslashes($_POST['contenu']);
    if ($id_photo != null) {

        if (isset($login)) {
            $requete = "INSERT INTO commentaire(contenu,id_photo,auteur)
		 VALUES ('$contenu',$id_photo,'$login')";
            $stmt = $connect->prepare($requete);
            $stmt->execute();
        }
    }

} else if (isset($_POST['but']) && $_POST['but'] == 'maj') {

    $id_photo = $_POST['id'];

    $date = verifie_date($_POST['date_photo']);

    $description = addslashes($_POST['description']);
    if ($id_photo != null) {

        $requete =
            "UPDATE photo
		 SET description = '$description',
		     date_photo = '$date'
		 WHERE id = $id_photo";

        $stmt = $connect->prepare($requete);
        $stmt->execute();
    }
} else {

    $id_photo = $_GET['id'];
}

?>
<html>
<head><title>Photo</title></head>
<body>
<h1>A faire: faire du nom du propri�taire un lien vers la liste de ses photos. Faire de m�me pour les personnes qui ont post� des commentaires</h1>
<h1>A faire: si la personne qui regarde la page est le propri�taire de la photo, ajouter un lien ou un formulaire � c�t� de chaque commentaire permettant de supprimer ce commentaire</h1>
<?php
// Affichage de la photo
if ($id_photo == null) {
    print "<p><b>Aucune photo de sp�cifi�e!</b></p>";
} else {

    $requete =
        "SELECT fichier,date_photo,description,proprietaire 
	   FROM photo 
	   WHERE id = $id_photo";
    // On execute la requete
    $resultat = $connect->prepare($requete);
    $resultat->execute();
    if ($nuplet =$resultat->fetch(PDO::FETCH_ASSOC)) {
        $proprietaire = $nuplet['proprietaire'];

        affiche_photo(
            $proprietaire,
            $nuplet['date_photo'],
            stripslashes($nuplet['description']),
            $nuplet['fichier']);

        $requete =
            "SELECT auteur, contenu, depot
	  	 FROM commentaire
	  	 WHERE id_photo = $id_photo";
        $resultat = $connect->prepare($requete);
        $resultat->execute();

        while ($nuplet =$resultat->fetch(PDO::FETCH_ASSOC)) {
            affiche_commentaire(
                $nuplet['auteur'],
                $nuplet['depot'],
                stripslashes($nuplet['contenu']));
        }


        print "<div>";

        print "<form action='photo.php' method='POST'>\n";
        print "<h3>Ajouter un commentaire</h3>";

        print "<p><textarea name='contenu' rows='10' cols='60'></textarea></p>\n";

        print "<input type='hidden' name='but' value='ajout_commentaire'>";
        print "<input type='hidden' name='id' value='$id_photo'>";

        print "<p><input type='submit' value='Ajouter'><input type='reset' value='Vider'></p>\n";
        print "</form>";
        print "</div>\n";
    } else {
        print "<p><b>Cette photo n'existe pas!</b></p>";
    }
}
?>
<hr>
<?php
if ($proprietaire == $login) {
    print "<p><a href='modifie_photo.php?id=$id_photo'>Modifier les informations sur cette photo</a>.</p>";
}
?>
<p><a href='index.php'>Retour a l'accueil</a></p>
</body>
</html>
<?php
$connect = null;
?>
