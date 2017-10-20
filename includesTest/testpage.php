<?php function testpage()
{
	global $db;
	?>
		
	<h2>Chatbox Alpha</h2>

	<p style="color: darkred;font-weight: bold;">
	Ne réactualisez pas la Page pour éviter le dédoublement de Messages !
	<br>
	Privilégiez le Bouton d'Actualisation de la Chat Box !
	</p>

	<table>
		<tbody>
			<tr>
				<?php
				if ($_SESSION['connected'])
				{
					?>
					<form method="POST" action="#">
						<input type="text" placeholder="Tapez votre Message ici !" autocomplete="off" name="message">
						<input type="submit" value="Envoyer" style="margin-left: 5px;">
					</form>
					<?php
				}
				?>
				<form method="POST" action="#">
					<input type="submit" name="reload" value="Actualiser" style="margin-left: 5px;">
				</form>
			</tr>
		</tbody>
	</table>

	<div class="chatbox">
		<?php
		$allmsg = $db->query('SELECT c.id, c.playerid, c.message, c.date_send, m.id AS m_id, m.username, m.rank AS rank, m.digni, m.pnj, m.actif FROM Chatbox c
		RIGHT JOIN member_list m
		ON c.playerid = m.id
		ORDER BY c.id DESC LIMIT 0, 15');
		$israinbow = 0;
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
			switch ($msg['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($msg['actif'] == 1)? "#FF5555" : $color; break;
				$color = ($msg['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($msg['digni'] == 3)? "#5555FF" : $color; break; 
				$color = ($msg['actif'] == 1)? "#FF5555" : $color; break; 
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($msg['pnj'] == 1)? "#55FFFF" : $color; break;
				$color = ($msg['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($msg['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			$img = (file_exists("pics/MiniImage/user_".$msg['playerid'].".png"))? "<img valign=\"center\" width=\"20px\" src=\"pics/MiniImage/user_". $msg['playerid']. ".png\"> " : " ";
			
			$rainbow = ($msg['playerid'] == 25)? "id='rainbow'": " ";
			if ($msg['playerid'] == 25) { $israinbow ++ ; }
			?>
			<p style="margin:0;">
				<?= $date_send," ", $rankmark ," ", $img, " " ?><span style="color:<?= $color?>;" <?= $rainbow ?>class="username-detail"><?= $msg['username']?></span> : <?= $style, $msg['message'], $styleoff ?>
			</p>
		<?php
		}
		?>
	</div>
	<?php
	
	if ($israinbow > 0)
	{
	?>
		<script>
		var intervalId = null;
		var colorId = 0;
		intervalId = setInterval(rainbow, 1000);
		function rainbow()
		{
			switch ($colorId)
			{
				default : nextColor = "#555550"; break;
				case 1:  nextColor = "#00AA00"; break;
				case 2: nextColor = "#55FF55"; break;
				case 3: nextColor = "#FF55FF"; break;
				case 4: nextColor = "#00AAAA"; break;
				case 5: nextColor = "#FFAA00"; break;
				case 6: nextColor = "#AA0000"; break;
				case 7: nextColor = "#000000"; break;
			}
			document.getElementById("rainbow").style.color = nextColor;
			if (colorId == 8)
			{
				colorId = 0;
			}
			colorId++;
		}
		</script>
	<?php
	}
}
?>