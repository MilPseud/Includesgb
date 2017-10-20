<?php function news()
{
	global $db, $_SESSION, $_POST, $_GET;
?>

<?php
if(isset($_POST['news']) AND !empty($_POST['news']))
{
	$new = htmlspecialchars($_POST['news']);
	$insertnew = $db->prepare('INSERT INTO news VALUES(?, ?, ?)');
	$insertnew->execute(array(NULL, $_SESSION['id'], $new));
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold; color: #00AAAA">'.$new.'</span>', 22));
	header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
}
?>


<div class="chatbox">
	<span style="font-weight: bold;font-size: 17px">Nouveautés</span>
	<br>
	<br>
	<?php
	$allnews = $db->query('SELECT n.id, n.idplayer, n.news, m.id AS m_id, m.username, m.rank FROM news n RIGHT JOIN member_list m ON n.idplayer = m.id ORDER BY n.id DESC LIMIT 0, 3');
	while($news = $allnews->fetch())
	{
	
					switch ($news['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($news['actif'] == 1)? "#FF5555" : $color;
				$color = ($news['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($news['actif'] == 1)? "#FF5555" : $color;
				$color = ($news['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($news['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($news['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($news['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($news['pnj'] == 2)? "#0200A6" : $color;
				$color = ($news['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			
			if($news['pnj'] == 2)
			{
			$styledieu = "text-shadow: 2px 2px 2px #FFFFFF;";	
			$stylebasic = false;
			}
			else
			{
			$styledieu = false;	
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			}
					
	?>
		<table>
			<tbody>
				<tr>
					<?php
				  if(file_exists("pics/MiniImage/user_".$news['idplayer'].".png"))
				  {
				  ?>
					<img width="32px" src="pics/MiniImage/user_<?= $news['idplayer']?>.png">
				  <?php
				  }
				  ?> <span class="username-detail" style="color: <?= $color?>;<?= $stylebasic?><?=$styledieu?>">
					<? echo $news['username']; ?>
					</span> : <? echo $news['news']; ?>
				</tr>
			</tbody>
		</table>
	<?php
	}
	?>
</div>
<?php
if($_SESSION['rank'] >= 5)
{
?>
<br>
	<table>
		<tbody>
			<tr>
				<form method="POST" action="">
					<input type="text" placeholder="Tapez vos News ici !" name="news">
					<input type="submit" value="Envoyer" style="margin-left: 5px;">
				</form>
			</tr>
		</tbody>
	</table>
<?php
}
?>
<?php
if($_SESSION['rank'] >= 1)
{
?>
<br>
<div class="chatbox">
	<span style="font-weight: bold;font-size: 17px">Gradations</span>
	<br>
	<br>
	<?php
	$dab = $db->query('SELECT gh.order, gh.idgrada, gh.text, gh.rank, gh.symbol, m.id, m.username FROM grada_history gh RIGHT JOIN member_list m ON gh.idgrada = m.id ORDER BY gh.order desc LIMIT 0, 5');
	while ($dab2 = $dab->fetch())
	{
	?>
	<table>
	<tbody>
	<tr>
	<?= $dab2['text']?>
	</tr>
	</tbody>
	</table>
	<?php
	}
	?>
</div>
<?php
}
?>

<div class="chatbox">
	<?php
	$anniv = $db->query('SELECT *
					FROM member_list
					WHERE ADDDATE(registration_date, INTERVAL 12 MONTH) < NOW() AND ADDDATE(registration_date, INTERVAL 13 MONTH) > NOW() AND vanish = 0
					ORDER BY registration_date desc, username asc');
	?>
	<span style="font-weight: bold;font-size: 17px">Bon Gaaranniversaire à eux !</span>
	<br>(De l'an pile à 1 mois plus tard):
	<br>
	<br>
				<?php
					while ($month = $anniv->fetch())
					{
						$rankanniv = $month['rank'];
						$rankanniv = ($month['actif'] == 1)? "A" : $rankanniv;
						$rankanniv = ($month['actif'] == 1)? "A" : $rankanniv;
						$rankanniv = ($month['digni'] == 1)? "D1" : $rankanniv;
						$rankanniv = ($month['digni'] == 2)? "D2" : $rankanniv;
						$rankanniv = ($month['digni'] == 3)? "D3" : $rankanniv;
						$rankanniv = ($month['pnj'] == 1)? "PNJ" : $rankanniv;
						$rankanniv = ($month['pnj'] == 3)? "E" : $rankanniv;
						$rankanniv = ($month['pnj'] == 2)? "DIEU" : $rankanniv;
						$rankanniv = ($month['desert'] == 1)? "DEL" : $rankanniv;
						$rankanniv = ($month['ban'] == 1)? "BAN" : $rankanniv;
						
						if ($month['pnj'] == 2)
						{
						$stylebasic = false;
						$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
						}
						else
						{
						$stylebasic = "text-shadow: 2px 2px 2px #000000;";
						$styledieu = false;
						}
						
						switch ($month['rank'])
						{
							default : $color = "#555550"; break;
							case 1:  $color = "#00AA00"; $color = ($month['actif'] == 1)? "#FF5555" : $color;
							$color = ($month['digni'] == 3)? "#5555FF" : $color; break;
							case 2: $color = "#55FF55"; $color = ($month['actif'] == 1)? "#FF5555" : $color;
							$color = ($month['digni'] == 3)? "#5555FF" : $color; break;
							case 3: $color = "#FF55FF"; break;
							case 4: $color = "#00AAAA"; $color = ($month['pnj'] == 1)? "#AAAAAA" : $color;
							$color = ($month['pnj'] == 3)? "#55FFFF" : $color;
							$color = ($month['digni'] == 2)? "#FFFF55" : $color; break;
							case 5: $color = "#FFAA00"; $color = ($month['pnj'] == 2)? "#0200A6" : $color;
							$color = ($month['digni'] == 1)? "#AA00AA" : $color; break;
							case 6: $color = "#AA0000"; break;
							case 7: $color = "#000000"; break;
						}
					?>
					<a onmouseout="this.style.color='<?=$color?>'" onmouseover="this.style.color='white'" class="username-detail" style="color: <?=$color?>;<?=$stylebasic?><?=$styledieu?>" href="index.php?p=profile&perso=<?= $month['id']?>" title="<?= $month['pseudo']?>"><img valign="center" src="pics/rank/Grade<?= $rankanniv?>.png" alt="" width="20px"> <?= $month['username']?> </a>
					<?php
					}
	?>
	<?php
	?>
</div>

<?php
}
?>