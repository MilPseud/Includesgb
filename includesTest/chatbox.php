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
<div id="chat">
	Chatbox Alpha
</div>

<table>
	<tbody>
		<tr>
			<?php
			if($_SESSION['connected'] == true)
			{
			?>
				<form method="POST" action="#">
					<input type="text" placeholder="Tapez votre Message ici !" autocomplete="off" name="message">
					<input type="submit" value="Envoyer">
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
				$style = "<div>";
				$styleoff = "</div>";
			}
			else
			{
				$style = false;
				$styleoff = false;
			}
			if ($msg['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "dieu";
			}
			else
			{
			$stylebasic = "basic";
			$styledieu = false;
			}
			
			switch ($msg['rank'])
			{
				default : $color = "color_b"; break;
				case 1:  $color = "color_p"; $color = ($msg['actif'] == 1)? "color_p_a" : $color;
				$color = ($msg['digni'] == 3)? "color_p_dc" : $color; break;
				case 2: $color = "color_p"; $color = ($msg['actif'] == 1)? "color_p_a" : $color;
				$color = ($msg['digni'] == 3)? "color_p_dc" : $color; break;
				case 3: $color = "color_vip"; break;
				case 4: $color = "color_c"; $color = ($msg['pnj'] == 1)? "color_pnj" : $color;
				$color = ($msg['pnj'] == 3)? "color_c" : $color;
				$color = ($msg['digni'] == 2)? "color_dm" : $color; break;
				case 5: $color = "color_mj"; $color = ($msg['pnj'] == 2)? "color_god" : $color;
				$color = ($msg['digni'] == 1)? "color_vip" : $color; break;
				case 6: $color = "color_mjs"; break;
				case 7: $color = "color_other"; break;
			}
			$img = (file_exists("pics/MiniImage/user_".$msg['playerid'].".png"))? "<img src=\"pics/MiniImage/user_". $msg['playerid']. ".png\"> " : " ";
			?>
			<p>
			<?php
			if($_SESSION['rank'] >= 4)
			{
			?>
			<a href="index.php?p=<?$_GET['p']?>&perso=<?=$_GET['perso']?>&delmsg=<?=$msg['id']?>"><span>[X]</span></a>
			<?php
			}
			?>
				<?= $date_send," ", $rankmark ," ", $img, " " ?><span class="<?= $color ?> <?= $styledieu?> <?= $stylebasic?>"> <?= $msg['username']?></span> : <?= $style, $msg['message'], $styleoff ?>
			</p>
		<?php
		}
		?>
</div>

<?php
}
?>