
<?php function candid()
{	global $db, $_POST, $_SESSION, $_GET;
?>

<?php

if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
   $perso = intval($_GET['perso']);
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqperso = $db->prepare('SELECT * FROM page_perso WHERE id = ?');
   $reqperso->execute(array($perso)); 
   $persoinfo = $reqperso->fetch();

   $reqcandid = $db->prepare('SELECT * FROM candid WHERE idjoueur = ?');
   $reqcandid->execute(array($perso)); 
   $candidinfo = $reqcandid->fetch();
   
   $reqalt = $db->prepare('SELECT * FROM div_alert WHERE id = ?');
   $reqalt->execute(array($perso)); 
   $altinfo = $reqalt->fetch();
?>
<div class="alertrouge">
<?php
if($_SESSION['id'] == $perso)
{
if(isset($_POST['valid']))
{
	$ID = $perso;
	$race = htmlspecialchars($_POST['race']);
	$Descp = htmlspecialchars($_POST['Descp']);
	$Descm = htmlspecialchars($_POST['Descm']);
	$bg = htmlspecialchars($_POST['bg']);
	$mc = htmlspecialchars($_POST['mc']);
	if(!empty($race) AND !empty($Descp) AND !empty($Descm) AND !empty($bg) AND !empty($mc))
	{
		if($_SESSION['id'] == $perso)
		{
			if(file_exists("pics/race/Race".$race.".png"))
			{
				$deletecandid = $db->prepare('DELETE FROM candid WHERE idjoueur = ?');
				$deletecandid->execute(array($ID));
			
				$updatecandid = $db->prepare('INSERT INTO candid(race, Descp, Descm, bg, idjoueur, Encours, mc) VALUES(?, ?, ?, ?, ?, ?, ?)');
				$updatecandid->execute(array($race, $Descp, $Descm, $bg, $ID, '1', $mc));
		
				$updatealt = $db->prepare('UPDATE div_alert SET Candid = ? WHERE id = ?');
				$updatealt->execute(array('1', $ID));
		
				$updateuser = $db->prepare('UPDATE member_list SET candid = ? WHERE id = ?');
				$updateuser->execute(array('1', $ID));
				header('Location: index.php');
			} else {
				$erreurRace = "La race que vous avez choisie n'existe pas ou vous l'avez mal orthographié !";
				}
		}
	}
}

switch ($userinfo['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($userinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($userinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($userinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($userinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($userinfo['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($userinfo['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($userinfo['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($userinfo['pnj'] == 2)? "#0200A6" : $color;
				$color = ($userinfo['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#000000"; break;
				case 7: $color = "#000000"; break;
			}
			
			if($userinfo['pnj'] == 2)
			{
			$styledieu = "text-shadow: 2px 2px 2px #FFFFFF;";	
			$stylebasic = false;
			}
			else
			{
			$styledieu = false;	
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			}

?>
	<?php
	if($altinfo['Candid'] == 1)
	{
	?>
	<span style="font-weight: bold;font-size: 25px;">
		Candidature
	</span>
	<br>
	<br>
	<br>
	Bienvenue <span class="username-detail" style="color: <?= $color?>;<?= $stylebasic?><?= $styledieu?>"><?= $userinfo['username']?> <?= $userinfo['nom']?>
	</span>	!
	<br>
	<br>
	<span style="color: red;">Ta Candidature est en cours de validation ! Patience !</span>
	<?php
	}
	elseif($altinfo['Candid'] == 2)
	{
	?>
	<span style="font-weight: bold;font-size: 25px;">
		Candidature
	</span>
	<br>
	<br>
	<br>
	Bienvenue <span class="username-detail" style="color: <?= $color?>;<?= $stylebasic?><?= $styledieu?>"><?= $userinfo['username']?> <?= $userinfo['nom']?>
	</span>	!
	<br>
	<br>
	<span style="color: red;">Ta Candidature est déjà validée !</span>
	<?php
	}
	else
	{
	?>
	<form method="POST" action="index.php?p=candid&perso=<?= $perso?>">
	<span style="font-weight: bold;font-size: 25px;">
		Candidature
	</span>
	<br>
	<br>
	<br>
	Bienvenue <span class="username-detail" style="color: <?= $color?>;<?= $stylebasic?><?= $styledieu?>"><?= $userinfo['username']?> <?= $userinfo['nom']?>
	</span>	!
	<br>
	<br>
	Tu es ici pour faire ta candidature ? Alors au boulot !
	<br>
	<br>
	Race du Personnage : <input type="text" name="race" value="<?=$userinfo['race']?>">
	<br>
	<span style="color: red"><?= $erreurRace?></span>
	<br>
	<span style="font-size: 12px;color: darkred">
		Notez bien le nom de la Race avec Majuscule et sans faute comme dans l'Onglet Race !
	</span>
	<br>
	<br>
	<br>
	<?php
	$Descp2 = preg_replace('#\n#', '<br>', $candidinfo['Descp']);
	$Descm2 = preg_replace('#\n#', '<br>', $candidinfo['Descm']);
	$bg2 = preg_replace('#\n#', '<br>', $candidinfo['bg']);
	?>
	Description Physique:
	<br>
	<textarea style="width: 500px; height: 200px;max-width: 500px; max-height: 200px;" name="Descp"><?=$Descp2?></textarea>
	<br>
	<br>
	<br>
	Description Mentale:
	<br>
	<textarea style="width: 500px; height: 200px;max-width: 500px; max-height: 200px;" name="Descm"><?=$Descm2?></textarea>
	<br>
	<br>
	<br>
	
	Récit RP:
	<br>
	<span style="font-size: 12px;color: darkred">
		Ici est demandé une Histoire RP (Rapport à GaaranStröm) pour tester votre orthographe et votre niveau de RP.
	</span>
	<br>
	<textarea style="width: 500px; height: 200px;max-width: 500px; max-height: 200px;" name="bg"><?=$bg2?></textarea>
	<br>
	<br>
	Pseudo Minecraft : <input type="text" name="mc" value="<?=$userinfo['pseudo']?>">
	<br>
	<br>
	<br>
	<input type="submit" name="valid" value="Envoyer !">
	</form>
	<?php
	}
	?>
<?php
}
else
{
?>
	<span style="font-weight: bold;font-size: 25px;">
		Candidature
	</span>
	<br>
	<br>
	<span style="color: red;">Vous n'avez pas l'autorisation d'être ici...</span>
<?php
}
?>
</div>
<?php
}

}
?>