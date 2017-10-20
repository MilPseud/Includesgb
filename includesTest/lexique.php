<?php function lexique()
{
	global $db, $_POST, $_GET, $_SESSION;

if ($_SESSION['rank'] >= 6 OR $_SESSION['id'] == 166)
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
			
if(!empty($_GET['del']))
{
	$deletelx = $db->prepare('DELETE FROM lexique WHERE id = ?');
	$deletelx->execute(array($_GET['del']));
	header('Location: index.php?p=lexique');
}
?>
<span style="font-weight: bold;font-size: 25px">
	Lexique
</span>
<br>
<br>
<span style="color: red; font-size: 15px">
Aide à la prononciation: 
</span>
<br>
-Le «h» s'entend fortement dans cette semble, il est soufflé avec la gorge.
<br>
-La combinaison «jh» se prononce «ch»
<br>
-La combinaison «kh» est un roulement arrière de la langue, il est plutôt guttural et soufflé
<br>
-Le «r» est simplement roulé
<br>
-La combinaison «ie» se prononce «eï»
<br>
-La combinaison «ee» se prononce comme un «i» long
<br>
<br>
<span style="color: red; font-size: 15px">
Note à moi même:
</span>
<br>
Une terminaison en «-an» désigne l'état fondamental, est utilisé à la fin de certains noms commun (l'homme ou la femme qui sont les états fondamentaux de l'humain), peut aussi être utilisé comme suffixe sur les adjectifs pour en appuyer la signification et renforcer l'éloge/le blâme.
<br>
Une terminaison en «-ar» désigne la fonction (père ou mère par exemple), ou l'action (se référer aux verbes).
<br>
Une terminaison en «-o» désigne une supériorité importante, quasiment divine, elle peut-être physique ou spirituelle. Il peut être utiliser en suffixe pour exalter un personnage et ses caractères (dans les mythes par exemples).
<br>
Une terminaison en «-on» désigne la personnification d'une force, d'un objet ou d'un concept (comme pour les noms des dieux).
<br>
Une terminaison en «-yn» désigne les Constellations dans le Ciel.
<br>
Les adjectifs se terminent en «-or», sauf cas particuliers (voir au-dessus).
<br>
La plupart des noms communs sans particularités se terminent en «-em».
<br>
<br>
<?php
$testlx = $db->query('SELECT * FROM lexique');
$getlx = $testlx->fetch();

if(!isset($_GET['sendmodif']))
{
	if($_SESSION['rank'] >= 5)
	{
		if(isset($_POST['addtrad']))
		{
			if(!empty($_POST['fr']) AND !empty($_POST['trad']))
			{
							$fr = htmlspecialchars($_POST['fr']);
							$trad = htmlspecialchars($_POST['trad']);
							$com = htmlspecialchars($_POST['com']);
							$insertkey = $db->prepare('INSERT INTO lexique VALUES(?, ?, ?, ?)');
							$insertkey->execute(array(NULL, $trad, $fr, $com));
							$erreur = "Traduction rajoutée !";
							header('Location: index.php?p='.$_GET['p'].'&trad='.$_GET['trad'].'');
			} else {
				$erreur = "Il faut les champs de Commun et de Traduction obligatoirement... !";
			}
		}
	}
}
elseif(isset($_GET['sendmodif']))
{
	if($_SESSION['rank'] >= 5)
	{
		if(isset($_POST['modiftrad']))
		{
			if(!empty($_POST['fr']) AND !empty($_POST['trad']))
			{
							$fr = htmlspecialchars($_POST['fr']);
							$trad = htmlspecialchars($_POST['trad']);
							$com = htmlspecialchars($_POST['com']);
							$insertkey = $db->prepare('UPDATE lexique SET trad = ?, fr = ?, com = ? WHERE id = ?');
							$insertkey->execute(array($trad, $fr, $com, $_GET['modif']));
							$erreur = "Traduction modifiée !";
							header('Location: index.php?p='.$_GET['p'].'&trad='.$_GET['trad'].'');
			} else {
				$erreur = "Il faut les champs de Commun et de Traduction obligatoirement... !";
			}
		}
	}
}

if(isset($_GET['modif']))
{
	$modiflx = $db->prepare('SELECT * FROM lexique WHERE id = ?');
	$modiflx->execute(array($_GET['modif']));
	$modif = $modiflx->fetch();
	
}
?>

<center><span style="font-weight: bold;font-size: 17px">Ajout au Lexique</span></center>
<br>
<?php
if(!isset($_GET['modif']))
{
?>
<div class="nav">
	<ul>
		<li>
			Addition
			<div class="link">
				<form method="POST" action="index.php?p=lexique&trad=<?=$_GET['trad']?>">
					<table cellspacing="5" cellpadding="5" align="center">
						<tbody>
							<tr>
								<td rowspan="1">
									Commun
									<br>
									<input type="text" name="fr" placeholder="Version Française">
								</td>
								<td rowspan="2">
									Commentaire
									<br>
									<input type="text" name="com" placeholder="Commentaire">
								</td>
								<td rowspan="2">
									<input type="submit" name="addtrad" value="Valider !">
								</td>
							</tr>
							<tr>
								<td rowspan="1">
									Traduction
									<br>
									<input type="text" name="trad" placeholder="Version Traduite">
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</li>
	</ul>
</div>
<?php
}
elseif(isset($_GET['modif']))
{
?>
<div class="nav">
	<ul>
		<li>
			Modification
			<div class="link">
				<form method="POST" action="index.php?p=lexique&trad=<?=$_GET['trad']?>&modif=<?=$_GET['modif']?>&sendmodif=1">
					<table cellspacing="5" cellpadding="5" align="center">
						<tbody>
							<tr>
								<td rowspan="1">
									Commun
									<br>
									<input type="text" name="fr" placeholder="Version Française" value="<?=$modif['fr']?>">
								</td>
								<td rowspan="2">
									Commentaire
									<br>
									<input type="text" name="com" placeholder="Commentaire" value="<?=$modif['com']?>">
								</td>
								<td rowspan="2">
									<input type="submit" name="modiftrad" value="Valider !">
								</td>
							</tr>
							<tr>
								<td rowspan="1">
									Traduction
									<br>
									<input type="text" name="trad" placeholder="Version Traduite" value="<?=$modif['trad']?>">
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</li>
	</ul>
</div>
<?php
}
?>
<br>
<span style="color: red"><?= $erreur?></span>
<br>
<br>

<?php

if(isset($_GET['trad']) AND $_GET['trad'] == 0)
{
?>

<center>
<form method="POST" action="index.php?p=lexique&trad=1">
	<input type="submit" value="Traduction->Commun" name="TradFR">
</form>
</center>
<br>
<table style="border-radius: 10px; border-width: 2px; border-style: solid; border-collapse: collapse; width:100%;" cellspacing="5" cellpadding="5" align="center">
	<tbody>
			<tr style="border-collapse: collapse;border:2px solid #FFA500;background-color:#FFD700;">
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:17%;" valign="center" align="center">
				Commun
			</th>
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:37%;" valign="center" align="center">
				Traduction
			</th>
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:17%" valign="center" align="center">
				Commentaire
			</th>
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:3%" valign="center" align="center">
				Action
			</th>
			</tr>
			<?php
			$selectlx = $db->query('SELECT * FROM lexique ORDER BY fr asc');
			while ($reqlx = $selectlx->fetch())
			{
			
			$com2 = preg_replace('#\n#', '<br>', $reqlx['com']);
			?>
			<tr style="border:2px solid #808080;">
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?= $reqlx['fr']?>
				</span>
			</td>
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?= $reqlx['trad']?>
				</span>
			</td>
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?= $com2?>
				</span>
			</td>
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Suprimer la Traduction" href="index.php?p=lexique&trad=<?=$_GET['trad']?>&del=<?= $reqlx['id']?>"><span style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">[X]</span></a><?php } ?>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Modifier la Traduction" href="index.php?p=lexique&trad=<?=$_GET['trad']?>&modif=<?= $reqlx['id']?>"><span style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[M]</span></a><?php } ?>
				</span>
			</td>
			</tr>
			<?php
			}
			?>
	</tbody>
</table>

<?php
}
elseif(isset($_GET['trad']) AND $_GET['trad'] == 1)
{
?>

<center>
<form method="POST" action="index.php?p=lexique&trad=0">
	<input type="submit" value="Commun->Traduction" name="FRTrad">
</form>
</center>
<br>
<table style="border-radius: 10px; border-width: 2px; border-style: solid; border-collapse: collapse; width:100%;" cellspacing="5" cellpadding="5" align="center">
	<tbody>
			<tr style="border-collapse: collapse;border:2px solid #FFA500;background-color:#FFD700;">
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:18%;" valign="center" align="center">
				Traduction
			</th>
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:38%;" valign="center" align="center">
				Commun
			</th>
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:18%" valign="center" align="center">
				Commentaire
			</th>
			<th style="border-collapse: collapse;background-color:#FFD700;height:30px;width:3%" valign="center" align="center">
				Action
			</th>
			</tr>
			<?php
			$selectlx = $db->query('SELECT * FROM lexique ORDER BY trad asc');
			while ($reqlx = $selectlx->fetch())
			{
			
			$com2 = preg_replace('#\n#', '<br>', $reqlx['com']);
			?>
			<tr style="border:2px solid #808080;">
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?= $reqlx['trad']?>
				</span>
			</td>
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?= $reqlx['fr']?>
				</span>
			</td>
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?= $com2?>
				</span>
			</td>
			<td align="center" style="background-color:#C0C0C0;">
				<span>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Suprimer la Traduction" href="index.php?p=lexique&trad=<?=$_GET['trad']?>&del=<?= $reqlx['id']?>"><span style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">[X]</span></a><?php } ?>
					<?php if($_SESSION['rank'] >= 5) { ?> <a title="Modifier la Traduction" href="index.php?p=lexique&trad=<?=$_GET['trad']?>&modif=<?= $reqlx['id']?>"><span style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[M]</span></a><?php } ?>
				</span>
			</td>
			</tr>
			<?php
			}
			?>
	</tbody>
</table>

<?php
}

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
?>