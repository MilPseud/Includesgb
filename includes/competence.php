<?php function competence()
{ global $db, $_POST, $_SESSION, $_GET;
if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{

$perso = intval($_GET['perso']);

$requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
$requser->execute(array($perso));
$userinfo = $requser->fetch();

if(!empty($_GET['del']))
{
	$deletecomp = $db->prepare('DELETE FROM competence_get WHERE player = ? AND comp = ?');
	$deletecomp->execute(array($perso, $_GET['del']));
	header('Location: index.php?p=competence&perso='.$perso.'');
}

if(!empty($_GET['add']))
{
	$reqcomp = $db->prepare('SELECT * FROM competence_get WHERE comp = ? AND player = ?');
	$reqcomp->execute(array($_GET['comp'], $perso));
	$compinfo2 = $reqcomp->fetch();

	$addexp = $db->prepare('UPDATE competence_get SET exp = ? WHERE player = ? AND comp = ?');
	$addexp->execute(array($compinfo2['exp'] + $_GET['add'], $perso, $_GET['comp']));
	header('Location: index.php?p=competence&perso='.$perso.'');
}

if(!empty($_GET['sup']))
{
	$reqcomp = $db->prepare('SELECT * FROM competence_get WHERE comp = ? AND player = ?');
	$reqcomp->execute(array($_GET['comp'], $perso));
	$compinfo2 = $reqcomp->fetch();
	
	$supexp = $db->prepare('UPDATE competence_get SET exp = ? WHERE player = ? AND comp = ?');
	$supexp->execute(array($compinfo2['exp'] - $_GET['sup'], $perso, $_GET['comp']));
	header('Location: index.php?p=competence&perso='.$perso.'');
}

if(isset($_POST['CompSubmit']))
{
	if(!empty($_POST['CompText']))
	{
		$verif = $db->prepare('SELECT * FROM competence_list WHERE name = ?');
		$verif->execute(array($_POST['CompText']));
		
		$verif2 = $db->prepare('SELECT * FROM competence_get WHERE player = ?');
		$verif2->execute(array($perso));	
		$v2 = $verif2->fetch();

		if ($v = $verif->fetch())
		{
			if($v['id'] != $v2['comp'])
			{
				$add = $db->prepare("INSERT INTO competence_get VALUES(?, ?, ?)");
				$add->execute(array($v['id'], '0', $perso));
				$erreur = "La Compétence a bien été ajoutée, merci de réactualiser par le bouton pour avant de quitter la page !";
				header('Location: index.php?p=competence&perso='.$perso.'');
			} else {
				$erreur = "Cet Utilisateur semble déjà avoir cette Compétence...";
			}
		} else {
			$erreur = "Cette Compétence ne semble exister ou alors mal orthographiée";
		}
	} else {
		$erreur = "Vous n'avez pas entrer de Compétence.";
	}
}

if ($userinfo['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($userinfo['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($userinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($userinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($userinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($userinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($userinfo['pnj'] == 1)? "#55FFFF" : $color;
				$color = ($userinfo['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($userinfo['pnj'] == 2)? "#0200A6" : $color;
				$color = ($userinfo['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}

$compsel = 1;
?>



<?php
if($_SESSION['id'] == $perso OR $_SESSION['rank'] >= 4)
{
?>
	<span style="font-size: 15px">Voici les Métiers et Compétences de</span> <span style="color: <?=$color?>;<?=$stylebasic?><?=$styledieu?>font-weight: bold;font-size: 1.1em;"><?=$userinfo['username']?></span>
	<br>
	<br>
	<?php
	if($_SESSION['rank'] >= 5)
	{
	?>
		<form method="POST">
			<input type="text" name="CompText" placeholder="Entrez la Compétence ici !">
			<input type="submit" name="CompSubmit" value="Valider !">
		</form>
		<br><?php echo "<span style='color: red'>".$erreur."</span>"; ?>
		<br>
	<?
	}
	?>

	<table style="border-radius: 10px;" cellspacing="5" cellpadding="5" align="center">
		<tbody>
			<?php
			while ($compsel >= 0)
			{
				switch ($compsel)
				{
					default : $name = "???"; break;
					case 1: $name = "Métiers"; $metierdb = 2; break;
					case 0: $name = "Compétences"; $metierdb = 1; break;
				}
				$compreq = $db->prepare('SELECT cget.comp, cget.exp, cget.player, clist.id, clist.metier, clist.name, clist.desc FROM competence_get cget RIGHT JOIN competence_list clist ON cget.comp = clist.id WHERE cget.player = ? AND clist.metier = ? ORDER BY clist.name asc');
				$compreq->execute(array($perso, $metierdb));
				?>
				<tr style="border:2px solid #FFA500;background-color:#FFD700;">
					<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:42%;" valign="center" align="center">
						<?=$name?>
					</th>
					<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:28%;" valign="center" align="center">
						Niveau
					</th>
					<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:28%" valign="center" align="center">
						Expérience
					</th>
				</tr>
				<?php
				while ($compinfo = $compreq->fetch())
				{
				?>
					<tr>
						<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
							<span>
								<?= $compinfo['name']?> <?php if($_SESSION['rank'] >= 4) { ?><a title="Retirer <?php if($compinfo['metier'] == 1) { ?>cette compétence<?php } elseif ($compinfo['metier'] == 2) { ?>ce métier<?php } ?>" href="index.php?p=competence&perso=<?=$perso?>&del=<?= $compinfo['id']?>"><span class="username-detail" style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">[X]</span></a><?php } ?>
							</span>
						</td>
						<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
							<span>
								<?php
								if($compinfo['exp'] <= 100)
								{
								echo "1";
								}
								elseif($compinfo['exp'] <= 200)
								{
								echo "2";
								}
								elseif($compinfo['exp'] <= 300)
								{
								echo "3";
								}
								elseif($compinfo['exp'] <= 400)
								{
								echo "4";
								}
								elseif($compinfo['exp'] <= 500)
								{
								echo "5";
								}
								elseif($compinfo['exp'] <= 600)
								{
								echo "6";
								}
								elseif($compinfo['exp'] <= 700)
								{
								echo "7";
								}
								elseif($compinfo['exp'] <= 800)
								{
								echo "8";
								}
								elseif($compinfo['exp'] <= 900)
								{
								echo "9";
								}
								elseif($compinfo['exp'] <= 1000)
								{
								echo "10";
								}
								elseif($compinfo['exp'] <= 1100)
								{
								echo "11";
								}
								elseif($compinfo['exp'] <= 1200)
								{
								echo "12";
								}
								elseif($compinfo['exp'] <= 1300)
								{
								echo "13";
								}
								elseif($compinfo['exp'] <= 1400)
								{
								echo "14";
								}
								elseif($compinfo['exp'] <= 1500)
								{
								echo "15";
								}
								elseif($compinfo['exp'] <= 1600)
								{
								echo "16";
								}
								elseif($compinfo['exp'] <= 1700)
								{
								echo "17";
								}
								elseif($compinfo['exp'] <= 1800)
								{
								echo "18";
								}
								elseif($compinfo['exp'] <= 1900)
								{
								echo "19";
								}
								elseif($compinfo['exp'] <= 2000)
								{
								echo "20";
								}
								elseif($compinfo['exp'] <= 2100)
								{
								echo "21";
								}
								elseif($compinfo['exp'] <= 2200)
								{
								echo "22";
								}
								elseif($compinfo['exp'] <= 2300)
								{
								echo "23";
								}
								elseif($compinfo['exp'] <= 2400)
								{
								echo "24";
								}
								elseif($compinfo['exp'] <= 2500)
								{
								echo "25";
								}
								elseif($compinfo['exp'] <= 2600)
								{
								echo "26";
								}
								elseif($compinfo['exp'] <= 2700)
								{
								echo "27";
								}
								elseif($compinfo['exp'] <= 2800)
								{
								echo "28";
								}
								elseif($compinfo['exp'] <= 2900)
								{
								echo "29";
								}
								elseif($compinfo['exp'] <= 3000)
								{
								echo "30";
								}
								elseif($compinfo['exp'] <= 3100)
								{
								echo "31";
								}
								elseif($compinfo['exp'] <= 3200)
								{
								echo "32";
								}
								elseif($compinfo['exp'] <= 3300)
								{
								echo "33";
								}
								elseif($compinfo['exp'] <= 3400)
								{
								echo "34";
								}
								elseif($compinfo['exp'] <= 3500)
								{
								echo "35";
								}
								elseif($compinfo['exp'] <= 3600)
								{
								echo "36";
								}
								elseif($compinfo['exp'] <= 3700)
								{
								echo "37";
								}
								elseif($compinfo['exp'] <= 3800)
								{
								echo "38";
								}
								elseif($compinfo['exp'] <= 3900)
								{
								echo "39";
								}
								elseif($compinfo['exp'] <= 4000)
								{
								echo "40";
								}
								elseif($compinfo['exp'] <= 4100)
								{
								echo "41";
								}
								elseif($compinfo['exp'] <= 4200)
								{
								echo "42";
								}
								elseif($compinfo['exp'] <= 4300)
								{
								echo "43";
								}
								elseif($compinfo['exp'] <= 4400)
								{
								echo "44";
								}
								elseif($compinfo['exp'] <= 4500)
								{
								echo "45";
								}
								elseif($compinfo['exp'] <= 4600)
								{
								echo "46";
								}
								elseif($compinfo['exp'] <= 4700)
								{
								echo "47";
								}
								elseif($compinfo['exp'] <= 4800)
								{
								echo "48";
								}
								elseif($compinfo['exp'] <= 4900)
								{
								echo "49";
								}
								elseif($compinfo['exp'] <= 5000)
								{
								echo "50";
								}
								?>
							</span>
						</td>
						<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
							<span>
								<?= $compinfo['exp']?> <br>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] <= 4950) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&add=50"><span class="username-detail" style="font-weight: bold; color: #0200A6" onmouseout="this.style.color='#0200A6'" onmouseover="this.style.color='white'">[+50]</span></a><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] <= 4970) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&add=30"><span class="username-detail" style="font-weight: bold; color: #0200A6" onmouseout="this.style.color='#0200A6'" onmouseover="this.style.color='white'">[+30]</span></a><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] <= 4990) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&add=10"><span class="username-detail" style="font-weight: bold; color: #0200A6" onmouseout="this.style.color='#0200A6'" onmouseover="this.style.color='white'">[+10]</span></a><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] <= 4995) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&add=5"><span class="username-detail" style="font-weight: bold; color: #0200A6" onmouseout="this.style.color='#0200A6'" onmouseover="this.style.color='white'">[+5]</span></a><br><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] >= 50) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&sup=50"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[-50]</span></a><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] >= 30) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&sup=30"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[-30]</span></a><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] >= 10) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&sup=10"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[-10]</span></a><?php } ?>
								<?php if($_SESSION['rank'] >= 4 AND $compinfo['exp'] >= 5) { ?><a href="index.php?p=competence&perso=<?=$perso?>&comp=<?= $compinfo['id']?>&sup=5"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[-5]</span></a><?php } ?>
							</span>
						</td>
					</tr>
				<?php
				}
				$compsel --;
			}
			?>
		</tbody>
	</table>
<?php
}
else
{
?>
	<span style="color: red">
		Vous n'avez pas le droit d'être ici...
	</span>
<?php
}
?>

<?php
}
?>

<?php
}
?>