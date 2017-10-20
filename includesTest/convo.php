<?php function convo()
{
	global $db, $_SESSION, $_POST, $_GET;
?>

<p style="color:darkred;font-weight:bold">
[Y'a juste besoin de cliquer !]
<br>
<br>
<table style="width:96%;;margin-left:2%;margin-right:2%;font-weight:normal">
	<tbody>	
		<tr style="height:110px">
			<td style="width:110px"><a href="http://join.skype.com/dr4wJ1c8s79O" onclick="window.open(this.href); return false;">
				<img class="imghover" src="pics/Accueil/GSskype.png" align="center" valign="middle" style="height:96px;border-radius:100%"></a>
			</td>
			<td align="left">
				<h3>Flood</h3>
				<br>
				<br>
				C'est la Conversation du Nawak... toute personne est libre de la quitter.
				<br>
				<br>
				Alors si le Staff et les Joueurs quittent cette conversation par votre faute...
				<br>
				Vous ne vous en prendrez qu'à vous.
				<br>
			</td>
		</tr>
		<?php
		if($_SESSION['rank'] >= 0)
		{
		?>
			<tr style="height:110px">
				<td style="width:110px"><a href="https://join.skype.com/gy35dQTvhH3c" onclick="window.open(this.href); return false;">
					<img class="imghover" src="pics/Accueil/GSskype.png" align="center" valign="middle" style="height:96px;border-radius:100%"></a>
				</td>
				<td align="left">
					<h3>Aide aux Joueurs</h3>
					<br>
					<br>
					C'est la Conversation où seulement le Staff et les Nouveaux Joueurs se trouvent.
					<br>
					<br>
					Vous êtes priés de quitter la Conversation si vous n'avez pas de questions.
					<br>
				</td>
			</tr>
		<?php
		}
		?>
		<?php
		if($_SESSION['rank'] >= 1)
		{
		?>
			<tr style="height:110px">
				<td style="width:110px"><a href="http://join.skype.com/bKGMp0gff0Df" onclick="window.open(this.href); return false;">
					<img class="imghover" src="pics/Accueil/GSskype.png" align="center" valign="middle" style="height:96px;border-radius:100%"></a>
				</td>
				<td align="left">
					<h3>Discussion</h3>
					<br>
					<br>
					C'est la Conversation de Discussion, elle est là pour discuter.
					<br>
					<br>
					Elle est officielle, et les punitions tomberont ici.
					<br>
					Elle est seulement accéssible aux Joueurs.
					<br>
				</td>
			</tr>
		<?php
		}
		?>
		<?php
		if($_SESSION['rank'] >= 1)
		{
		?>
			<tr style="height:110px">
				<td style="width:110px"><a href="https://join.skype.com/ePfbpsrC6HE7" onclick="window.open(this.href); return false;">
					<img class="imghover" src="pics/Accueil/GSskype.png" align="center" valign="middle" style="height:96px;border-radius:100%"></a>
				</td>
				<td align="left">
					<h3>Sérieux</h3>
					<br>
					<br>
					La Conversation des Sérieuse pour parler du Serveur.
					<br>
				</td>
			</tr>
		<?php
		}
		?>
		<?php
		if($_SESSION['rank'] >= 4)
		{
		?>
			<tr style="height:110px">
				<td style="width:110px"><a href="http://join.skype.com/kgbdNHOBlzvA" onclick="window.open(this.href); return false;">
					<img class="imghover" src="pics/Accueil/GSskype.png" align="center" valign="middle" style="height:96px;border-radius:100%"></a>
				</td>
				<td align="left">
					<h3>Staff</h3>
					<br>
					<br>
					La Conversation des Staffeux !
					<br>
				</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<?php
}
?>