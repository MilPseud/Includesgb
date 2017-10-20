<?php function alert()
{	global $db, $_POST, $_SESSION, $_GET;

   $id = $_SESSION['id'];
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($id)); 
   $userinfo = $requser->fetch();
   
   $reqalt = $db->prepare('SELECT * FROM div_alert WHERE id = ?');
   $reqalt->execute(array($id));
   $altinfo = $reqalt->fetch();
?>

<div class="alertrouge">
	<span style="font-size: 20px;font-weight: bold;">
		Alpha !
	</span>
	<br>
	<br>
	<span style="font-size: 14px;">
		GaaranStröm est dans sa période Alpha !
		<br>
		Le Serveur est dès à présent ouvert !
		<br>
		Vous pourrez vous connecter après que votre candidature aie été validée !
		<br>
		<br>
		<img width="100%" src="http://status.mclive.eu/GaaranStröm/gaaranstrom.craft.gg/banner.png"></img>
	</span>
</div>

<?php
if($_SESSION['connected'])
{
	$countmp = $db->prepare('SELECT id FROM mp WHERE receiver = ? AND statut = 0');
	$countmp->execute(array($_SESSION['id']));
	$mpcount = $countmp->rowCount();
	if($mpcount >= 1)
	{
	?>
	<div class="alert">
		<span style="font-size: 20px;font-weight: bold;">
			<?=$mpcount?> nouveau<?php if($mpcount >= 2) { ?>x<?php } ?> Messages !
		</span>
		<br>
		<br>
		<span style="font-size: 14px;">
			Vous pouvez les lire dès maintenant !
			<br>
			<br>
			<a style="font-size: 17px;" href="index.php?p=mp&mode=read">
				Cliquez ici !
			</a>
		</span>
	</div>
	<?php
	}
}
?>

<?php
if($_SESSION['connected'])
{
if($_SESSION['rank'] == 0)
{
	if($altinfo['Candid'] == 0)
	{
	?>
	<div class="alert">
		<span style="font-size: 20px;font-weight: bold;">
			La Candidature !
		</span>
		<br>
		<br>
		<span style="font-size: 14px;">
			Il est temps de faire votre Candidature ! Voici le lien !
			<br>
			<a style="font-size: 17px;" href="index.php?p=candid&perso=<?= $_SESSION['id']?>">
				Cliquez ici !
			</a>
		</span>
	</div>
	<?php
	}
	elseif($altinfo['Candid'] == 1)
	{
	?>
	<div class="alert">
		<span style="font-size: 20px;font-weight: bold;">
			La Candidature !
		</span>
		<br>
		<br>
		<span style="font-size: 14px;">
			Votre Candidature a bien était reçue et est en cours de Validation !
			<br>
			Merci de bien vouloir patienter !
		</span>
	</div>
	<?php
	}
	elseif($altinfo['Candid'] == 3)
	{
	?>
	<div class="alert">
		<span style="font-size: 20px;font-weight: bold;">
			La Candidature !
		</span>
		<br>
		<br>
		<span style="font-size: 14px;">
			Votre Candidature a été refusée... Mais vous pouvez toujours retenter !
			<br>
			<a style="font-size: 17px;" href="index.php?p=candid&perso=<?= $_SESSION['id']?>">
				Cliquez ici !
			</a>
		</span>
	</div>
	<?php
	}
}

}
?>

<?php
if($_SESSION['connected'])
{
if($_SESSION['rank'] >= 0)
{
	if($altinfo['Editperso'] == 0)
	{
	?>
	<div class="alert">
		<span style="font-size: 20px;font-weight: bold;">
			Votre personnage !
		</span>
		<br>
		<br>
		<span style="font-size: 14px;">
			Vous devez impérativement remplir votre fiche personnage !
			<br>
			<br>
			<a style="font-size: 17px;" href="index.php?p=editprofile&perso=<?= $_SESSION['id']?>">
				Cliquez ici !
			</a>
		</span>
	</div>
	<?php
	}
}
}
?>

<?php
if($_SESSION['connected'])
{
if(($_SESSION['rank'] >= 4 AND $_SESSION['pnj'] == 0) OR ($_SESSION['digni'] != 0))
{
	if($altinfo['staffteam'] == 0)
	{
	?>
	<div class="alert">
		<span style="font-size: 20px;font-weight: bold;">
			Votre présentation !
		</span>
		<br>
		<br>
		<span style="font-size: 14px;">
			En tant que Cadre fraichement gradé, vous pouvez avoir une présentation de vous même sur la Page du Staff !
			<br>
			<br>
			<a style="font-size: 17px;" href="index.php?p=staffedit&perso=<?= $_SESSION['id']?>">
				Cliquez ici !
			</a>
		</span>
	</div>
	<?php
	}
}
}
?>

<?php
}
?>