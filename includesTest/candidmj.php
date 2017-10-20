<?php function candidmj()
{	global $db, $_POST, $_SESSION, $_GET;
?>
<div class="alertrouge">
<?php
if($_SESSION['rank'] >= 4)
{

if(isset($_GET['perso']) AND $_GET['perso'] == 0)
{
?>
<span style="font-weight: bold;font-size: 25px;">
	Validation des Candidatures
</span>
<br>
<br>
<br>
Ici sont répertoriées les candidatures en attente des Visiteurs.
<br>
<br>
 Bonne lecture et réfléchissez bien avant de prendre votre décision finale !
<br>
<br>
<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
<tbody>

<tr style="border:2px solid #FFA500;background-color:#FFD700;">
		<th style="background-color:#FFD700;height:30px;width:30px" align="center"><font style="margin-left:10px"></font></th>
		<th style="background-color:#FFD700;height:30px;width:20px" align="center"><font style="margin-left:10px"></font></th>
		<th style="background-color:#FFD700;height:30px;width:30%;" align="left">En attente</th>
		<th style="background-color:#FFD700;height:30px;width:20%;" align="left">Titre</th>
		<th style="background-color:#FFD700;height:30px;width:50%" align="center">Race.</th>
	</tr>
<?php

$select = $db->query("SELECT * FROM member_list WHERE candid = '1' ORDER BY username");

while ($line = $select->fetch())

{


?>

<tr style="border:2px solid #8B4513;background-color:#A0522D;">
       <td style="height:25px" valign="middle" align="center">
                 <span class="member_list"><img width="24px" src="pics/rank/Grade<?= $line['rank']?>.png"></span>
       </td>
       <td style="height:25px" valign="middle" align="center">
<?php

if (file_exists("pics/MiniImage/user_".$line['id'].".png")) {
?>
                 <span class="member_list"><img width="32px" src="pics/MiniImage/user_<?= $line['id']?>.png"></span>
<?php
}
?>
       </td>
       <td style="height:25px" valign="middle" align="left">
                 <a href="index.php?p=candidmj&perso=<?= $line["id"]?>">
					<span class="member_list" title="Pseudo Minecraft: <?= $line['pseudo']?>">
						<?= $line['username']?> <?= $line['nom']?>
					</span>
				</a>
       </td>
       <td style="height:25px" valign="middle" align="left">
                 <span class="member_list"><?= $line['title']?></span>
       </td>
       <td style="height:25px" valign="middle" align="center">
                 <span class="member_list"><img width="18px" src="pics/race/Race<?= $line['race']?>.png"></span>
</tr>

<?php
}
?>

</tbody>
</table>
<br>
<?php
}

if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
   $perso = intval($_GET['perso']);
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqcandid = $db->prepare('SELECT * FROM candid WHERE idjoueur = ?');
   $reqcandid->execute(array($perso)); 
   $candidinfo = $reqcandid->fetch();
   
   $reqalt = $db->prepare('SELECT * FROM div_alert WHERE id = ?');
   $reqalt->execute(array($perso)); 
   $altinfo = $reqalt->fetch();
   
   $reqcanduser = $db->prepare('SELECT c.idcandid, c.idjoueur, c.Encours, c.race, c.Descp, c.Descm, c.bg, c.mc, m.id, m.username, m.nom, m.rank, m.candid FROM candid c RIGHT JOIN member_list m ON c.idjoueur = m.id WHERE c.idjoueur = ?');
   $reqcanduser->execute(array($perso)); 
   $canduserinfo = $reqcanduser->fetch();
   
if(isset($_POST['valid']))
{
	$ID = $perso;
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $ID, "".$_SESSION["username"]." a validé la Candidature de ".$canduserinfo["username"]."", '1', '+'));

	$updateuser = $db->prepare('UPDATE member_list SET race = ?, rank = ?, candid = ? WHERE id = ?');
	$updateuser->execute(array($candidinfo['race'], '1', '0', $ID));
	
	$updatealt = $db->prepare('UPDATE div_alert SET Candid = ? WHERE id = ?');
	$updatealt->execute(array('2', $ID));
	
	$updatecandid = $db->prepare('UPDATE candid SET Encours = ? WHERE idjoueur = ?');
	$updatecandid->execute(array('2', $ID));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES( ?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold; color: #00AAAA">Félicitations à '.$canduserinfo['username'].' pour sa Candidature !</span>', 22));
	
	$insertmp = $db->prepare('INSERT INTO mp VALUES(?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)');
	$insertmp->execute(array(NULL, "22", $canduserinfo['id'], "Validation de Candidature", "Félicitations ! Vous avez réussi votre Candidature !<br><br>Voici le message laissé par le MJ:<br><br>----------<br>". $_POST['msgrefus']."<br><br>----------<br>L'ip du Serveur est: <strong>gaaranstrom.craft.gg</strong><br>(Whitelist tout les jours à 18H, suivi de l'intégration suivant à la même heure !)<br><br>Bonne journée sur GaaranStröm.<br><br>-".$_SESSION['username']."", '0', '0', '0', '0'));
	header('Location: index.php');
}

if(isset($_POST['refuse']))
{
	$ID = $perso;
	$updateuser = $db->prepare('UPDATE member_list SET candid = ? WHERE id = ?');
	$updateuser->execute(array('0', $ID));
	
	$updatealt = $db->prepare('UPDATE div_alert SET Candid = ? WHERE id = ?');
	$updatealt->execute(array('3', $ID));
	
	$updatecandid = $db->prepare('UPDATE candid SET Encours = ? WHERE idjoueur = ?');
	$updatecandid->execute(array('0', $ID));
	
	$insertmp = $db->prepare('INSERT INTO mp VALUES(?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)');
	$insertmp->execute(array(NULL, "22", $canduserinfo['id'], "Refus de Candidature", "Nous sommes dans le regrès de vous annoncer que votre Candidature a était refusée.<br><br>Voici le message laissé par le MJ:<br><br>----------<br>". $_POST['msgrefus']."<br><br>----------<br>Bonne fin de journée.<br><br>-".$_SESSION['username']."", '0', '0', '0', '0'));
	header('Location: index.php');
}

	switch ($canduserinfo['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($canduserinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($canduserinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($canduserinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($canduserinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($canduserinfo['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($canduserinfo['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($canduserinfo['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($canduserinfo['pnj'] == 2)? "#0200A6" : $color;
				$color = ($canduserinfo['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			
			if($canduserinfo['pnj'] == 2)
			{
			$styledieu = "text-shadow: 2px 2px 2px #FFFFFF;";	
			$stylebasic = false;
			}
			else
			{
			$styledieu = false;	
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			}
if($altinfo['Candid'] == 0)
{
?>
<span style="font-weight: bold;font-size: 25px;">
	Candidature de <span class="username-detail" style="color: <?= $color?>;<?= $stylebasic?><?= $styledieu?>"><?= $canduserinfo['username']?> <?= $canduserinfo['nom']?>
	</span>
</span>
<br>
<br>
<span style="color: red;">Ce Visiteur n'a pas encore fait de Candidature !</span>
<?php
}
elseif($altinfo['Candid'] == 1 OR $altinfo['Candid'] == 2 OR $altinfo['Candid'] == 3)
{
$Descp = preg_replace('#\n#', '<br>', $canduserinfo['Descp']);
$Descm = preg_replace('#\n#', '<br>', $canduserinfo['Descm']);
$bg = preg_replace('#\n#', '<br>', $canduserinfo['bg']);
?>
	<form method="POST" action="index.php?p=candidmj&perso=<?= $perso?>">
	<span style="font-weight: bold;font-size: 25px;">
		Candidature de <span class="username-detail" style="color: <?= $color?>;<?= $stylebasic?><?= $styledieu?>"><?= $canduserinfo['username']?> <?= $canduserinfo['nom']?>
		</span>
	</span>
	<br>
	<br>
	<br>
	Race du Personnage : <?= $candidinfo['race']?>
	<br>
	<br>
	<br>
	Description Physique:
	<br>
	<?= $Descp?>
	<br>
	<br>
	<br>
	Description Mentale:
	<br>
	<?= $Descm?>
	<br>
	<br>
	<br>
	
	Récit RP:
	<br>
	<?= $bg?>
	<br>
	<br>
	Pseudo Minecraft : <a href="https://fr.namemc.com/profile/<?=$canduserinfo['mc']?>"><?= $canduserinfo['mc']?></a>
	<br>
	<span style="font-size: 12px;color: darkred">Cliquez sur le pseudo pour voir le skin.</span>
	<br>
	<br>
	<br>
	<?php
	if($canduserinfo['candid'] == 1)
	{
	if($_SESSION['rank'] >= 5)
	{
	?>
	<center>
		<textarea style="min-height: 300px;min-width: 500px" name="msgrefus" placeholder="Message personnel à laisser au Joueur en cas de Validation/Refus"></textarea>
		<br>
		<br>
		<input type="submit" name="valid" value="Valider la Candidature !">
		<input type="submit" name="refuse" value="Refuser la Candidature...">
	</center>
	<?php
	}
	}
	else
	{
	if($altinfo['Candid'] == 2)
	{
	?>
	La Candidature de ce joueur a déja été validée !
	<?php
	}
	elseif($altinfo['Candid'] == 3)
	{
	?>
	La Candidature de ce Joueur a déjà été refusée...
	<?php
	}
	}
	?>
	</form>
<?php
}
?>
<?php
}

}
else
{
?>
<span style="font-weight: bold;font-size: 25px;">
		Validation des Candidatures
</span>
<br>
<br>
<span style="color: red;">
	Vous n'avez pas l'autorisation d'être ici...
</span>
<?php
}
?>
</div>
<?php
}
?>