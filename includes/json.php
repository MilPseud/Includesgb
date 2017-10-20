<?php

require('includes/JSONAPI.php');
// get this file at: https://github.com/alecgorge/jsonapi/raw/master/sdk/php/JSONAPI.php

$ip = "77.192.102.189"; // Ip du serveur
$port = 20154; //port du plugin (par défaut : 20059)
$utilisateur = "ElenyahKenray"; //nom d'utilisateur
$motdepasse = "bonbonpkg2000"; //mot de passe
$salt = "salt"; 
$api = new JSONAPI($ip, $port, $utilisateur, $motdepasse, $salt); // on affiche le nombre de joueur

$PlayerCount = $api->call("getPlayerCount");
$PlayerLimit = $api->call("getPlayerLimit");
$ServerVersion = $api->call("getServer");
$PlayerNames = $api->call("getPlayerNames");

if ($ServerVersion["success"] == '') 
{ 
	echo "Serveur hors ligne "; 
} 
else 
{ 
	echo "Serveur en ligne "; 
	
	echo ' Nom du serveur : '; 
	print_r($ServerVersion["success"]["serverName"]); 
	
	echo " Nombre de joueur : "; 
	print_r($PlayerCount["success"]); 
	echo "/"; 
	print_r($PlayerLimit["success"]); 
	
	echo ' Version du Serveur : '; 
	print_r($ServerVersion["success"]["version"]); 
	
	echo '';
} 
?>