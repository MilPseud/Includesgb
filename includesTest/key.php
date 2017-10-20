<?php function sucesskey()
{ global $db, $_POST, $_SESSION, $_GET;
if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{

$perso = intval($_GET['perso']);
$requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
$requser->execute(array($perso));
$userinfo = $requser->fetch();

$ID = $perso;
$KeyTextSTR = htmlspecialchars($_POST['Key']);
$KeyText = strtoupper($KeyTextSTR);

if(!empty($_GET['rp']))
{
		$verif2 = $db->prepare('SELECT * FROM key_get WHERE keyd = ? AND id = ?');
		$verif2->execute(array($_GET['rp'], $perso));	
		$v2 = $verif2->rowCount();
		
		$verif = $db->prepare('SELECT * FROM key_list WHERE id = ?');
		$verif->execute(array($_GET['rp']));
		$v = $verif->fetch();
		
			if($v2 == 0)
			{
				if($v['level'] == 0)
				{
					$add2 = $db->prepare("UPDATE member_list SET keycount = ? WHERE id = ?");
					$add2->execute(array($userinfo['keycount'] + 1, $userinfo['id']));
					$add = $db->prepare("INSERT INTO key_get VALUES(?, ?, 1)");
				}
				else
				{
					$add = $db->prepare("INSERT INTO key_get VALUES(?, ?, 0)");
				}
				$add->execute(array($_GET['rp'], $perso));
				if($v['level'] == 0)
				{
				$erreur = "Félicitations ! Vous avez une nouvelle Clé !";
				} else {
				$erreur = "Félicitations ! Vous avez une nouvelle Clé !<br>En attente de la Validation d'un MJE ou plus !";
				}
				header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
			} else {
				$erreur = "Vous semblez déjà avoir cette clé en votre possession...";
			}
}

if(isset($_POST['KeySubmit']))
{
	if(!empty($KeyText))
	{
		$verif = $db->prepare('SELECT * FROM key_list WHERE name = ?');
		$verif->execute(array($KeyText));

		if ($v = $verif->fetch())
		{
		$verif2 = $db->prepare('SELECT * FROM key_get WHERE keyd = ? AND id = ?');
		$verif2->execute(array($v['id'], $perso));	
		$v2 = $verif2->rowCount();
		
			if($v2 == 0)
			{
				if($v['level'] == 0)
				{
					$add2 = $db->prepare("UPDATE member_list SET keycount = ? WHERE id = ?");
					$add2->execute(array($userinfo['keycount'] + 1, $userinfo['id']));
					$add = $db->prepare("INSERT INTO key_get VALUES(?, ?, ?, 1)");
				}
				else
				{
					$add = $db->prepare("INSERT INTO key_get VALUES(?, ?, ?, 0)");
				}
				$add->execute(array(NULL, $v['id'], $perso));
				if($v['level'] == 0)
				{
				$erreur = "Félicitations ! Vous avez une nouvelle Clé !";
				} else {
				$erreur = "Félicitations ! Vous avez une nouvelle Clé !<br>En attente de la Validation d'un MJE ou plus !";
				}
				header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
			} else {
				$erreur = "Vous semblez déjà avoir cette clé en votre possession...";
			}
		} else {
			$erreur = "Votre Clé ne fonctionne pas ! Dommage !";
		}
	} else {
		$erreur = "Vous n'avez pas entrer de clé !";
	}
}

if(!empty($_GET['del']))
{
	$deleteget = $db->prepare('DELETE FROM key_get WHERE keyd = ? AND id = ? AND valid = ?');
	$deleteget->execute(array($_GET['del'], $perso, '0'));
	header('Location: index.php?p=sucesskey&perso='.$perso.'');
}

if(!empty($_GET['valid']))
{
	$validget = $db->prepare('UPDATE key_get SET valid = ? WHERE id = ? AND keyd = ? AND valid = ?');
	$validget->execute(array($_GET['valid'], $perso, $_GET['key'], '0'));
	
	$addvalid = $db->prepare("UPDATE member_list SET keycount = ? WHERE id = ?");
	$addvalid->execute(array($userinfo['keycount'] + 1, $userinfo['id']));
	header('Location: index.php?p=sucesskey&perso='.$perso.'');
}

if(isset($_POST['return']))
{
	header('Location: index.php?p=sucesskey');
}
?>

<?php
if($_SESSION['id'] == $perso)
{
?>
<span style="font-weight: bold;font-size: 25px">
	Clés
</span>
<br>
<br>Vous êtes bloqués dans une des énigmes de GaaranStröm ?
<br>Certains secrets n'ont pas étés découverts ?
<br>Cette section est là pour vous donner ces réponses !
<br>
<br>
<?php
if($_SESSION['rank'] >= 5)
{
	?>
	<form method="POST">
	<input type="submit" name="return" value="Retour à liste des Clés obtenues">
	</form>
	<?php
}
?>
<br>Entrez des clés que vous trouverez en jeu dans cette section, et peut-être que vous apprendrez des choses sur notre univers ?
<br>
<br>
<form method="POST" action="#">
<input type="text" name="Key" placeholder="Entrez la Clé ici !">
<input type="submit" name="KeySubmit" value="Valider !">
</form>
<br><?php echo "<span style='color: red'>".$erreur."</span>"; ?>
<br>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			if($_SESSION['rank'] >= 6)
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id, kl.level, kl.name, kl.desc FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kg.id = ? ORDER BY kg.valid desc, kl.level desc, kg.keyd asc');
			}
			else
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id, kl.level, kl.name, kl.desc FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kg.id = ? AND kl.level < 6 ORDER BY kg.valid desc, kl.level desc, kg.keyd asc');
			}
			$selectreq->execute(array($perso));
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
				<div class="link">
					<?php
					if($reqkey['valid'] == 1)
					{
					?>
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?= $reqkey['level']?>.png">
					<br>
					<span>
						<?= $desc?>
					</span>
					<?php
					}
					elseif($reqkey['valid'] == 0)
					{
					?>
					<span style="text-shadow: 2px 2px 2px #000000; color: black; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevelInconnu.png">
					<br>
					<?php
					if($_SESSION['rank'] >= 6)
					{
					?>
					<a href="index.php?p=sucesskey&perso=<?=$perso?>&key=<?=$reqkey['keyd']?>&valid=1">
						<span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
							[Valider la Clé]
						</span>
					</a>
					<a href="index.php?p=sucesskey&perso=<?=$perso?>&del=<?=$reqkey['keyd']?>">
						<span class="username-detail" style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">
							[Refuser la Clé]
						</span>
					</a>
					<?php
					}
					else
					{
					?>
					<span style="color: darkred">
						En attente de validation !
					</span>
					<?php
					}
					
					}
					?>
					
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<?php
}
elseif($_SESSION['rank'] >= 5)
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
?>

<span style="font-weight: bold;font-size: 25px">
	Clés
</span>
<br>
<br>Voici les Clés de <span style="color:<?= $color?>; <?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $userinfo['username']?></span> !
<br>Ici se trouve toutes les Clés qu'il a pu rentrer !
<br>
<br>
<?php
if($_SESSION['rank'] >= 5)
{
	?>
	<form method="POST">
	<input type="submit" name="return" value="Retour à liste des Clés obtenues">
	</form>
	<br>
	<br>
	<?php
}
?>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			if($_SESSION['rank'] >= 6)
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id, kl.name, kl.level, kl.desc FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kg.id = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc');
			}
			else
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id, kl.name, kl.level, kl.desc FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kl.level < 6 AND kg.id = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc');
			}
			$selectreq->execute(array($perso, '1'));
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
				<div class="link">
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?= $reqkey['level']?>.png">
					<br>
					<span>
						<?= $desc?>
					</span>
					
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<br>
<br>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			if($_SESSION['rank'] >= 6)
			{
			$selectreq2 = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id, kl.name, kl.level, kl.desc FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kg.id = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc');
			}
			else
			{
			$selectreq2 = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id, kl.name, kl.level, kl.desc FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kl.level < 6 AND kg.id = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc');
			}
			$selectreq2->execute(array($perso, '0'));
			
			while ($reqkey2 = $selectreq2->fetch())
			{
			
			$desc2 = preg_replace('#\n#', '<br>', $reqkey2['desc']);
			
			switch ($reqkey2['level'])
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
				<div class="link">
					<span style="text-shadow: 2px 2px 2px #000000; color: <?=$level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey2['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?=$reqkey2['level']?>.png">
					<br>
					<br>
					<span>
						<?= $desc2?>
					</span>
					<br>
					<br>
					<a href="index.php?p=sucesskey&perso=<?=$perso?>&key=<?=$reqkey2['keyd']?>&valid=1">
						<span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
							[Valider la Clé]
						</span>
					</a>
					<a href="index.php?p=sucesskey&perso=<?=$perso?>&del=<?=$reqkey2['keyd']?>">
						<span class="username-detail" style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">
							[Refuser la Clé]
						</span>
					</a>
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<?php
}
else
{
?>
<span style="color: red">
	Vous n'avez pas le droit d'être ici !
</span>
<?php
}

}
elseif(isset($_GET['kaff']) AND $_GET['kaff'] > 0)
{

$kaff = $_GET['kaff'];

if($_SESSION['rank'] >= 5)
{

if(isset($_POST['return']))
{
	header('Location: index.php?p=sucesskey');
}
?>
<span style="font-weight: bold;font-size: 25px">
	Clés
</span>
<br>
<br>Voici les Clés de <span style="color:<?= $color?>; <?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $userinfo['username']?></span> !
<br>Ici se trouve toutes les Clés qu'il a pu rentrer !
<br>
<br>
<?php
if($_SESSION['rank'] >= 5)
{
	?>
	<form method="POST">
	<input type="submit" name="return" value="Retour à liste des Clés obtenues">
	</form>
	<br>
	<br>
	<?php
}
?>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			if($_SESSION['rank'] >= 6)
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kg.keyd = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			}
			else
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kl.level < 6 AND kg.keyd = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			}
			$selectreq->execute(array($kaff, '1'));
			while ($reqkey = $selectreq->fetch())
			{
			
			$selectname = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$selectname->execute(array($reqkey['id']));
			$name = $selectname->fetch();
			
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
			
			if ($reqkey['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($reqkey['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($reqkey['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($reqkey['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($reqkey['pnj'] == 1)? "#55FFFF" : $color;
				$color = ($reqkey['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($reqkey['pnj'] == 2)? "#0200A6" : $color;
				$color = ($reqkey['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			?>
				<div class="link">
					<span style="text-shadow: 2px 2px 2px #000000; color: <?=$color?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['username']?>
					</span>
					<br>
					<br>
					<br>
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?= $reqkey['level']?>.png">
					<br>
					<span>
						<?= $desc?>
					</span>
					
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<br>
<br>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			
			if($_SESSION['rank'] >= 6)
			{
			$selectreq2 = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kg.keyd = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			}
			else
			{
			$selectreq2 = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kl.level < 6 AND kg.keyd = ? AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			}
			$selectreq2->execute(array($kaff, '0'));
			
			while ($reqkey2 = $selectreq2->fetch())
			{
			
			$selectname = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$selectname->execute(array($reqkey2['id']));
			$name = $selectname->fetch();
			
			if(!empty($_GET['del']))
			{
				$deleteget = $db->prepare('DELETE FROM key_get WHERE keyd = ? AND id = ? AND valid = ?');
				$deleteget->execute(array($_GET['del'], $_GET['player'], '0'));
				header('Location: index.php?p=sucesskey&kaff='.$kaff.'');
			}

			if(!empty($_GET['valid']))
			{
				$validget = $db->prepare('UPDATE key_get SET valid = ? WHERE id = ? AND keyd = ? AND valid = ?');
				$validget->execute(array($_GET['valid'], $_GET['player'], $kaff, '0'));
				
				$addvalid = $db->prepare("UPDATE member_list SET keycount = ? WHERE id = ?");
				$addvalid->execute(array($name['keycount'] + 1, $_GET['player']));
				header('Location: index.php?p=sucesskey&kaff='.$kaff.'');
			}
			
			$desc2 = preg_replace('#\n#', '<br>', $reqkey2['desc']);
			
			switch ($reqkey2['level'])
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
			
			if ($reqkey2['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($reqkey2['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($reqkey2['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey2['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($reqkey2['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey2['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($reqkey2['pnj'] == 1)? "#55FFFF" : $color;
				$color = ($reqkey2['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($reqkey2['pnj'] == 2)? "#0200A6" : $color;
				$color = ($reqkey2['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			?>
				<div class="link">
					<span style="text-shadow: 2px 2px 2px #000000; color: <?=$color?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey2['username']?>
					</span>
					<br>
					<br>
					<br>
					<span style="text-shadow: 2px 2px 2px #000000; color: <?=$level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey2['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?=$reqkey2['level']?>.png">
					<br>
					<br>
					<span>
						<?= $desc2?>
					</span>
					<br>
					<br>
					<a href="index.php?p=sucesskey&kaff=<?=$kaff?>&player=<?=$reqkey2['id']?>&valid=1">
						<span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
							[Valider la Clé]
						</span>
					</a>
					<a href="index.php?p=sucesskey&kaff=<?=$kaff?>&player=<?=$reqkey2['id']?>&del=<?=$kaff?>">
						<span class="username-detail" style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">
							[Refuser la Clé]
						</span>
					</a>
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<?php
}
else
{
?>
<span style="color: red">
	Vous n'avez pas le droit d'être ici !
</span>
<?php
}

}
elseif(!isset($_GET['perso']) AND !isset($_GET['kaff']))
{

if($_SESSION['rank'] >= 5)
{

if(isset($_POST['submitjaff']))
{
	$select2jaff = $db->prepare('SELECT * FROM member_list WHERE username = ?');
	$select2jaff->execute(array($_POST['jaff']));
	$jaff2 = $select2jaff->rowCount();

	if($jaff2 >= 1)
	{
		$selectjaff = $db->prepare('SELECT * FROM member_list WHERE username = ?');
		$selectjaff->execute(array($_POST['jaff']));
		$jaff = $selectjaff->fetch();
		
		header('Location: index.php?p=sucesskey&perso='.$jaff['id'].'');
	} else {
		$erreur = "Cet utilisateur n'existe pas !";
	}
}

if(isset($_POST['submitkaff']))
{
	$select2kaff = $db->prepare('SELECT * FROM key_list WHERE name = ?');
	$select2kaff->execute(array($_POST['kaff']));
	$kaff2 = $select2kaff->rowCount();
	
	if($kaff2 >= 1)
	{
		$selectkaff = $db->prepare('SELECT * FROM key_list WHERE name = ?');
		$selectkaff->execute(array($_POST['kaff']));
		$kaff = $selectkaff->fetch();
		
		header('Location: index.php?p=sucesskey&kaff='.$kaff['id'].'');
	} else {
		$erreur = "Cette clé n'existe pas !";
	}
}
?>
<span style="font-weight: bold;font-size: 25px">
	Clés
</span>
<br>
<br>Voici les Clés de tout le monde !
<br>Ici se trouve toutes les Clés que les joueurs ont pu rentrer !
<br>
<br>
<?php
if($_SESSION['rank'] >= 5)
{
	?>
	<form method="POST">
	Uniquement ce Joueur: <input type="text" name="jaff" placeholder="Prénom UNIQUEMENT !"> <input type="submit" name="submitjaff" value="Valider">
	<br>
	Uniquement cette clé: <input type="text" name="kaff" placeholder="Clé"> <input type="submit" name="submitkaff" value="Valider">
	</form>
	<br>
	<span style="color: red"><?=$erreur?></span>
	<br>
	<br>
	<?php
}
?>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			if($_SESSION['rank'] >= 6)
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			}
			else
			{
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kl.level < 6 AND kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			}
			$selectreq->execute(array('1'));
			while ($reqkey = $selectreq->fetch())
			{
			
			$selectname = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$selectname->execute(array($reqkey['id']));
			$name = $selectname->fetch();
			
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
			
			if ($reqkey['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($reqkey['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($reqkey['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($reqkey['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($reqkey['pnj'] == 1)? "#55FFFF" : $color;
				$color = ($reqkey['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($reqkey['pnj'] == 2)? "#0200A6" : $color;
				$color = ($reqkey['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			?>
				<div class="link">
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $color?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['username']?>
					</span>
					<br>
					<br>
					<br>
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?= $reqkey['level']?>.png">
					<br>
					<span>
						<?= $desc?>
					</span>
					
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<br>
<br>
<div class="nav" align="center">
	<ul>
		<li>
			<?php
			$selectreq = $db->prepare('SELECT kg.keyd, kg.id, kg.valid, kl.id AS kl_id, kl.level, kl.name, kl.desc, m.id AS m_id, m.username, m.rank, m.digni, m.actif, m.pnj FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd RIGHT JOIN member_list m ON kg.id = m.id WHERE kg.valid = ? ORDER BY kl.level desc, kg.keyd asc, m.rank desc, m.pnj desc, m.digni desc, m.actif desc, m.username asc');
			$selectreq->execute(array('0'));
			
			while ($reqkey = $selectreq->fetch())
			{
			
			$selectname = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$selectname->execute(array($reqkey['id']));
			$name = $selectname->fetch();
			
			if(!empty($_GET['del']))
			{
				$deleteget = $db->prepare('DELETE FROM key_get WHERE keyd = ? AND id = ? AND valid = ?');
				$deleteget->execute(array($_GET['del'], $_GET['player'], '0'));
				header('Location: index.php?p=sucesskey');
			}

			if(!empty($_GET['valid']))
			{
				$validget = $db->prepare('UPDATE key_get SET valid = ? WHERE id = ? AND keyd = ? AND valid = ?');
				$validget->execute(array($_GET['valid'], $_GET['player'], $_GET['key'], '0'));
				
				$addvalid = $db->prepare("UPDATE member_list SET keycount = ? WHERE id = ?");
				$addvalid->execute(array($name['keycount'] + 1, $_GET['player']));
				header('Location: index.php?p=sucesskey');
			}
			
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
			
			if ($reqkey['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($reqkey['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($reqkey['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($reqkey['actif'] == 1)? "#FF5555" : $color;
				$color = ($reqkey['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($reqkey['pnj'] == 1)? "#55FFFF" : $color;
				$color = ($reqkey['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($reqkey['pnj'] == 2)? "#0200A6" : $color;
				$color = ($reqkey['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			?>
				<div class="link">
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $color?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['username']?>
					</span>
					<br>
					<br>
					<br>
					<span style="text-shadow: 2px 2px 2px #000000; color: <?= $level?>; font-weight: bold; font-size: 1.1em;">
						<?= $reqkey['name']?>
					</span>
					<br>
					<br>
					<img width="100px" height="100px" src="pics/clemagie/CléLevel<?= $reqkey['level']?>.png">
					<br>
					<br>
					<span>
						<?= $desc?>
					</span>
					<br>
					<br>
					<a href="index.php?p=sucesskey&player=<?=$reqkey['id']?>&key=<?=$reqkey['keyd']?>&valid=1">
						<span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
							[Valider la Clé]
						</span>
					</a>
					<a href="index.php?p=sucesskey&player=<?=$reqkey['id']?>&del=<?=$reqkey['keyd']?>">
						<span class="username-detail" style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">
							[Refuser la Clé]
						</span>
					</a>
				</div>
			<?php
			}
			?>
		</li>
	</ul>
</div>
<?php
}
else
{
?>
<span style="color: red">
	Vous n'avez pas le droit d'être ici !
</span>
<?php
}

}

}
?>