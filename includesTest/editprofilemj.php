<?php function editprofilemj()

{	global $db, $_POST, $_SESSION, $_GET;
if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{

   $perso = intval($_GET['perso']);
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqperso = $db->prepare('SELECT * FROM page_perso WHERE id = ?');
   $reqperso->execute(array($perso));
   $persoinfo = $reqperso->fetch();
}

if($_SESSION['rank'] >= 4)
{

if(isset($_POST['valid']))
{
   $ID = $perso;
   $username = htmlspecialchars($_POST['username']);
   $race = htmlspecialchars($_POST['race']);
   $title = $_POST['title'];
   $nom = htmlspecialchars($_POST['nom']);
   $mc = htmlspecialchars($_POST['mc']);
   $lateralite = htmlspecialchars($_POST['lateralite']);
   $poids = htmlspecialchars($_POST['poids']);
   $taille = htmlspecialchars($_POST['taille']);
   $Physique = htmlspecialchars($_POST['Physique']);
   $Mental = htmlspecialchars($_POST['Mental']);
   $Descp = htmlspecialchars($_POST['Descp']);
   $Descm = htmlspecialchars($_POST['Descm']);
   $bg = htmlspecialchars($_POST['bg']);
   $bonus1 = htmlspecialchars($_POST['bonus1']);
   $bonus2 = htmlspecialchars($_POST['bonus2']);
   $bonus3 = htmlspecialchars($_POST['bonus3']);
   $bonus4 = htmlspecialchars($_POST['bonus4']);
   $bonus5 = htmlspecialchars($_POST['bonus5']);
   $malus1 = htmlspecialchars($_POST['malus1']);
   $malus2 = htmlspecialchars($_POST['malus2']);
   $malus3 = htmlspecialchars($_POST['malus3']);
   $malus4 = htmlspecialchars($_POST['malus4']);
   $malus5 = htmlspecialchars($_POST['malus5']);
	if(!empty($_POST['username']) AND !empty($_POST['lateralite']) AND !empty($_POST['poids']) AND !empty($_POST['taille']) AND !empty($_POST['Physique']) AND !empty($_POST['Mental']) AND !empty($_POST['race']))
	{
		$nomlength = strlen($nom);
		if($nomlength <= 225)
		{
			if(file_exists("pics/race/Race".$race.".png"))
			{
				$updateuser = $db->prepare('UPDATE member_list SET username = ?, nom = ?, pseudo = ?, race = ?, title = ? WHERE id = ?');
				$updateuser->execute(array($username, $nom, $mc, $race, $title, $ID));
				
				if($bg != $persoinfo['bg'])
				{
					$updatebg = $db->prepare('UPDATE page_perso SET bgvalid = 0 WHERE id = ?');
					$updatebg->execute(array($ID));
				}
			
				$updateperso = $db->prepare('UPDATE page_perso SET lateralite = ?, poids = ?, taille = ?, Physique = ?, Mental = ?, Descp = ?, Descm = ?, bg = ?, bonus1 = ?, bonus2 = ?, bonus3 = ?, bonus4 = ?, bonus5 = ?, malus1 = ?, malus2 = ?, malus3 = ?, malus4 = ?, malus5 = ? WHERE id = ?');
				$updateperso->execute(array($lateralite, $poids, $taille, $Physique, $Mental, $Descp, $Descm, $bg, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $malus1, $malus2, $malus3, $malus4, $malus5, $ID));
				$erreur = header('Location: index.php?p=profile&perso='.$perso.'');
			} else {
				$erreur = "Cette race n'existe pas !";
			}
		} else {
			$erreur = "Votre nom est trop long...";
		}
	} else {
		$erreur = "Vous devez remplir les données avec le signe * avant de pouvoir enregistrer...";
	}
}
?>

<form action="index.php?p=editprofilemj&perso=<?= $perso?>" method="POST">
	<table cellspacing="5" cellpadding="5">
		<tbody>
			<tr>
				<td valign="top" width="50%">
					<table class="pnjtable" style="border-radius: 50px;" cellspacing="5" cellpadding="5" align="center">
						<tbody>
								<tr>
									<td colspan="2" style="border-radius: 10px;">
										<table>
											<tbody>
												<tr>
													<center>
													<?php
													if (file_exists("pics/GrandImage/user_".$userinfo['id'].".png")) {
													?>
													<img style="background-image: url('pics/GrandImage/Transparent.png');" src="pics/GrandImage/user_<?= $userinfo['id']?>.png" alt="" width="200px" height="200px">
													<?php
													} else {
													?>
													<img src="pics/GrandImage/Transparent.png" alt="" width="200px" height="200px">
													<?php
													}
													?>
													</center>
												</tr>
											</tbody>
										</table>
									</td>
									<td valign="top" rowspan="2" colspan="2" style="border-radius: 10px;text-align:center;width: auto;">
										<table cellspacing="5" cellpadding="5" align="center">
											<tbody>
											 <tr>
											  <td colspan="1" style="border-radius: 10px;text-align:center;">
												Latéralité*
												<br>
												<span style="font-weight: bold;color: #00AA00;">
													<input type="text" name="lateralite" value="<?= $persoinfo['lateralite']?>">
												</span>
											  </td>
											  <td colspan="1" style="border-radius: 10px;text-align:center;">
												Poids*
												<br>
												<span style="font-weight: bold;color: #00AA00;">
													<input type="text" name="poids" value="<?= $persoinfo['poids']?>">
												</span>
											  </td>
											  <td colspan="1" style="border-radius: 10px;text-align:center;">
												Taille*
												<br>
												<span style="font-weight: bold;color: #00AA00;">
													<input type="text" name="taille" value="<?= $persoinfo['taille']?>">
												</span>
											  </td>
											 </tr>
											</tbody>
										</table>
										Description Physique
										<br>
										<br>
										<textarea style="width: 650px;min-height: 150px;max-width: 650px;" type="text" name="Descp"><?= $persoinfo['Descp']?></textarea>
									</td>
									<td valign="center" rowspan="4" colspan="3" style="border-radius: 10px;text-align:center;">
									<table width="200px">
									<tbody>
									<tr>
										Bonus
										<br>
										<br>
										-<input type="text" name="bonus1" value="<?= $persoinfo['bonus1']?>">
										<br>
										-<input type="text" name="bonus2" value="<?= $persoinfo['bonus2']?>">
										<br>
										-<input type="text" name="bonus3" value="<?= $persoinfo['bonus3']?>">
										<br>
										-<input type="text" name="bonus4" value="<?= $persoinfo['bonus4']?>">
										<br>
										-<input type="text" name="bonus5" value="<?= $persoinfo['bonus5']?>">
									</tr>
									</tbody>
									</table>
									</td>
									<td valign="center" rowspan="4" colspan="3" style="border-radius: 10px;text-align:center;">
									<table width="200px">
									<tbody>
									<tr>
										Malus
										<br>
										<br>
										-<input type="text" name="malus1" value="<?= $persoinfo['malus1']?>">
										<br>
										-<input type="text" name="malus2" value="<?= $persoinfo['malus2']?>">
										<br>
										-<input type="text" name="malus3" value="<?= $persoinfo['malus3']?>">
										<br>
										-<input type="text" name="malus4" value="<?= $persoinfo['malus4']?>">
										<br>
										-<input type="text" name="malus5" value="<?= $persoinfo['malus5']?>">
									</tr>
									</tbody>
									</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-radius: 10px;text-align:center;">
										Prénom: <input type="text" name="username" value="<?= $userinfo['username']?>"></span>
										</span>
										<br>
										<br>
										Nom: <input type="text" name="nom" value="<?= $userinfo['nom']?>">
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-radius: 10px;text-align:center;">
									<span style="font-size: 12px;color: red">
										Après avoir changer votre Pseudo MC, déconnectez-vous et reconnectez-vous.
									</span>
									<br>
									Minecraft: <input type="text" name="mc" value="<?= $userinfo['pseudo']?>">
									</td>
									<td rowspan="2" style="border-radius: 10px;text-align:center;">
										Physique*
										<br>
										<input type="text" name="Physique" value="<?= $persoinfo['Physique']?>">%
									</td>
									<td rowspan="2" style="border-radius: 10px;text-align:center;">
										Mental*
										<br>
										<input type="text" name="Mental" value="<?= $persoinfo['Mental']?>">%
									</td>
								</tr>
								<tr>
									<td colspan="1" style="border-radius: 10px;text-align:center;">
										Race
										<br>
										<input type="text" name="race" value="<?= $userinfo['race']?>">
									</td>
									<td colspan="1" style="border-radius: 10px;text-align:center;">
										Titre
										<br>
										<input type="text" name="title" value="<?= $userinfo['title']?>">
									</td>
								</tr>
								<tr>
									<td colspan="10" rowspan="1" style="border-radius: 10px;text-align:center;">
										Description Mentale
										<br>
										<br>
										<textarea style="width: 1300px;min-height: 300px;max-width: 1300px;" type="text" name="Descm"><?= $persoinfo['Descm']?></textarea>
										<br>
										<br>
									</td>
								</tr>
								<tr>
									<td valign="top" rowspan="1" colspan="10" style="border-radius: 10px;text-align:center;width: auto;">
										Histoire
										<br>
										<br>
										<textarea style="width: 1300px;min-height: 300px;max-width: 1300px;" type="text" name="bg"><?= $persoinfo['bg']?></textarea>
										<br>
										<br>
									</td>
								</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table>
		<tbody>
			<tr style="width: 100%;">
				<td style="width: 30%;text-align: center;">
				- Les caractéristiques avec un * sont obligatoires.
				<br>
				<br>
				- Veuillez déco' reco' après chaque changement de Pseudo Minecraft ! /!\
				</td>
				<td style="width: 30%;">
					<input style="width: 700px; height: 300px;font-size: 50px;" name="valid" value="Valider" type="submit">
				</td>
				<td style="width: 30%;text-align: center;">
					<?php
					if(isset($erreur))
					{
						echo '<font color="red">'.$erreur.'</font>';
					}
					else
					{
						echo 'Un problème ? Il apparaitra à la place de ce message !';
					}
					?>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
}
else
{
?>
<span style="color: darkred;" class="username-detail">
	Vous n'avez pas l'autorisation d'être ici...
</span>
<?php
}

}