<?php function chatbox()
{
	global $db, $_SESSION, $_POST, $_GET;
?>

<?php
if(isset($_POST['message']) AND !empty($_POST['message']))
{
	$message = htmlspecialchars($_POST['message']);
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array($message, $_SESSION['id']));
	header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
}

if(isset($_GET['delmsg']))
{
	$delmsg = $db->prepare('DELETE FROM Chatbox WHERE id = ?');
	$delmsg->execute(array($_GET['delmsg']));
	header('Location: index.php?p='.$_GET['p'].'&perso='.$_GET['perso'].'');
}
?>
<span style="font-weight: bold;font-size: 25px;">
	Chatbox Alpha
</span>
<br>
<br>
<br>

<table>
	<tbody>
		<tr>
			<?php
			if($_SESSION['connected'] == true)
			{
			?>
				<form method="POST" action="#">
					<input type="text" placeholder="Tapez votre Message ici !" autocomplete="off" name="message">
					<input type="submit" value="Envoyer" style="margin-left: 5px;">
				</form>
			<?php
			}
			?>
		</tr>
	</tbody>
</table>

<div class="chatbox" id="cb">
		<?php
		$allmsg = $db->query('SELECT c.id, c.playerid, c.message, c.date_send, m.id AS m_id, m.username, m.rank, m.digni, m.pnj, m.actif FROM Chatbox c
		RIGHT JOIN member_list m
		ON c.playerid = m.id
		ORDER BY c.id DESC LIMIT 0, 15');
		while($msg = $allmsg->fetch())
		{
			if ($msg['rank'] > 4)
			{
				$rankmark = "--";
			}
			elseif ($msg['rank'] == 4)
			{
				$rankmark = "-";
			}	
			else
			{
				$rankmark = false;
			}
			$date_send = preg_replace('#^.{11}(.{2}):(.{2}):.{2}$#', '[$1:$2] ', $msg['date_send']);
			if ($msg['rank'] > 4)
			{
				$style = "<span style='font-weight: bold;'>";
				$styleoff = "</span>";
			}
			else
			{
				$style = false;
				$styleoff = false;
			}
			if ($msg['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($msg['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($msg['actif'] == 1)? "#FF5555" : $color;
				$color = ($msg['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($msg['actif'] == 1)? "#FF5555" : $color;
				$color = ($msg['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($msg['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($msg['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($msg['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($msg['pnj'] == 2)? "#0200A6" : $color;
				$color = ($msg['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			$img = (file_exists("pics/MiniImage/user_".$msg['playerid'].".png"))? "<img valign=\"center\" width=\"32px\" src=\"pics/MiniImage/user_". $msg['playerid']. ".png\"> " : " ";
			?>
			<p style="margin:0;">
			<?php
			if($_SESSION['rank'] >= 4)
			{
			?>
			<a href="index.php?p=<?$_GET['p']?>&perso=<?=$_GET['perso']?>&delmsg=<?=$msg['id']?>"><span style="font-weight: bold; color: red" onmouseout="this.style.color='red'" onmouseover="this.style.color='white'">[X]</span></a>
			<?php
			}
			?>
				<?= $date_send," ", $rankmark ," ", $img, " " ?><span style="color:<?= $color?>; <?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $msg['username']?></span> : <?= $style, $msg['message'], $styleoff ?>
			</p>
		<?php
		}
		?>
</div>

<?php
}
?>