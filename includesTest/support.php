<?php function support()
{	global $db, $_POST, $_SESSION, $_GET;

	if($_SESSION['connected'])
	{
		if($_SESSION['rank'] >= 0)
		{
			if(isset($_POST['send']))
			{
				$insertspr = $db->prepare('INSERT INTO support VALUES(?, ?, ?, ?, NOW(), ?, ?)');
				$insertspr->execute(array(NULL, $_POST['name'], $_POST['descriptif'], $_POST['errorlist'], $_SESSION['id'], 1));
				header('Location: index.php?p=support');
			}
			?>
			<form method="POST">
				<div class="nav">
					<ul>
						<li>
							<div class="link">
								Type de Rapport: <input name="errorlist" list="errorlists" placeholder="Rapport d'Erreur">
								<datalist id="errorlists">
									<option value="Rapport d'Erreur" checked>
									<option value="Proposition d'idée">
									<option value="Faute d'Orthographe/Frappe">
									<option value="Plainte">
									<option value="Bug/Triche">
								</datalist>
								<br>
								<br>
								Nom du Rapport: <input type="text" name="name" placeholder="Nom du Rapport">
								<br>
								<br>
								Rapport
								<br>
								<textarea style="max-width: 95%; min-width: 60%; min-height: 150px" name="descriptif" type="text" placeholder="Rapport"></textarea>
								<br>
								<br>
								<input type="submit" name="send" value="Envoyer le Rapport">
							</div>
						</li>
					</ul>
				</div>
			</form>
			<br>
			<br>
			<br>
			<?php
			if($_SESSION['rank'] >= 5)
			{
				$reqspr = $db->query('SELECT * FROM support ORDER BY date_send desc');
			}
			else
			{
				$reqspr = $db->prepare('SELECT * FROM support WHERE idplayer = ? ORDER BY date_send desc');
				$reqspr->execute(array($_SESSION['id']));
			}
			
			while($sprinfo = $reqspr->fetch())
			{
				$selectmbr = $db->prepare('SELECT * FROM member_list WHERE id = ?');
				$selectmbr->execute(array($sprinfo['idplayer']));
				$mbr = $selectmbr->fetch();
				
				if ($mbr['pnj'] == 2)
				{
					$stylebasic = false;
					$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
				}
				else
				{
					$stylebasic = "text-shadow: 2px 2px 2px #000000;";
					$styledieu = false;
				}
				
				switch ($mbr['rank'])
				{
					default : $color = "#555550"; break;
					case 1:  $color = "#00AA00"; $color = ($mbr['actif'] == 1)? "#FF5555" : $color;
					$color = ($mbr['digni'] == 3)? "#5555FF" : $color; break;
					case 2: $color = "#55FF55"; $color = ($mbr['actif'] == 1)? "#FF5555" : $color;
					$color = ($mbr['digni'] == 3)? "#5555FF" : $color; break;
					case 3: $color = "#FF55FF"; break;
					case 4: $color = "#00AAAA"; $color = ($mbr['pnj'] == 1)? "#55FFFF" : $color;
					$color = ($mbr['digni'] == 2)? "#FFFF55" : $color; break;
					case 5: $color = "#FFAA00"; $color = ($mbr['pnj'] == 2)? "#0200A6" : $color;
					$color = ($mbr['digni'] == 1)? "#AA00AA" : $color; break;
					case 6: $color = "#AA0000"; break;
					case 7: $color = "#000000"; break;
				}
				
				if(isset($_GET['statute']))
				{
					$statutemodif = $db->prepare('UPDATE support SET valid = ? WHERE id = ?');
					$statutemodif->execute(array($_GET['statute'], $_GET['rapport']));
					header('Location: index.php?p=support');
				}
				
				$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $sprinfo['date_send']);
				$text = preg_replace('#\n#', '<br>', $sprinfo['text']);
				?>
				<div class="nav">
					<ul>
						<li>
							<?=$sprinfo['type']?>
							<div class="link">
								<?=$sprinfo['name']?>
								<br>
								<br>
								<?php
								if(file_exists('pics/MiniImage/user_'.$sprinfo['idplayer'].'.png'))
								{
									?>
									<img src="pics/MiniImage/user_<?=$sprinfo['idplayer']?>.png">
									<?php
								}
								?>
								<span class="username-detail" style="color: <?=$color?>;<?=$stylebasic?><?=$styledieu?>">
									<?=$mbr['title']?> <?=$mbr['username']?>
								</span>
								<br>
								<?=$date?>
								<br>
								<br>
								Etat: <?php
								if($sprinfo['valid'] == 1)
								{
									echo "<span style='color: #FFAA00;'>Pas encore vue</span>";
								}
								elseif($sprinfo['valid'] == 2)
								{
									echo "<span style='color: #FFFF55;'>En cours</span>";
								}
								elseif($sprinfo['valid'] == 3)
								{
									echo "<span style='color: #55FF55;'>Correction appliquée</span>";
								}
								elseif($sprinfo['valid'] == 4)
								{
									echo "<span style='color: #AA0000;'>Correction impossible</span>";
								}
								?>
								<br>
								<?php
								if($_SESSION['rank'] >= 5)
								{
									?>
									<a title="Pas encore vue" href="index.php?p=support&rapport=<?=$sprinfo['id']?>&statute=1"><span style="font-weight: bold; color: #FFAA00" onmouseout="this.style.color='#FFAA00'" onmouseover="this.style.color='white'">[N]</span></a>
									<a title="En cours" href="index.php?p=support&rapport=<?=$sprinfo['id']?>&statute=2"><span style="font-weight: bold; color: #FFFF55" onmouseout="this.style.color='#FFFF55'" onmouseover="this.style.color='white'">[E]</span></a>
									<a title="Correction appliquée" href="index.php?p=support&rapport=<?=$sprinfo['id']?>&statute=3"><span style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'">[V]</span></a>
									<a title="Correction impossible" href="index.php?p=support&rapport=<?=$sprinfo['id']?>&statute=4"><span style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[F]</span></a>
									<?php
								}
								?>
								<br>
								<br>
								<?=$text?>
							</div>
						</li>
					</ul>
				</div>
				<br>
				<?php
			}
		}
	}
}
?>