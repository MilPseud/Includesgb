<?php function achievement()
{	global $db, $_POST, $_SESSION, $_GET;

if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
   $perso = intval($_GET['perso']);
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqperso = $db->prepare('SELECT * FROM page_perso WHERE id = ?');
   $reqperso->execute(array($perso)); 
   $persoinfo = $reqperso->fetch();
   
   $reqach = $db->prepare('SELECT * FROM achievement WHERE id = ?');
   $reqach->execute(array($perso)); 
   $achinfo = $reqach->fetch();
   
   $reqalt = $db->prepare('SELECT * FROM div_alert WHERE id = ?');
   $reqalt->execute(array($perso)); 
   $altinfo = $reqalt->fetch();
   
if(isset($_POST['add']))
{
   $ID = $perso;
   $add = htmlspecialchars($_POST['add']);
   $erreur = header('Location: index.php?p=achievementmj&f=add&perso='.$perso.'');
}
   
   if(isset($_POST['del']))
{
   $ID = $perso;
   $del = htmlspecialchars($_POST['del']);
   $erreur = header('Location: index.php?p=achievementmj&f=del&perso='.$perso.'');
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
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
?>
<span style="font-weight: bold;font-size: 25px;">
	Les Succès
</span>
<br>
<br>
<form method="POST" action="index.php?p=achievement&perso=<?= $perso?>">
<?php
if($_SESSION['rank'] >= 5)
{
?>
<input type="submit" name="add" value="Mode Addition">
<input type="submit" name="del" value="Mode Suppression">
<?php
}
?>
</form>
<br>
<br>
Voici la Liste des Succès de <span style="color: <?=$color?>" class="username-detail"><?= $userinfo['username']?>
							 </span>
<br>
<br>
<center>
<table valign="center" class="achievementtable">
	<tbody>
		<tr>
			<?php
			if($achinfo['fonda'] == 1)
			{
			?>
			<td>
				<img title="Ce Joueur a crée le Serveur GaaranStröm !" src="pics/achievements/0.png" alt="" width="100px" height="100px">
			</td>
			<?php
			}
			?>
			<?php
			if($achinfo['scenar'] == 1)
			{
			?>
			<td>
				<img title="Ce Joueur est le MJ Originel de GaaranStröm Papier !" src="pics/achievements/1.png" alt="" width="100px" height="100px">
			</td>
			<?php
			}
			?>
			<?php
				if($achinfo['code'] == 1)
				{
				?>
			<td>
				<img title="Ce Joueur a participé au Codage (Site ou Serveur) !" src="pics/achievements/2.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				?>
			<?php
				if($userinfo['digni'] == 1)
				{
				?>
			<td>
				<img title="Ce Joueur est un ancien MJS ! Tant de pouvoir !" src="pics/achievements/3.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				elseif($userinfo['digni'] == 2)
				{
				?>
			<td>
				<img title="Ce Joueur est un ancien MJ ! Quel talent !" src="pics/achievements/4.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				elseif($userinfo['digni'] == 3)
				{
				?>
			<td>
				<img title="Ce Joueur est un ancien Cadre ! Respect !" src="pics/achievements/5.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				?>
			<?php
				if($altinfo['candid'] == 2)
				{
				?>
			<td>
				<img title="Ce Joueur a fait sa Candidature !" src="pics/achievements/6.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				?>
			<?php
				if($userinfo['rank'] == 2)
				{
				?>
			<td>
				<img title="Ce Joueur a participé au RP Papier GaaranStröm !" src="pics/achievements/7.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				?>
			<?php
				if($achinfo['investi'] == 1)
				{
				?>
			<td>
				<img title="Ce Joueur s'est investi dans l'équipe de Build ou des Loristes !" src="pics/achievements/8.png" alt="" width="100px" height="100px">
			</td>
			<?php
				}
				?>
		</tr>
	</tbody>
</table>
<?php echo ''.$erreur.''; ?>
</center>
<?php
}

}
?>