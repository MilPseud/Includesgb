<?php function delaccount()

{ global $db, $_POST, $_SESSION, $_GET;

	$perso = intval($_GET['perso']);
	$requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
    $requser->execute(array($perso)); 
    $userinfo = $requser->fetch();
	
	if(isset($_POST['yes']))
	{
		if($_SESSION['id'] == $perso)
		{
			$DEL = $db->prepare('UPDATE member_list SET desert = ? WHERE id = ?'); 
			$DEL->execute(array(1, $perso));
			
			$_SESSION = array();
			session_destroy();
			header('Location: index.php');
		}
		else
		{
			$DEL = $db->prepare('UPDATE member_list SET desert = ? WHERE id = ?'); 
			$DEL->execute(array(1, $perso));
			header('Location: index.php?p=profile&perso='.$perso.'');
		}
	}
	
	if(isset($_POST['no']))
	{
		header('Location: index.php?p=profile&perso='.$perso.'');
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
				case 4: $color = "#00AAAA"; $color = ($userinfo['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($userinfo['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($userinfo['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($userinfo['pnj'] == 2)? "#0200A6" : $color;
				$color = ($userinfo['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
	?>
	<center>
		<?php
		if($_SESSION['id'] == $perso)
		{
			?>
			Êtes-vous sûr de vouloir supprimer votre Compte ?
			<?php
		}
		else
		{
			?>
			Êtes-vous sûr de vouloir supprimer le Compte <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?=$userinfo['username']?></span> ?
			<?php
		}
		?>
		<br>
		<form method="POST">
			<input type="submit" name="yes" value="Supprimer">
			<input type="submit" name="no" value="Retour">
		</form>
	</center>
	<?php
}
?>