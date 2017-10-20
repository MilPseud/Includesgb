<?php function atlas()
{
	global $db, $_SESSION, $_POST, $_GET;
?>
<center>
	Voici la Carte de GaaranStröm ! J'espère qu'elle vous sera utile !<br>
	<br>
	<br>
	L'Île Gaaran et ses périphéries:
	<br>
	<img src="pics/Accueil/Gaaran.png" width="60%">
	<?php
	if($_SESSION['rank'] >= 5)
	{
		?>
		<br>
		<br>
		L'Île Ström:
		<br>
		<img src="pics/Accueil/Ström.png" width="10%">
		<?php
	}
	?>
</center>
<?php
}
?>