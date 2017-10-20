<?php function competencelist()
{ global $db, $_POST, $_SESSION, $_GET;

if(!empty($_GET['del']))
{
	$deletecomp = $db->prepare('DELETE FROM competence_get WHERE comp = ?');
	$deletecomp->execute(array($_GET['del']));
	
	$deleteget = $db->prepare('DELETE FROM competence_list WHERE id = ?');
	$deleteget->execute(array($_GET['del']));
	header('Location: index.php?p=competencelist');
}

if(!empty($_GET['met']))
{
	$metcomp = $db->prepare('UPDATE competence_list SET metier = ? WHERE id = ?');
	$metcomp->execute(array($_GET['met'], $_GET['comp']));
	header('Location: index.php?p=competencelist');
}

$testcomp = $db->query('SELECT * FROM competence_list');
$getcomp = $testcomp->fetch();

if($_SESSION['rank'] >= 4)
{
	if(isset($_POST['addcomp']))
	{
		if(!empty($_POST['name']) AND !empty($_POST['desc']) AND !empty($_POST['metier']))
		{
			if(strlen($_POST['name']) <= 225)
			{
						$name = htmlspecialchars($_POST['name']);
						$desc = htmlspecialchars($_POST['desc']);
						$metier = htmlspecialchars($_POST['metier']);
						$insertkey = $db->prepare('INSERT INTO competence_list VALUES(?, ?, ?, ?)');
						$insertkey->execute(array(NULL, $name, $desc, $metier));
						$erreur = "Métier/Compétence rajoutée !";
						header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
			} else {
				$erreur = "Le Métier/La Compétence est trop long/longue... !";
			}
		} else {
			$erreur = "Il faut remplir tout les champs... !";
		}
	}
}

$compsel = 1;
?>

<?php
if($_SESSION['rank'] >= 4)
{
?>
<form method="POST" action="index.php?p=competencelist">
	<table cellspacing="5" cellpadding="5" align="center" style="border-radius: 100px;padding: 20px; border: 3px #8B4513 solid; background-color: #A0522D;">
		<tbody>
			<tr>
				<td rowspan="1">
					Métier/Compétence
					<br>
					<input type="text" name="name" placeholder="Métier/Compétence Exacte !">
				</td>
				<td rowspan="2">
					Description
					<br>
					<textarea style="height: 200px; width: 400px" type="text" name="desc" placeholder="Description de la Clé"></textarea>
				</td>
				<td rowspan="2">
					<input type="submit" name="addcomp" value="Valider !">
				</td>
			</tr>
			<tr>
				<td rowspan="1">
					Métier ou Compétence ?
					<br>
					<input type="text" name="metier" placeholder="1 = Compétence, 2 = Métier">
				</td>
			</tr>
		</tbody>
	</table>
</form>
<br>
<span style="color: red"><?= $erreur?></span>
<br>
<br>
<?php
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
				$compreq = $db->prepare('SELECT * FROM competence_list WHERE metier = ? ORDER BY name asc');
				$compreq->execute(array($metierdb));
				?>
				<tr style="border:2px solid #FFA500;background-color:#FFD700;">
					<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:30%;" valign="center" align="center">
						<?=$name?>
					</th>
					<th style="border:2px solid #FFA500;border-radius: 10px;background-color:#FFD700;height:30px;width:70%;" valign="center" align="center">
						Description
					</th>
				</tr>
				<?php
				while ($compinfo = $compreq->fetch())
				{
				?>
					<tr>
						<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
							<span>
								<?= $compinfo['name']?> <?php if($_SESSION['rank'] >= 4) { ?><a title="Supprimer <?php if($compinfo['metier'] == 1) { ?>cette compétence<?php } elseif ($compinfo['metier'] == 2) { ?>ce métier<?php } ?>" href="index.php?p=competencelist&del=<?= $compinfo['id']?>"><span class="username-detail" style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">[X]</span></a>
								<?php if($compinfo['metier'] == 1) { ?> <a title="Transformer en Métier" href="index.php?p=competencelist&comp=<?= $compinfo['id']?>&met=2"><span class="username-detail" style="font-weight: bold; color: #5555FF" onmouseout="this.style.color='#5555FF'" onmouseover="this.style.color='white'">[M]</span></a>
								<?php } elseif($compinfo['metier'] == 2) { ?> <a title="Transformer en Compétence" href="index.php?p=competencelist&comp=<?= $compinfo['id']?>&met=1"><span class="username-detail" style="font-weight: bold; color: #FFFF55" onmouseout="this.style.color='#FFFF55'" onmouseover="this.style.color='white'">[C]</span></a><?php } } ?>
							</span>
						</td>
						<td align="center" style="border:2px solid #808080;background-color:#C0C0C0;border-radius: 10px;">
							<span>
								<?= $compinfo['desc']?>
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
?>