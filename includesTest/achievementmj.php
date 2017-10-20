<?php function achievementmj()
{	global $db, $_POST, $_SESSION, $_GET;

if(isset($_GET['f']) AND $_GET['f'] == 'add')
{

if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
   $perso = intval($_GET['perso']);

   if($_SESSION['rank'] >= 5)
   {
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqperso = $db->prepare('SELECT * FROM page_perso WHERE id = ?');
   $reqperso->execute(array($perso)); 
   $persoinfo = $reqperso->fetch();
   
   $reqach = $db->prepare('SELECT * FROM achievement WHERE id = ?');
   $reqach->execute(array($perso)); 
   $achinfo = $reqach->fetch();
   
if(isset($_POST['fonda']))
{
   $ID = $perso;
   $fonda = htmlspecialchars($_POST['fonda']);
   
	$updateach = $db->prepare('UPDATE achievement SET fonda = ? WHERE id = ?');
    $updateach->execute(array('1', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['scenar']))
{
   $ID = $perso;
   $scenar = htmlspecialchars($_POST['scenar']);
   
	$updateach = $db->prepare('UPDATE achievement SET scenar = ? WHERE id = ?');
    $updateach->execute(array('1', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['code']))
{
   $ID = $perso;
   $code = htmlspecialchars($_POST['code']);
   
	$updateach = $db->prepare('UPDATE achievement SET code = ? WHERE id = ?');
    $updateach->execute(array('1', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['investi']))
{
   $ID = $perso;
   $investi = htmlspecialchars($_POST['investi']);
   
	$updateach = $db->prepare('UPDATE achievement SET investi = ? WHERE id = ?');
    $updateach->execute(array('1', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}


if(isset($_POST['del']))
{
   $ID = $perso;
   $del = htmlspecialchars($_POST['del']);
   $erreur = header('Location: index.php?p=achievementmj&f=del&perso='.$perso.'');
}

if(isset($_POST['back']))
{
   $ID = $perso;
   $back = htmlspecialchars($_POST['back']);
   $erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
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
	Addition des Succès
</span>
<br>
<br>
<br>
Définissez les Succès de <span style="color: <?=$color?>" class="username-detail"><?= $userinfo['username']?>
								 </span>
<br>
<br>
<form method="POST" action="index.php?p=achievementmj&f=add&perso=<?= $perso?>">
<input type="submit" name="del" value="Mode Suppression">
<input type="submit" name="back" value="Retour">
<br>
<br>
<center>
<table valign="center" class="achievementtablemj">
	<tbody>
		<tr>
			<?php
			if($achinfo['fonda'] == 0)
			{
			?>
			<td>
				<input type="submit" value="Fondateur" name="fonda" title="Ce Joueur a créer le Serveur GaaranStröm !" style="height: 100px;">
			</td>
			<?php
			}
			if($achinfo['scenar'] == 0)
			{
			?>
			<td>
				<input type="submit" value="Scenariste" name="scenar" title="Ce Joueur a créer le Serveur GaaranStröm !" style="height: 100px;">
			</td>
			<?php
			}
			if($achinfo['code'] == 0)
			{
			?>
			<td>
				<input type="submit" value="Codeur" name="code" title="Ce Joueur a participé au Codage (Site ou Serveur) !" style="height: 100px;">
			</td>
			<?php
			}
			if($achinfo['investi'] == 0)
			{
			?>
			<td>
				<input type="submit" value="Investi" name="investi" title="Ce Joueur s'est investi dans l'équipe de Build ou des Loristes !" style="height: 100px;">
			</td>
			<?php
			}
			?>
		</tr>
	</tbody>
</table>
<?php echo ''.$erreur.''; ?>
</center>
</form>
<?php
}
else
{
?>
<span style="font-weight: bold;font-size: 25px;">
	Addition des Succès
</span>
<br>
<br>
<span style="color: red;">
	Vous n'avez pas l'autorisation d'être ici...
</span>
<?php
}

}

}
if(isset($_GET['f']) AND $_GET['f'] == 'del')
{

if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
   $perso = intval($_GET['perso']);

   if($_SESSION['rank'] >= 5)
   {
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqperso = $db->prepare('SELECT * FROM page_perso WHERE id = ?');
   $reqperso->execute(array($perso)); 
   $persoinfo = $reqperso->fetch();
   
   $reqach = $db->prepare('SELECT * FROM achievement WHERE id = ?');
   $reqach->execute(array($perso)); 
   $achinfo = $reqach->fetch();
   
if(isset($_POST['fonda']))
{
   $ID = $perso;
   $fonda = htmlspecialchars($_POST['fonda']);
   
	$updateach = $db->prepare('UPDATE achievement SET fonda = ? WHERE id = ?');
    $updateach->execute(array('0', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['scenar']))
{
   $ID = $perso;
   $scenar = htmlspecialchars($_POST['scenar']);
   
	$updateach = $db->prepare('UPDATE achievement SET scenar = ? WHERE id = ?');
    $updateach->execute(array('0', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['code']))
{
   $ID = $perso;
   $code = htmlspecialchars($_POST['code']);
   
	$updateach = $db->prepare('UPDATE achievement SET code = ? WHERE id = ?');
    $updateach->execute(array('0', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['investi']))
{
   $ID = $perso;
   $investi = htmlspecialchars($_POST['investi']);
   
	$updateach = $db->prepare('UPDATE achievement SET investi = ? WHERE id = ?');
    $updateach->execute(array('0', $ID));
			
	$erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}

if(isset($_POST['add']))
{
   $ID = $perso;
   $add = htmlspecialchars($_POST['add']);
   $erreur = header('Location: index.php?p=achievementmj&f=add&perso='.$perso.'');
}

if(isset($_POST['back']))
{
   $ID = $perso;
   $back = htmlspecialchars($_POST['back']);
   $erreur = header('Location: index.php?p=achievement&perso='.$perso.'');
}
?>
<span style="font-weight: bold;font-size: 25px;">
	Suppression des Succès
</span>
<br>
<br>
<br>
Définissez les Succès de <span style="color: <?=$color?>" class="username-detail"><?= $userinfo['username']?>
								 </span>
<br>
<br>
<form method="POST" action="index.php?p=achievementmj&f=del&perso=<?= $perso?>">
<input type="submit" name="add" value="Mode Addition">
<input type="submit" name="back" value="Retour">
<br>
<br>
<center>
<table valign="center" class="achievementtablemj">
	<tbody>
		<tr>
			<?php
			if($achinfo['fonda'] == 1)
			{
			?>
			<td>
				<input type="submit" value="Fondateur" name="fonda" title="Ce Joueur a créer le Serveur GaaranStröm !" style="height: 100px;">
			</td>
			<?php
			}
			if($achinfo['scenar'] == 1)
			{
			?>
			<td>
				<input type="submit" value="Scenariste" name="scenar" title="Ce Joueur a créer le Serveur GaaranStröm !" style="height: 100px;">
			</td>
			<?php
			}
			if($achinfo['code'] == 1)
			{
			?>
			<td>
				<input type="submit" value="Codeur" name="code" title="Ce Joueur a participé au Codage (Site ou Serveur) !" style="height: 100px;">
			</td>
			<?php
			}
			if($achinfo['investi'] == 1)
			{
			?>
			<td>
				<input type="submit" value="Investi" name="investi" title="Ce Joueur s'est investi dans l'équipe de Build ou des Loristes !" style="height: 100px;">
			</td>
			<?php
			}
			?>
		</tr>
	</tbody>
</table>
<?php echo ''.$erreur.'';?>
</center>
</form>
<?php
}
else
{
?>
<span style="font-weight: bold;font-size: 25px;">
	Suppression des Succès
</span>
<br>
<br>
<span style="color: red;">
	Vous n'avez pas l'autorisation d'être ici...
</span>
<?php
}

}

}

}
?>