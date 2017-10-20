<h2>
				  <span style="color: <?=$color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;">
					<?php
					if($userinfo['ban'] == 1)
					{
					?>
					<span style="color: #FF8A00">BANNI</span>
					<?php
					}
					elseif($userinfo['desert'] == 1)
					{
					?>
					<span style="color: #6A400F">DÉSERTEUR</span>
					<?php
					}
					elseif($userinfo['ban'] == 0)
					{
					if($userinfo['desert'] == 0)
					{
					?>
					<?= $userinfo['title']?>
					<?php
					}
					}
					?>, <?= $userinfo['username']?> <?= $userinfo['nom']?></span>
				  </span>
</h2>

<form action="index.php?p=profile&perso=<?= $perso?>" method="POST">
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
												Latéralité
												<br>
												<span style="font-weight: bold;color: #00AA00;">
													<?= $persoinfo['lateralite']?>
												</span>
											  </td>
											  <td colspan="1" style="border-radius: 10px;text-align:center;">
												Poids
												<br>
												<span style="font-weight: bold;color: #00AA00;">
													<?= $persoinfo['poids']?>
												</span>
											  </td>
											  <td colspan="1" style="border-radius: 10px;text-align:center;">
												Taille
												<br>
												<span style="font-weight: bold;color: #00AA00;">
													<?= $persoinfo['taille']?>
												</span>
											  </td>
											 </tr>
											</tbody>
										</table>
										Description Physique
										<br>
										<br>
										<?php echo $descp;?>
									</td>
									<td valign="center" rowspan="3" colspan="3" style="border-radius: 10px;text-align:center;">
									<table width="200px">
									<tbody>
									<tr>
										Qualités
										<br>
										<br>
										-<?= $persoinfo['bonus1']?>
										<br>
										-<?= $persoinfo['bonus2']?>
										<br>
										-<?= $persoinfo['bonus3']?>
										<br>
										-<?= $persoinfo['bonus4']?>
										<br>
										-<?= $persoinfo['bonus5']?>
									</tr>
									</tbody>
									</table>
									</td>
									<td valign="center" rowspan="3" colspan="3" style="border-radius: 10px;text-align:center;">
									<table width="200px">
									<tbody>
									<tr>
										Défauts
										<br>
										<br>
										-<?= $persoinfo['malus1']?>
										<br>
										-<?= $persoinfo['malus2']?>
										<br>
										-<?= $persoinfo['malus3']?>
										<br>
										-<?= $persoinfo['malus4']?>
										<br>
										-<?= $persoinfo['malus5']?>
									</tr>
									</tbody>
									</table>
									</td>
									<td valign="center" rowspan="5" colspan="1" style="border-radius: 10px;text-align:center;">
													<?php
													if(empty($userinfo['pseudo']))
													{
													?>
														<span>Veuillez enregistrer un pseudo avant de générer votre Skin !</span>
													<?php
													}
													else
													{
													?>
														Minecraft: <span style="font-weight: bold;color: #AA00AA;"><?= $userinfo['pseudo']?></span>
													<?php
													}
													?>
												<?php
												if($_SESSION['id'] == $userinfo['id'] OR $_SESSION['rank'] >= 5)
												{
													if(!empty($userinfo['pseudo']))
													{
													?>
													<br>
													<span style="font-size: 12px;color: red">
														Après avoir changer votre Pseudo MC, déconnectez-vous et reconnectez-vous.
													</span>
													<br>
													<br>
													<form method="POST" action="index.php?p=profile&perso=<?= $_SESSION['id']?>">
														<input type="submit" name="skin" value="Skin">
													</form>
													<?php
													}
												}
												if(file_exists("pics/ClassicImage/user_".$userinfo['id'].".png"))
												{
												?>
												<br>
												<br>
														<img src="pics/ClassicImage/user_<?= $userinfo['id']?>.png">
														<br>
														<br>
														<?php
														if($_SESSION['id'] == $userinfo['id'] OR $_SESSION['rank'] >= 5)
														{
														?>
														<form method="POST" action="index.php?p=profile&perso=<?= $_SESSION['id']?>">
															<input target="_blank" type="submit" name="download" value="Télécharger Skin">
															<br>
															<input type="submit" name="unskin" value="Retirer Skin">
														</form>
														<?php
														}
														?>
												<?php
												}
												?>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-radius: 10px;text-align:center;">
										  <span style="color: <?=$color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $userinfo['username']?> <?= $userinfo['nom']?>
										</span>
										<br>
										<form action="index.php?p=profile&perso=<?= $perso?>" method="POST">
											<?php
											if($_SESSION['rank'] >= 6 OR $_SESSION['id'] == $perso)
											{
											?>
											<?php
												if($userinfo['desert'] == 0)
												{
												?>
												<input name="Delete" title="Suppression !" type="submit" style='color: #6A400F' value="[DEL]">
												<?php
												}
												elseif($userinfo['desert'] == 1)
												{
												?>
												<input name="unDelete" title="Suppression !" type="submit" style='color: #321e07' value="[uDEL]">
												<?php
												}
												?>
											<?php
											}
											?>
											<?php
											if($_SESSION['rank'] >= 7 OR $_SESSION['id'] == 28)
											{
											?>
												<?php
												if($userinfo['digni'] == 0)
												{
												if($userinfo['rank'] == 6)
												{
												?>
												<input name="DigniMJE" title="Passer Dignitaire !" type="submit" style='color: #AA00AA' value="[DMJE]">
												<?php
												}
												}
												elseif($userinfo['digni'] == 1)
												{
												if($userinfo['rank'] == 5)
												{
												?>
												<input name="unDigni" title="Passer Normal !" type="submit" style='color: #AA0000' value="[uDMJE]">
												<?php
												}
												}
												?>
											<?php
											}
											if($_SESSION['rank'] >= 6 OR $_SESSION['id'] == 28)
											{
											?>
												<?php
												if($userinfo['rank'] == 5)
												{
												?>
													<?php
													if($userinfo['pnj'] == 0)
													{
													?>
													<input name="Dieu" title="Dieu !" type="submit" style='color: #0200A6' value="[D]">
													<?php
													}
													if($userinfo['pnj'] == 2)
													{
													?>
													<input name="Humain" title="Humain !" type="submit" style='color: #555550' value="[H]">
													<?php
													}
													?>
												<?php
												}
												if($userinfo['rank'] == 4)
												{
												?>
													<?php
													if($userinfo['pnj'] == 0)
													{
													?>
													<input name="PNJ" title="PNJ !" type="submit" style='color: #AAAAAA' value="[P]">
													<input name="E" title="Entité !" type="submit" style='color: #55FFFF' value="[E]">
													<?php
													}
													if($userinfo['pnj'] == 1)
													{
													?>
													<input name="Humain" title="Humain !" type="submit" style='color: #555550' value="[H]">
													<input name="E" title="Entité !" type="submit" style='color: #55FFFF' value="[E]">
													<?php
													}
													if($userinfo['pnj'] == 3)
													{
													?>
													<input name="Humain" title="Humain !" type="submit" style='color: #555550' value="[H]">
													<input name="PNJ" title="PNJ !" type="submit" style='color: #AAAAAA' value="[P]">
													<?php
													}
													?>
												<?php
												}
												?>
												<?php
												if($userinfo['digni'] == 0)
												{
												if($userinfo['rank'] == 5)
												{
												?>
												<input name="DigniMJ" title="Passer Dignitaire !" type="submit" style='color: #FFFF55' value="[DMJ]">
												<?php
												}
												}
												elseif($userinfo['digni'] == 2)
												{
												if($userinfo['rank'] == 4)
												{
												?>
												<input name="unDigni" title="Passer Normal !" type="submit" style='color: #FFAA00' value="[uDMJ]">
												<?php
												}
												}
												?>
											<?php
											}
											if($_SESSION['rank'] >= 5 OR $_SESSION['id'] == 28)
											{
											?>
												<?php
												if($userinfo['vanish'] == 0)
												{
												?>
												<input name="Vanish" title="Invisibilité !" type="submit" style='color: #FF55FF' value="[V]">
												<?php
												}
												elseif($userinfo['vanish'] == 1)
												{
												?>
												<input name="unVanish" title="Désinvisibilité !" type="submit" style='color: #CB49CB' value="[uV]">
												<?php
												}
												?>
												<?php
												if($userinfo['ban'] == 0)
												{
												?>
												<input name="Ban" title="Bannir !" type="submit" style='color: #FF8A00' value="[B]">
												<?php
												}
												elseif($userinfo['ban'] == 1)
												{
												?>
												<input name="Pardon" title="Pardonner !" type="submit" style='color: #6E3C00' value="[uB]">
												<?php
												}
												?>
												<?php
												if($userinfo['rank'] == 1)
												{
												?>
												<input name="VIP" title="Passer VIP !" type="submit" style='color: #FF55FF' value="[VIP]">
												<input name="Alpha" title="Passer Alpha !" type="submit" style='color: #55FF55' value="[Al]">
												<?php
												}
												elseif($userinfo['rank'] == 2)
												{
												?>
												<input name="VIP" title="Passer VIP !" type="submit" style='color: #FF55FF' value="[VIP]">
												<input name="Joueur" title="Passer Joueur !" type="submit" style='color: #FF5555' value="[Jo]">
												<?php
												}
												elseif($userinfo['rank'] == 3)
												{
												?>
												<input name="Alpha" title="Passer Alpha !" type="submit" style='color: #55FF55' value="[Al]">
												<input name="Joueur" title="Passer Joueur !" type="submit" style='color: #00AA00' value="[Jo]">
												<?php
												}
												?>
												<?php
												if($userinfo['digni'] == 0)
												{
												if($userinfo['rank'] == 4)
												{
												?>
												<input name="DigniCadre" title="Passer Dignitaire !" type="submit" style='color: #5555FF' value="[DC]">
												<?php
												}
												}
												elseif($userinfo['digni'] == 3)
												{
												if($userinfo['rank'] == 1)
												{
												?>
												<input name="unDigni" title="Passer Normal !" type="submit" style='color: #00AAAA' value="[uDC]">
												<?php
												}
												elseif($userinfo['rank'] == 2)
												{
												?>
												<input name="unDigni" title="Passer Normal !" type="submit" style='color: #00AAAA' value="[uDC]">
												<?php
												}
												elseif($userinfo['rank'] == 3)
												{
												?>
												<input name="unDigni" title="Passer Normal !" type="submit" style='color: #00AAAA' value="[uDC]">
												<?php
												}
												}
												?>
											<?php
											}
											if($_SESSION['rank'] >= 4 OR $_SESSION['id'] == 28)
											{
											?>
												<?php
												if($userinfo['rank'] == 1)
												{
												if($userinfo['actif'] == 0)
												{
												?>
												<input name="Actif" title="Passer Actif !" type="submit" style='color: #FF5555' value="[Ac]">
												<?php
												}
												elseif($userinfo['actif'] == 1)
												{
												?>
												<input name="inActif" title="Passer Actif !" type="submit" style='color: #B93F3F' value="[inAc]">
												<?php
												}
												}
												?>
											<?php
											}
											?>
											<?php
											if($userinfo['rank'] <= 6 OR $_SESSION['id'] == 28)
											{
											if($userinfo['rank'] >= 1 OR $_SESSION['id'] == 28)
											{
											?>
												<?php
												if($_SESSION['rank'] >= 5 OR $_SESSION['id'] == 28)
												{
												if($_SESSION['rank'] >= $userinfo['rank'] + 2 OR $_SESSION['id'] == 28)
												{
												?>
												<input name="upgrade" title="Gradation !" type="submit" style='color: #00AA00' value="[+]">
												<?php
												}
												}
												?>
											<?php
											}
											}
											?>
											<?php
											if($userinfo['rank'] >= 4 OR $_SESSION['id'] == 28)
											{
											?>
												<?php
												if($_SESSION['rank'] >= 5 OR $_SESSION['id'] == 28)
												{
												if($_SESSION['rank'] >= $userinfo['rank'] + 1 OR $_SESSION['id'] == 28)
												{
												?>
												<input name="retrograde" title="Dégradation !" type="submit" style='color: #AA0000' value="[-]">
												<?php
												}
												}
												?>
											<?php
											}
											?>
										</form>
									</td>
								</tr>
								<tr>
									<td colspan="1" style="border-radius: 10px;text-align:center;">
										<span><?= $userinfo['race']?></span>
									</td>
									<td colspan="1" style="border-radius: 10px;text-align:center;">
										<?php
										if($userinfo['ban'] == 1)
										{
										?>
										<span style="color: #FF8A00">BANNI</span>
										<?php
										}
										elseif($userinfo['desert'] == 1)
										{
										?>
										<span style="color: #6A400F">DÉSERTEUR</span>
										<?php
										}
										elseif($userinfo['ban'] == 0)
										{
										if($userinfo['desert'] == 0)
										{
										?>
										<span><?= $userinfo['title']?></span>
										<?php
										}
										}
										?>
										<?php
										if($userinfo['digni'] == 1)
										{
										?>
										<span style="color: #AA0000">(Dignitaire)</span>
										<?php
										}
										elseif($userinfo['digni'] == 2)
										{
										?>
										<span style="color: #FFAA00">(Dignitaire)</span>
										<?php
										}
										elseif($userinfo['digni'] == 3)
										{
										?>
										<span style="color: #00AAAA">(Dignitaire)</span>
										<?php
										}
										?>
									</td>
									<td rowspan="1" style="border-radius: 10px;text-align:center;">
										Physique
										<br>
										<?php
										if($persoinfo['Physique'] >= 100)
										{
										?>
										<span style="color: #55FF55;font-weight: bold;"><?= $persoinfo['Physique']?>%</span>
										<?php
										} elseif($persoinfo['Physique'] >= 80)
										{
										?>
										<span style="color: #00AA00;font-weight: bold;"><?= $persoinfo['Physique']?>%</span>
										<?php
										} elseif($persoinfo['Physique'] >= 60)
										{
										?>
										<span style="color: #00AAAA;font-weight: bold;"><?= $persoinfo['Physique']?>%</span>
										<?php
										} elseif($persoinfo['Physique'] >= 40)
										{
										?>
										<span style="color: #FFAA00;font-weight: bold;"><?= $persoinfo['Physique']?>%</span>
										<?php
										} elseif($persoinfo['Physique'] >= 20)
										{
										?>
										<span style="color: #AA0000;font-weight: bold;"><?= $persoinfo['Physique']?>%</span>
										<?php
										} elseif($persoinfo['Physique'] >= 0)
										{
										?>
										<span style="color: #FF5555;font-weight: bold;"><?= $persoinfo['Physique']?>%</span>
										<?php
										}
										?>
									</td>
									<td rowspan="1" style="border-radius: 10px;text-align:center;">
										Mental
										<br>
										<?php
										if($persoinfo['Mental'] >= 100)
										{
										?>
										<span style="color: #55FF55;font-weight: bold;"><?= $persoinfo['Mental']?>%</span>
										<?php
										} elseif($persoinfo['Mental'] >= 80)
										{
										?>
										<span style="color: #00AA00;font-weight: bold;"><?= $persoinfo['Mental']?>%</span>
										<?php
										} elseif($persoinfo['Mental'] >= 60)
										{
										?>
										<span style="color: #00AAAA;font-weight: bold;"><?= $persoinfo['Mental']?>%</span>
										<?php
										} elseif($persoinfo['Mental'] >= 40)
										{
										?>
										<span style="color: #FFAA00;font-weight: bold;"><?= $persoinfo['Mental']?>%</span>
										<?php
										} elseif($persoinfo['Mental'] >= 20)
										{
										?>
										<span style="color: #AA0000;font-weight: bold;"><?= $persoinfo['Mental']?>%</span>
										<?php
										} elseif($persoinfo['Mental'] >= 0)
										{
										?>
										<span style="color: #FF5555;font-weight: bold;"><?= $persoinfo['Mental']?>%</span>
										<?php
										}
										?>
									</td>
								</tr>
								<tr>
									<td colspan="10" rowspan="1" style="border-radius: 10px;text-align:center;">
										Description Mentale
										<br>
										<br>
										<?php echo $descm;?>
									</td>
								</tr>
								<tr>
									<td valign="top" rowspan="1" colspan="10" style="border-radius: 10px;text-align:center;width: auto;">
										Histoire
										<?php
										if($persoinfo['bgvalid'] == 1)
										{
											?>
											<img title="Sceau d'approbation des MJs apposé par <?=$persovalid['username']?>, <?=$date?>" align="right" width="50px" src="pics/Accueil/seal.png">
											<?php
										}
										elseif($persoinfo['bgvalid'] == 2)
										{
											?>
											<img title="Sceau de désapprobation des MJs apposé par <?=$persovalid['username']?>, <?=$date?>" align="right" width="50px" src="pics/Accueil/seal3.png">
											<?php
										}
										elseif($persoinfo['bgvalid'] == 0)
										{
											?>
											<img title="En cours d'examination !" align="right" width="50px" src="pics/Accueil/seal2.png">
											<?php
										}
										if($_SESSION['rank'] >= 5 AND $persoinfo['bgvalid'] == 0)
										{
											?>
											<form method="POST">
												<input type="submit" name="bgvalid" value="Valider l'Histoire !">
												<input type="submit" name="bgrefuse" value="Refuser l'Histoire !">
											</form>
											<?php
										}
										?>
										<br>
										<br>
										<?php echo $bg;?>
									</td>
								</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</form>

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
<br>
<?php
if($_SESSION['id'] == $perso OR $_SESSION['rank'] >= 4)
{
if($persoinfo['bgvalid'] == 1)
{
?>
<br>
<br>
<form action="index.php?p=profile&perso=<?= $perso?>" method="POST" enctype="multipart/form-data">
<span style="color: darkred;font-weight: bold;font-size: 20px;">
	Votre Histoire a été validée par <?=$persovalid['username']?>, <?=$date?>
</span>
<?php
}
elseif($persoinfo['bgvalid'] == 2)
{
?>
<br>
<br>
<form action="index.php?p=profile&perso=<?= $perso?>" method="POST" enctype="multipart/form-data">
<span style="color: darkred;font-weight: bold;font-size: 20px;">
	Votre Histoire a été refusée par <?=$persovalid['username']?>, <?=$date?>
</span>
<?php
}
}
if($_SESSION['id'] == $perso)
{
?>
<br>
<br>
<form action="index.php?p=profile&perso=<?= $perso?>" method="POST" enctype="multipart/form-data">
<span style="font-weight: bold;font-size: 20px;">
	Recharger votre Image de Profil !
</span>
<br>
<span style="color: darkred">
	Format PNG
	<br>
	Minimum 200x200
	<br>
	Image CARRÉE
	<br>
	(S'il vous plait, mettez de la transparence au lieu des fonds unicolores, sinon on supprime, merci !)
</span>
<br>
<br>
<input type="file" name="send_img" id="img"> <input type="submit" style="text-align:right;" name="sendnew" value="Envoyer">
<br>
<br>
<input type='submit' name='del_img' id='del_img' value="Supprimer l'Image ?">
</form>
<?php
}
if($_SESSION['id'] == $perso)
{
?>
<br>
<br>
<span style="font-weight: bold;font-size: 20px;">
	Ce personnage vous appartient ?
</span>
<br>
<a href="index.php?p=editprofile&perso=<?= $userinfo['id']?>">Cliquez ici</a> pour Editer votre Profil !
<?php
}
?>
<?php
if($_SESSION['rank'] >= 4)
{
?>
<br>
<br>
<span style="font-weight: bold;font-size: 20px;">
	Vous êtes assez haut gradé pour modifier cette page ?
</span>
<br>
<a href="index.php?p=editprofilemj&perso=<?= $userinfo['id']?>">Cliquez ici</a> pour Editer le Profil !
<?php
}
?>
<?php
if($_SESSION['rank'] >= 4)
{
?>
<br>
<br>
<span style="font-weight: bold;font-size: 20px;">
	Voici les Métiers et Compétences que ce joueur entreprends !
</span>
<br>
<a href="index.php?p=competence&perso=<?= $userinfo['id']?>">Cliquez ici</a> pour les voir !
<?php
}
?>
<?php
if($_SESSION['rank'] >= 5)
{
?>
<br>
<br>
<span style="font-weight: bold;font-size: 20px;">
	Voici les Clés que ce Joueur a récolté !
</span>
<br>
<a href="index.php?p=sucesskey&perso=<?= $userinfo['id']?>">Cliquez ici</a> pour les voir !
<?php
}
?>
<?php
if($_SESSION['id'] != $userinfo['id'])
{
?>
<br>
<br>
<span style="font-weight: bold;font-size: 20px;">
	Les Succès !
</span>
<br>
<a href="index.php?p=achievement&perso=<?= $userinfo['id']?>">Cliquez ici</a> pour voir les Succès de ce Joueur !
<?php
}
?>