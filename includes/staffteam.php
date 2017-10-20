<?php function staff()
{	global $db, $_POST, $_SESSION, $_GET;
?>

<?php
$staffsel = 5;

while($staffsel >= 0)
{
	switch($staffsel)
	{
		default : $name = "???"; break;
		case 5: $name = "Maîtres du Jeu Superviseurs"; $rankdb = 6; $dignidb = 0; break;
		case 4: $name = "Maîtres du Jeu"; $rankdb = 5; $dignidb = 0; break;
		case 3: $name = "Cadres"; $rankdb = 4; $dignidb = 0; break;
		case 2: $name = "MJSs Dignitaire"; $rankdb = 5; $dignidb = 1; break;
		case 1: $name = "MJs Dignitaire"; $rankdb = 4; $dignidb = 2; break;
		case 0: $name = "Cadres Dignitaire"; $rankdb = 1; $dignidb = 3; break;
	}
	$reqstaff = $db->prepare('SELECT m.id, m.username, m.rank, m.digni, m.vanish, s.idplayer, s.citation, s.age, s.ville, s.surnom FROM staff_team s RIGHT JOIN member_list m ON s.idplayer = m.id WHERE m.rank = ? AND m.digni = ? AND m.pnj = 0 ORDER BY m.digni asc, m.rank desc, m.username asc');
	$reqstaff->execute(array($rankdb, $dignidb));
?>
	<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
		<tbody>
			<tr style="border:2px solid #FFA500;background-color:#FFD700;">
				<th colspan="3" style="background-color:#FFD700;height:30px;width:100%;" align="center"><?= $name?></th>
			</tr>
			<?php
			while($staffinfo = $reqstaff->fetch())
			{
				switch ($staffinfo['rank'])
				{
					default : $color = "#555550"; break;
					case 1:  $color = "#00AA00"; $color = ($staffinfo['actif'] == 1)? "#FF5555" : $color;
					$color = ($staffinfo['digni'] == 3)? "#5555FF" : $color; break;
					case 2: $color = "#55FF55"; $color = ($staffinfo['actif'] == 1)? "#FF5555" : $color;
					$color = ($staffinfo['digni'] == 3)? "#5555FF" : $color; break;
					case 3: $color = "#FF55FF"; break;
					case 4: $color = "#00AAAA"; $color = ($staffinfo['pnj'] == 1)? "#AAAAAA" : $color;
					$color = ($staffinfo['pnj'] == 3)? "#55FFFF" : $color;
					$color = ($staffinfo['digni'] == 2)? "#FFFF55" : $color; break;
					case 5: $color = "#FFAA00"; $color = ($staffinfo['pnj'] == 2)? "#0200A6" : $color;
					$color = ($staffinfo['digni'] == 1)? "#AA00AA" : $color; break;
					case 6: $color = "#AA0000"; break;
					case 7: $color = "#000000"; break;
				}
				$citation = preg_replace('#\n#', '<br>', $staffinfo['citation']);
				?>
				<tr style="border:2px solid #8B4513;background-color:#A0522D;">
					<td valign="middle" align="center">
						<?php
						if(file_exists('pics/staff/Staff'.$staffinfo['id'].'.png'))
						{
							?>
							<img valign="middle" width="150px" height="150px" src="pics/staff/Staff<?=$staffinfo['id']?>.png">
							<?php
						}
						else
						{
							?>
							<img valign="middle" width="150px" height="150px" src="pics/staff/StaffEmpty.png">
							<?php
						}
						?>
					</td>
					<td valign="middle" align="center">
						<span class="username-detail" style="color: <?=$color?>"><?= $staffinfo['username']?></span>
						<?php
						if($_SESSION['id'] == $staffinfo['id'] OR $_SESSION['rank'] >= 6)
						{
						?>
						<a href="index.php?p=staffedit&perso=<?= $staffinfo['id']?>"><span class="username-detail" style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[EDITER]</span></a>
						<?php
						}
						?>
						<?php
						if(!empty($staffinfo['age']))
						{
							?>
							<br>
							<?= $staffinfo['age']?>
							<?php
						}
						if(!empty($staffinfo['ville']))
						{
							?>
							<br>
							<?= $staffinfo['ville']?>
							<?php
						}
						if(!empty($staffinfo['surnom']))
						{
							?>
							<br>
							"<?= $staffinfo['surnom']?>"
							<?php
						}
						?>
					</td>
					<td valign="middle" align="center">
						<?php
						if(!empty($staffinfo['citation']))
						{
							?>
							"<?= $citation?>"
							<?php
						}
						else
						{
							?>
							[Ce Membre du Staff n'a pas entré de Citation]
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
	<br>
	<?php
	$staffsel --;
}
?>

<?php
}
?>