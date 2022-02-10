<?php
include('boite_outils.php');
deconnexion();
// On charge la page login.php
charger_page("index.php");
// Au cas ou la redirection ne marche pas, on met un lien vers l'accueil
?>
<html>
<head><title>Deconnexion</title></head>
<body>
<p>Vous etes deconnectes.</p>
<p><a href='index.php'>Revenir a l'accueil</a>.</p>
</body>
</html>
