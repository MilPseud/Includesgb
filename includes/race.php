<?php function race()
{
	global $db, $_SESSION, $_POST, $_GET;
?>

<center><h1>Les Races</h1>
<br>
<h3>Voici les Races de GaaranStröm !</h3></center>

<form method="POST">
<table align="center" style="text-align: center;" cellspacing="5" style="font-weight: bold;">
	<tbody>
		<tr>
			<td>
				<a href="index.php?p=race&race=1">Humain
				<br>
				<?php
				if($_GET['race'] == 1)
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/Humain.png">
				<?php
				}
				else
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/HumainBlink.png" onmouseover="this.src='pics/Grandrace/Humain.png';" onmouseout="this.src='pics/Grandrace/HumainBlink.png';">
				<?php
				}
				?>
				</a>
			</td>
			<td>
				<a href="index.php?p=race&race=2">Nain
				<br>
				<?php
				if($_GET['race'] == 2)
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/Nain.png">
				<?php
				}
				else
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/NainBlink.png" onmouseover="this.src='pics/Grandrace/Nain.png';" onmouseout="this.src='pics/Grandrace/NainBlink.png';">
				<?php
				}
				?>
				</a>
			</td>
			<td>
				<a href="index.php?p=race&race=3">Anthropomorphe
				<br>
				<?php
				if($_GET['race'] == 3)
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/Anthropomorphe.png">
				<?php
				}
				else
				{
				?>
				<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/AnthropomorpheBlink.png" onmouseover="this.src='pics/Grandrace/Anthropomorphe.png';" onmouseout="this.src='pics/Grandrace/AnthropomorpheBlink.png';">
				<?php
				}
				?>
				</a>
			</td>
			<td>
				<a href="index.php?p=race&race=4">Elfe
				<br>
				<?php
				if($_GET['race'] == 4)
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/Elfe.png">
				<?php
				}
				else
				{
				?>
				<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/ElfeBlink.png" onmouseover="this.src='pics/Grandrace/Elfe.png';" onmouseout="this.src='pics/Grandrace/ElfeBlink.png';">
				<?php
				}
				?>
				</a>
			</td>
			<td>
				<a href="index.php?p=race&race=5">Gobelin
				<br>
				<?php
				if($_GET['race'] == 5)
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/Gobelin.png">
				<?php
				}
				else
				{
				?>
				<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/GobelinBlink.png" onmouseover="this.src='pics/Grandrace/Gobelin.png';" onmouseout="this.src='pics/Grandrace/GobelinBlink.png';">
				<?php
				}
				?>
				</a>
			</td>
		</tr>
		<tr>
			<?php
			if($_SESSION['rank'] >= 4)
			{
				?>
				<td>
					<a href="index.php?p=race&race=6">Entité
					<br>
					<?php
					if($_GET['race'] == 6)
					{
					?>
					<img width="100px" align="center" src="pics/Grandrace/Entité.png">
					<?php
					}
					else
					{
					?>
					<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/EntitéBlink.png" onmouseover="this.src='pics/Grandrace/Entité.png';" onmouseout="this.src='pics/Grandrace/EntitéBlink.png';">
					<?php
					}
					?>
					</a>
				</td>
				<?php
			}
			if($_SESSION['rank'] >= 5)
			{
				?>
				<td>
					<a href="index.php?p=race&race=7">Être d'Artéfact
					<br>
					<?php
					if($_GET['race'] == 7)
					{
					?>
					<img width="100px" align="center" src="pics/Grandrace/Être d'Artéfact.png">
					<?php
					}
					else
					{
					?>
					<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/Être d'ArtéfactBlink.png" onmouseover="this.src='pics/Grandrace/Être d'Artéfact.png';" onmouseout="this.src='pics/Grandrace/Être d'ArtéfactBlink.png';">
					<?php
					}
					?>
					</a>
				</td>
				<td>
					<a href="index.php?p=race&race=8">Bête Divine
					<br>
					<?php
					if($_GET['race'] == 8)
					{
					?>
					<img width="100px" align="center" src="pics/Grandrace/Bête Divine.png">
					<?php
					}
					else
					{
					?>
					<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/Bête DivineBlink.png" onmouseover="this.src='pics/Grandrace/Bête Divine.png';" onmouseout="this.src='pics/Grandrace/Bête DivineBlink.png';">
					<?php
					}
					?>
					</a>
				</td>
				<td>
					<a href="index.php?p=race&race=9">Dieu
					<br>
					<?php
					if($_GET['race'] == 9)
					{
					?>
					<img width="100px" align="center" src="pics/Grandrace/Dieu.png">
					<?php
					}
					else
					{
					?>
					<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/DieuBlink.png" onmouseover="this.src='pics/Grandrace/Dieu.png';" onmouseout="this.src='pics/Grandrace/DieuBlink.png';">
					<?php
					}
					?>
					</a>
				</td>
				<td>
				<?php
			}
			?>
		</tr>
		<tr>
			<td colspan="2">
				<a href="index.php?p=race&race=10">Spéciale
				<br>
				<?php
				if($_GET['race'] == 10)
				{
				?>
				<img width="100px" align="center" src="pics/Grandrace/Spéciale.png">
				<?php
				}
				else
				{
				?>
				<img style="top-padding: 100px;" width="100px" align="center" src="pics/Grandrace/SpécialeBlink.png" onmouseover="this.src='pics/Grandrace/Spéciale.png';" onmouseout="this.src='pics/Grandrace/SpécialeBlink.png';">
				<?php
				}
				?>
				</a>
			</td>
			<td colspan="1">
			</td>
			<td colspan="2">
				<a href="index.php?p=race&race=11">Inconnue
			</td>
		</tr>
	</tbody>
</table>
</form>
<?php
if(isset($_GET['race']) AND $_GET['race'] > 0)
{

   $race = intval($_GET['race']);
   $reqrace = $db->prepare('SELECT * FROM race_description WHERE id = ?');
   $reqrace->execute(array($race)); 
   $raceinfo = $reqrace->fetch();
   
   $name = preg_replace('#\n#', '<br>', $raceinfo['name']);
   $desc = preg_replace('#\n#', '<br>', $raceinfo['paragraph']);
}
?>
<p style="text-align: center;font-weight: bold;font-size: 20px">
	<?= $name?>
</p>
<p style="text-align: center;">
	<?= $desc?>
</p>

<?php
}
?>