<?php function enca()
{	global $db, $_POST, $_SESSION, $_GET;
	if($_SESSION['rank'] >= 4)
	{
		if(isset($_POST['senca']))
		{
			if(!empty($_POST['jenca']) AND !empty($_POST['tenca']) AND !empty($_POST['denca']))
			{
				$idplayer2 = $db->prepare('SELECT * FROM member_list WHERE username = ?');
				$idplayer2->execute(array($_POST['jenca']));
				$player2 = $idplayer2->rowCount();
				
				if($player2 >= 1)
				{
					$idplayer = $db->prepare('SELECT * FROM member_list WHERE username = ?');
					$idplayer->execute(array($_POST['jenca']));
					$player = $idplayer->fetch();
					
					$insertenca = $db->prepare('INSERT INTO enca VALUES(?, ?, ?, ?, ?, NOW(), ?, ?)');
					$insertenca->execute(array(NULL, $_SESSION['id'], '0', $player['id'], $_POST['tenca'], $_POST['denca'], '0'));
					header('Location: index.php?p=enca');
				} else {
					$erreur = "Le joueur encadré n'existe pas !";
				}
			} else {
				$erreur = "Remplissez tout les champs.. !";
			}
		}
		?>
		<center>
			<span style="font-size: 20px">
				Encadrement
			</span>
		</center>
		
		<div class="nav" align="center" width="95%">
			<ul>
				<li>
					<div class="link">
						<form method="POST" action="index.php?p=enca&lol=1">
							Joueur Encadré: <input type="text" name="jenca" placeholder="Prénom UNIQUEMENT !">
							<br>
							<br>Type d'Encadrement: <input type="text" name="tenca" placeholder="Ex: 'Escalade'">
							<br>
							<br><span style="color: red"><?=$erreur?></span>
							<br>
							<br><textarea style="max-width: 95%; min-width: 60%; min-height: 150px" type="text" name="denca" placeholder="Description de l'Encadrement"></textarea>
							<br>
							<br><input type="submit" name="senca" value="Envoyer !">
						</form>
					</div>
				</li>
			</ul>
		</div>
		<br>
		<?php
		$reqenca = $db->query('SELECT * FROM enca ORDER BY valid asc, id desc');
		?>
		<center>
			<span style="font-size: 20px">
				Liste des Encadrements
			</span>
		</center>
		<?php
		while($encainfo = $reqenca->fetch())
		{
			$reqmember1 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$reqmember1->execute(array($encainfo['cadre']));
			$cadre = $reqmember1->fetch();
			
			$reqmember3 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$reqmember3->execute(array($encainfo['mj']));
			$mj = $reqmember3->fetch();
			
			$reqmember2 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$reqmember2->execute(array($encainfo['joueur']));
			$joueur = $reqmember2->fetch();
			
			if ($cadre['pnj'] == 2)
			{
				$stylebasic = false;
				$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
				$stylebasic = "text-shadow: 2px 2px 2px #000000;";
				$styledieu = false;
			}
			
			switch ($cadre['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($cadre['actif'] == 1)? "#FF5555" : $color;
				$color = ($cadre['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($cadre['actif'] == 1)? "#FF5555" : $color;
				$color = ($cadre['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($cadre['pnj'] == 1)? "#55FFFF" : $color;
				$color = ($cadre['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($cadre['pnj'] == 2)? "#0200A6" : $color;
				$color = ($cadre['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			
			if ($joueur['pnj'] == 2)
			{
				$stylebasic2 = false;
				$styledieu2 = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
				$stylebasic2 = "text-shadow: 2px 2px 2px #000000;";
				$styledieu2 = false;
			}
			
			switch ($joueur['rank'])
			{
				default : $color2 = "#555550"; break;
				case 1:  $color2 = "#00AA00"; $color2 = ($joueur['actif'] == 1)? "#FF5555" : $color2;
				$color2 = ($joueur['digni'] == 3)? "#5555FF" : $color2; break;
				case 2: $color2 = "#55FF55"; $color2 = ($joueur['actif'] == 1)? "#FF5555" : $color2;
				$color2 = ($joueur['digni'] == 3)? "#5555FF" : $color2; break;
				case 3: $color2 = "#FF55FF"; break;
				case 4: $color2 = "#00AAAA"; $color2 = ($joueur['pnj'] == 1)? "#55FFFF" : $color2;
				$color2 = ($joueur['digni'] == 2)? "#FFFF55" : $color2; break;
				case 5: $color2 = "#FFAA00"; $color2 = ($joueur['pnj'] == 2)? "#0200A6" : $color2;
				$color2 = ($joueur['digni'] == 1)? "#AA00AA" : $color2; break;
				case 6: $color2 = "#AA0000"; break;
				case 7: $color2 = "#000000"; break;
			}
			
			if ($mj['pnj'] == 2)
			{
				$stylebasic3 = false;
				$styledieu3 = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
				$stylebasic3 = "text-shadow: 2px 2px 2px #000000;";
				$styledieu3 = false;
			}
			
			switch ($mj['rank'])
			{
				default : $color3 = "#555550"; break;
				case 1:  $color3 = "#00AA00"; $color3 = ($joueur['actif'] == 1)? "#FF5555" : $color3;
				$color3 = ($joueur['digni'] == 3)? "#5555FF" : $color3; break;
				case 2: $color3 = "#55FF55"; $color3 = ($joueur['actif'] == 1)? "#FF5555" : $color3;
				$color3 = ($joueur['digni'] == 3)? "#5555FF" : $color3; break;
				case 3: $color3 = "#FF55FF"; break;
				case 4: $color3 = "#00AAAA"; $color3 = ($joueur['pnj'] == 1)? "#55FFFF" : $color3;
				$color3 = ($joueur['digni'] == 2)? "#FFFF55" : $color3; break;
				case 5: $color3 = "#FFAA00"; $color3 = ($joueur['pnj'] == 2)? "#0200A6" : $color3;
				$color3 = ($joueur['digni'] == 1)? "#AA00AA" : $color3; break;
				case 6: $color3 = "#AA0000"; break;
				case 7: $color3 = "#000000"; break;
			}
			
			if(isset($_GET['valid']))
			{
				$valid = $db->prepare('UPDATE enca SET valid = 1, mj = ? WHERE id = ?');
				$valid->execute(array($_SESSION['id'], $_GET['enca']));
				
				header('Location: index.php?p=enca');
			}
			
			if(isset($_GET['denied']))
			{
				$denied = $db->prepare('UPDATE enca SET valid = 2, mj = ? WHERE id = ?');
				$denied->execute(array($_SESSION['id'], $_GET['enca']));
				
				header('Location: index.php?p=enca');
			}
			
			$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $encainfo['date_send']);
			$enca2 = preg_replace('#\n#', '<br>', $encainfo['enca']);
			
			if($encainfo['valid'] == 0)
			{
				?>
				<div class="nav" align="center" width="95%">
				<?php
			}
			elseif($encainfo['valid'] == 2)
			{
				?>
				<div class="navrefused" align="center" width="95%">
				<?php
			}
			elseif($encainfo['valid'] == 1)
			{
				?>
				<div class="navvalid" align="center" width="95%">
				<?php
			}
			?>
				<span style="font-size: 16px"><?=$encainfo['enca']?></span>
				<ul>
					<li>
						<?php
						if($encainfo['valid'] == 0)
						{
							?>
							<div class="link">
							<?php
						}
						elseif($encainfo['valid'] == 1)
						{
							?>
							<div class="linkvalid">
							<?php
						}
						elseif($encainfo['valid'] == 2)
						{
							?>
							<div class="linkrefused">
							<?php
						}
							if($_SESSION['rank'] >= 5)
							{
								if($encainfo['valid'] == 0)
								{
									?>
									<img width="200px" src="pics/Accueil/inprogress.png"> <form method="POST" action="index.php?p=enca&enca=<?=$encainfo['id']?>&valid=1">
									<input type="submit" name="valid" value="Valider"></form> <form method="POST" action="index.php?p=enca&enca=<?=$encainfo['id']?>&denied=2">
									<input type="submit" name="denied" value="Refuser"></form>
									<?php
								}
								elseif($encainfo['valid'] == 1)
								{
									?>
									<img width="200px" src="pics/Accueil/approuved.png">
									<br>
									Par: <span class="username-detail" style="color: <?=$color3?>;<?=$stylebasic3?><?=$styledieu3?>"><?=$mj['username']?></span>
									<?php
								}
								elseif($encainfo['valid'] == 2)
								{
									?>
									<img width="200px" src="pics/Accueil/denied.png">
									<br>
									Par: <span class="username-detail" style="color: <?=$color3?>;<?=$stylebasic3?><?=$styledieu3?>"><?=$mj['username']?></span>
									<?php
								}
							}
							?>
							<br>
							<br>
							<?=$date?>
							<br>
							<br>
							Encadrant: <span class="username-detail" style="color: <?=$color?>;<?=$stylebasic?><?=$styledieu?>"><?=$cadre['username']?></span>
							<br>
							Joueur Encadré: <span class="username-detail" style="color: <?=$color2?>;<?=$stylebasic2?><?=$styledieu2?>"><?=$joueur['username']?></span>
							<br>
							<br><?=$enca2?>
						</div>
					</li>
				</ul>
			</div>
			<br>
			<?php
		}
	}
	else
	{
		?>
		<span class="username-detail" style="color: darkred;">
			Vous n'avez pas l'autorisation d'être ici...
		</span>
		<?php
	}
}
?>