<?php function keylist()
{
	global $db, $_POST, $_GET, $_SESSION;

if ($_SESSION['rank'] >= 5)
{
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
			
$testkey = $db->query('SELECT * FROM key_list');
$getkey = $testkey->fetch();
			
if(!empty($_GET['upgrade']))
{
	$callkey = $db->prepare('SELECT * FROM key_list WHERE id = ?');
	$callkey->execute(array($_GET['key']));
	$levelkey = $callkey->fetch();

	$upgrade = $db->prepare('UPDATE key_list SET level = ? WHERE id = ?');
	$upgrade->execute(array($levelkey['level'] + $_GET['upgrade'], $_GET['key']));
	header('Location: index.php?p=keylist');
}

if(!empty($_GET['retrograde']))
{
	$callkey = $db->prepare('SELECT * FROM key_list WHERE id = ?');
	$callkey->execute(array($_GET['key']));
	$levelkey = $callkey->fetch();
	
	$retrograde = $db->prepare('UPDATE key_list SET level = ? WHERE id = ?');
	$retrograde->execute(array($levelkey['level'] - $_GET['retrograde'], $_GET['key']));
	header('Location: index.php?p=keylist');
}		
			
if(!empty($_GET['del']))
{
	$deletecomp = $db->prepare('DELETE FROM key_get WHERE keyd = ?');
	$deletecomp->execute(array($_GET['del']));
	
	$deleteget = $db->prepare('DELETE FROM key_list WHERE id = ?');
	$deleteget->execute(array($_GET['del']));
	header('Location: index.php?p=keylist');
}

if($_SESSION['rank'] >= 6)
{
	if(isset($_POST['addkey']))
	{
		if(!empty($_POST['name']) AND !empty($_POST['desc']))
		{
			if(strlen($_POST['name']) <= 225)
			{
				if($_POST['level'] >= 0)
				{
					if($_POST['level'] <= 8)
					{
						$namestr = htmlspecialchars($_POST['name']);
						$name = strtoupper($namestr);
						$desc = htmlspecialchars($_POST['desc']);
						$level = htmlspecialchars($_POST['level']);
						$insertkey = $db->prepare('INSERT INTO key_list VALUES(?, ?, ?, ?)');
						$insertkey->execute(array(NULL, $level, $name, $desc));
						$erreur = "Clé rajoutée !";
						header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
					} else {
						$erreur = "Ce niveau est inconnu...";
					}
				} else {
					$erreur = "Ce niveau est inconnu...";
				}
			} else {
				$erreur = "La Clé est trop longue... !";
			}
		} else {
			$erreur = "Il faut remplir tout les champs... !";
		}
	}
}
?>

<span style="font-weight: bold;font-size: 25px">
	Liste des Clés
</span>
<br>
<br>
<center><span style="font-weight: bold;font-size: 17px">Inscription de Clés</span></center>
<br>
<div class="nav">
	<ul>
		<li>
			<div class="link">
				<form method="POST" action="index.php?p=keylist">
					<table cellspacing="5" cellpadding="5" align="center">
						<tbody>
							<tr>
								<td rowspan="1">
									Clé
									<br>
									<input type="text" name="name" placeholder="Clé (En Latin !)">
								</td>
								<td rowspan="2">
									Description
									<br>
									<textarea style="height: 200px; width: 400px" type="text" name="desc" placeholder="Description de la Clé"></textarea>
								</td>
								<td rowspan="2">
									<input type="submit" name="addkey" value="Valider !">
								</td>
							</tr>
							<tr>
								<td rowspan="1">
									Niveau
									<br>
									<input type="text" name="level" placeholder="Niveau (De 0 à 7)">
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</li>
	</ul>
</div>
<br>
<span style="color: red"><?= $erreur?></span>
<br>
<br>
<table style="border-radius: 10px;" cellspacing="5" cellpadding="5" align="center">
	<tbody>	
			<tr style="border:2px solid #FFA500;background-color:#FFD700;">
			<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:18%;" valign="center" align="center">
				Clé
			</th>
			<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:38%;" valign="center" align="center">
				Description
			</th>
			<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:18%" valign="center" align="center">
				Niveau
			</th>
			</tr>
			<?php
			if($_SESSION['rank'] >= 6)
			{
			$selectreq = $db->query('SELECT * FROM key_list ORDER BY level desc, name asc');
			}
			else
			{
			$selectreq = $db->query('SELECT * FROM key_list WHERE level < 6 ORDER BY level desc, name asc');
			}
			while ($reqkey = $selectreq->fetch())
			{
			
			$desc = preg_replace('#\n#', '<br>', $reqkey['desc']);
			
			switch ($reqkey['level'])
			{
				default : $level = "#555550"; break;
				case 1: $level = "#00AA00"; break;
				case 2: $level = "#55FF55"; break;
				case 3: $level = "#FF55FF"; break;
				case 4: $level = "#00AAAA"; break;
				case 5: $level = "#FFAA00"; break;
				case 6: $level = "#AA0000"; break;
				case 7: $level = "#000000"; break;
			}
			?>
			<tr>
			<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
				<span style="text-shadow: 2px 2px 2px #000000; color: <?= $level?>; font-weight: bold; font-size: 1.1em;">
					<?= $reqkey['name']?>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Suprimer la Clé" href="index.php?p=keylist&del=<?= $reqkey['id']?>"><span style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">[X]</span></a><?php } ?>
					<?php if($reqkey['level'] <= 6)
					{
					?>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Monter d'un Niveau" href="index.php?p=keylist&key=<?= $reqkey['id']?>&upgrade=1"><span style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[+]</span></a><?php } ?>
					<?php
					}
					if($reqkey['level'] >= 1)
					{
					?>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Baisser d'un Niveau" href="index.php?p=keylist&key=<?= $reqkey['id']?>&retrograde=1"><span style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[-]</span></a><?php } ?>
					<?php
					}
					?>
				</span>
			</td>
			<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
				<span>
					<?= $desc?>
				</span>
			</td>
			<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
				<?php
				if(file_exists('pics/clemagie/CléLevel'.$reqkey['level'].'.png'))
				{
				?>
				<img width="100px" height="100px" src="pics/clemagie/CléLevel<?= $reqkey['level']?>.png">
				<?php
				}
				else
				{
				?>
				<img width="100px" height="100px" src="pics/clemagie/CléLevelInconnu.png">
				<?php
				}
				?>
			</td>
			</tr>
			<?php
			}
			?>
	</tbody>
</table>
<?php
}
elseif($_SESSION['rank'] <= 5)
{
?>
<span style="color: red">
	Vous n'avez pas le droit d'être ici !
</span>
<?php
}

}
?>