<?php function mp()
{
	global $db, $_POST, $_GET, $_SESSION;

	if(isset($_GET['mode']) AND $_GET['mode'] == "read")
	{
		if(isset($_GET['mode']) AND $_GET['mode'] == "read" AND !isset($_GET['consult']))
		{
			if(isset($_GET['action']) AND $_GET['action'] == "del")
			{
				$del = $db->prepare('UPDATE mp SET vanish = 1 WHERE id = ?');
				$del->execute(array($_GET['msg']));
				header('Location: index.php?p=mp&mode=read');
			}
			
			if(isset($_GET['action']) AND $_GET['action'] == "archive")
			{
				$del = $db->prepare('UPDATE mp SET archive = 1 WHERE id = ?');
				$del->execute(array($_GET['msg']));
				header('Location: index.php?p=mp&mode=read');
			}
			
			if(isset($_POST['write']))
			{
				header('Location: index.php?p=mp&mode=write');
			}
			
			if(isset($_POST['archived']))
			{
				header('Location: index.php?p=mp&mode=archived');
			}
			
			if(isset($_POST['send']))
			{
				header('Location: index.php?p=mp&mode=send');
			}
			
			if(isset($_POST['luall']))
			{
				$luall = $db->prepare('UPDATE mp SET statut = 1 WHERE receiver = ?');
				$luall->execute(array($_SESSION['id']));
				header('Location: index.php?p=mp&mode=read');
			}
			
			if(isset($_POST['archivelu']))
			{
				$archivedlu = $db->prepare('UPDATE mp SET archive = 1 WHERE receiver = ? AND statut = 1');
				$archivedlu->execute(array($_SESSION['id']));
				header('Location: index.php?p=mp&mode=read');
			}
			
			$reqmp = $db->prepare('SELECT mp.id, mp.sender, mp.receiver, mp.date_send, mp.topic, mp.statut, mp.archive, mp.rp, mp.vanish, ml.id AS ml_id, ml.username, ml.nom, ml.title FROM mp mp RIGHT JOIN member_list ml ON mp.sender = ml.id WHERE mp.receiver = ? AND mp.archive = ? AND mp.vanish = 0 ORDER BY mp.date_send DESC, mp.topic ASC');
			$reqmp->execute(array($_SESSION['id'], '0'));
			?>
			<span style="font-size: 20px;font-weight: bold;">Messages reçus</span>
			<br>
			<br>
			<form method="POST">
			<input name="write" type="submit" value="Écrire un Message">
			<input name="archived" type="submit" value="Messages Archivés">
			<input name="send" type="submit" value="Messages Envoyés">
			<input name="luall" type="submit" value="Tout Marquer comme Lu">
			<input name="archivelu" type="submit" value="Archiver Messages Lus">
			<br>
			<br>
			<table class="mptable" width="100%">
				<tbody>
					<tr>
						<th width="50%">
							Sujet
						</th>
						<th>
							Auteur
						</th>
						<th>
							Date de Réception
						</th>
						<th>
							Statut
						</th>
						<th>
							Action
						</th>
					</tr>
					<?php
					while($mpinfo = $reqmp->fetch())
					{
						$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $mpinfo['date_send']);
						?>
						<tr>
							<td align="center">
								<a href="index.php?p=mp&mode=read&consult=<?=$mpinfo['id']?>">
									<?php
									if($mpinfo['rp'] == 0)
									{
										?>
										<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP]</span>
										<?php
									}
									elseif($mpinfo['rp'] == 1)
									{
										?>
										<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP]</span>
										<?php
									}
									if($mpinfo['statut'] == 0)
									{
										?>
										<?=$mpinfo['topic']?>
										<?php
									}
									elseif($mpinfo['statut'] == 1)
									{
										?>
										<span style="color: #555550" onmouseout="this.style.color='#555550'" onmouseover="this.style.color='white'">
											<?=$mpinfo['topic']?>
										</span>
										<?php
									}
									?>
								</a>
							</td>
							<td align="left">
								<a href="index.php?p=profile&perso=<?=$mpinfo['sender']?>">
									<?=$mpinfo['title']?>, <?=$mpinfo['username']?> <?=$mpinfo['nom']?>
								</a>
							</td>
							<td align="left">
								<?=$date?>
							</td>
							<td align="left">
								<?php
								if($mpinfo['statut'] == 0)
								{
									echo "Non Lu";
								}
								elseif($mpinfo['statut'] == 1)
								{
									echo "Lu";
								}
								?>
							</td>
							<td align="center">
								<a title="Archiver !" href="index.php?p=mp&mode=read&msg=<?=$mpinfo['id']?>&action=archive"><span class="username-detail" style="font-weight: bold; color: #5555FF" onmouseout="this.style.color='#5555FF'" onmouseover="this.style.color='white'">[A]</span></a>
								<a title="Supprimer !" href="index.php?p=mp&mode=read&msg=<?=$mpinfo['id']?>&action=del"><span class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'">[X]</span></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<br>
			<input name="write" type="submit" value="Écrire un Message">
			<input name="archived" type="submit" value="Messages Archivés">
			<input name="send" type="submit" value="Messages Envoyés">
			<input name="luall" type="submit" value="Tout Marquer comme Lu">
			<input name="archivelu" type="submit" value="Archiver Messages Lus">
			</form>
			<?php
		}
		elseif(isset($_GET['mode']) AND $_GET['mode'] == "read" AND isset($_GET['consult']))
		{
			$reqmp = $db->prepare('SELECT mp.id, mp.sender, mp.receiver, mp.date_send, mp.message, mp.topic, mp.statut, mp.archive, mp.rp, mp.vanish, ml.id AS ml_id, ml.username, ml.nom, ml.title FROM mp mp RIGHT JOIN member_list ml ON mp.sender = ml.id WHERE mp.id = ?');
			$reqmp->execute(array($_GET['consult']));
			$mpinfo = $reqmp->fetch();
			
			if($_SESSION['id'] == $mpinfo['receiver'])
			{
				$lu = $db->prepare('UPDATE mp SET statut = 1 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
			}
			
			if(isset($_POST['recu']))
			{
				header('Location: index.php?p=mp&mode=read');
			}
			
			if(isset($_POST['answer']))
			{
				header('Location: index.php?p=mp&mode=write');
			}
			
			if(isset($_POST['archive']))
			{
				$lu = $db->prepare('UPDATE mp SET archive = 1 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
				header('Location: index.php?p=mp&mode=archived&consult='.$_GET['consult'].'');
			}
			
			if(isset($_POST['vanish']))
			{
				$lu = $db->prepare('UPDATE mp SET vanish = 1 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
				header('Location: index.php?p=mp&mode=read');
			}
			
			$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $mpinfo['date_send']);
			$message = preg_replace('#\n#', '<br>', $mpinfo['message']);
			?>
			<span style="font-size: 20px;font-weight: bold;">Consultation de Message</span>
			<br>
			<br>
			<form method="POST">
			<input type="submit" name="recu" value="Retour Messages Reçus">
			<input type="submit" name="answer" value="Répondre">
			<input type="submit" name="archive" value="Archiver">
			<input type="submit" name="vanish" value="Supprimer">
			<br>
			<br>
			<table class="mptable" style="border-collapse: collapse" width="100%">
				<tbody>
					<tr>
						<td>
							Auteur:
							<br>
							Envoyé:
							<br>
							Sujet:
						</td>
						<td>
							<a href="index.php?p=profile&perso=<?=$mpinfo['sender']?>">
								<?=$mpinfo['title']?>, <?=$mpinfo['username']?> <?=$mpinfo['nom']?>
							</a>
							<br>
							<?=$date?>
							<br>
							<?php
							if($mpinfo['rp'] == 0)
							{
								?>
								<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP]</span>
								<?php
							}
							elseif($mpinfo['rp'] == 1)
							{
								?>
								<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP]</span>
								<?php
							}
							?>
							<?=$mpinfo['topic']?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?=$message?>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<input type="submit" name="recu" value="Retour Messages Reçus">
			<input type="submit" name="answer" value="Répondre">
			<input type="submit" name="archive" value="Archiver">
			<input type="submit" name="vanish" value="Supprimer">
			</form>
			<?php
		}
	}
	elseif(isset($_GET['mode']) AND $_GET['mode'] == "send")
	{	
		if(isset($_GET['mode']) AND $_GET['mode'] == "send" AND !isset($_GET['consult']))
		{
			$reqmp = $db->prepare('SELECT mp.id, mp.sender, mp.receiver, mp.date_send, mp.topic, mp.statut, mp.archive, mp.rp, mp.vanish, ml.id AS ml_id, ml.username, ml.nom, ml.title FROM mp mp RIGHT JOIN member_list ml ON mp.receiver = ml.id WHERE mp.sender = ? ORDER BY mp.date_send DESC, mp.topic ASC');
			$reqmp->execute(array($_SESSION['id']));
			
			if(isset($_POST['recu']))
			{
				header('Location: index.php?p=mp&mode=read');
			}
			?>
			<span style="font-size: 20px;font-weight: bold;">Messages envoyés</span>
			<br>
			<br>
			<form method="POST">
			<input name="recu" type="submit" value="Retour Messages Reçus">
			<br>
			<br>
			<table class="mptable" width="100%">
				<tbody>
					<tr>
						<th width="50%">
							Sujet
						</th>
						<th>
							Destinataire
						</th>
						<th>
							Date d'Envoie
						</th>
						<th>
							Statut
						</th>
					</tr>
					<?php
					while($mpinfo = $reqmp->fetch())
					{
						$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $mpinfo['date_send']);
						?>
						<tr>
							<td align="center">
								<a href="index.php?p=mp&mode=send&consult=<?=$mpinfo['id']?>">
									<?php
									if($mpinfo['rp'] == 0)
									{
										?>
										<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP]</span>
										<?php
									}
									elseif($mpinfo['rp'] == 1)
									{
										?>
										<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP]</span>
										<?php
									}
									if($mpinfo['statut'] == 0)
									{
										?>
										<?=$mpinfo['topic']?>
										<?php
									}
									elseif($mpinfo['statut'] == 1)
									{
										?>
										<span style="color: #555550" onmouseout="this.style.color='#555550'" onmouseover="this.style.color='white'">
											<?=$mpinfo['topic']?>
										</span>
										<?php
									}
									?>
								</a>
							</td>
							<td align="left">
								<a href="index.php?p=profile&perso=<?=$mpinfo['receiver']?>">
									<?=$mpinfo['title']?>, <?=$mpinfo['username']?> <?=$mpinfo['nom']?>
								</a>
							</td>
							<td align="left">
								<?=$date?>
							</td>
							<td align="left">
								<?php
								if($mpinfo['statut'] == 0)
								{
									echo "Non Lu";
								}
								elseif($mpinfo['statut'] == 1)
								{
									echo "Lu";
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
			<input name="recu" type="submit" value="Retour Messages Reçus">
			</form>
			<?php
		}
		elseif(isset($_GET['mode']) AND $_GET['mode'] == "send" AND isset($_GET['consult']))
		{
			$reqmp = $db->prepare('SELECT mp.id, mp.sender, mp.receiver, mp.date_send, mp.message, mp.topic, mp.statut, mp.archive, mp.rp, mp.vanish, ml.id AS ml_id, ml.username, ml.nom, ml.title FROM mp mp RIGHT JOIN member_list ml ON mp.receiver = ml.id WHERE mp.id = ?');
			$reqmp->execute(array($_GET['consult']));
			$mpinfo = $reqmp->fetch();
			
			if($_SESSION['id'] == $mpinfo['receiver'])
			{
				$lu = $db->prepare('UPDATE mp SET statut = 1 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
			}
			
			if(isset($_POST['send']))
			{
				header('Location: index.php?p=mp&mode=send');
			}
			
			if(isset($_POST['answer']))
			{
				header('Location: index.php?p=mp&mode=write');
			}
			
			$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $mpinfo['date_send']);
			$message = preg_replace('#\n#', '<br>', $mpinfo['message']);
			?>
			<span style="font-size: 20px;font-weight: bold;">Consultation de Message</span>
			<br>
			<br>
			<form method="POST">
			<input type="submit" name="send" value="Retour Messages Envoyés">
			<br>
			<br>
			<table class="mptable" style="border-collapse: collapse" width="100%">
				<tbody>
					<tr>
						<td>
							Destinataire:
							<br>
							Envoyé:
							<br>
							Sujet:
						</td>
						<td>
							<a href="index.php?p=profile&perso=<?=$mpinfo['receiver']?>">
								<?=$mpinfo['title']?>, <?=$mpinfo['username']?> <?=$mpinfo['nom']?>
							</a>
							<br>
							<?=$date?>
							<br>
							<?php
							if($mpinfo['rp'] == 0)
							{
								?>
								<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP]</span>
								<?php
							}
							elseif($mpinfo['rp'] == 1)
							{
								?>
								<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP]</span>
								<?php
							}
							?>
							<?=$mpinfo['topic']?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?=$message?>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<input type="submit" name="send" value="Retour Messages Envoyés">
			</form>
			<?php
		}
	}
	elseif(isset($_GET['mode']) AND $_GET['mode'] == "archived")
	{
		if(isset($_GET['mode']) AND $_GET['mode'] == "archived" AND !isset($_GET['consult']))
		{
			if(isset($_GET['action']) AND $_GET['action'] == "del")
			{
				$del = $db->prepare('UPDATE mp SET vanish = 1, archive = 0 WHERE id = ?');
				$del->execute(array($_GET['msg']));
				header('Location: index.php?p=mp&mode=archived');
			}
			
			if(isset($_GET['action']) AND $_GET['action'] == "archive")
			{
				$del = $db->prepare('UPDATE mp SET archive = 0 WHERE id = ?');
				$del->execute(array($_GET['msg']));
				header('Location: index.php?p=mp&mode=archived');
			}
			
			$reqmp = $db->prepare('SELECT mp.id, mp.sender, mp.receiver, mp.date_send, mp.topic, mp.statut, mp.archive, mp.rp, mp.vanish, ml.id AS ml_id, ml.username, ml.nom, ml.title FROM mp mp RIGHT JOIN member_list ml ON mp.sender = ml.id WHERE mp.receiver = ? AND mp.archive = ? AND mp.vanish = 0 ORDER BY mp.date_send DESC, mp.topic ASC');
			$reqmp->execute(array($_SESSION['id'], '1'));
			
			if(isset($_POST['recu']))
			{
				header('Location: index.php?p=mp&mode=read');
			}
			?>
			<span style="font-size: 20px;font-weight: bold;">Messages archivés</span>
			<br>
			<br>
			<form method="POST">
			<input name="recu" type="submit" value="Retour Messages Reçus">
			<br>
			<br>
			<table class="mptable" width="100%">
				<tbody>
					<tr>
						<th width="50%">
							Sujet
						</th>
						<th>
							Auteur
						</th>
						<th>
							Date de Réception
						</th>
						<th>
							Statut
						</th>
						<th>
							Action
						</th>
					</tr>
					<?php
					while($mpinfo = $reqmp->fetch())
					{
						$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $mpinfo['date_send']);
						?>
						<tr>
							<td align="center">
								<a href="index.php?p=mp&mode=archived&consult=<?=$mpinfo['id']?>">
									<?php
									if($mpinfo['rp'] == 0)
									{
										?>
										<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP]</span>
										<?php
									}
									elseif($mpinfo['rp'] == 1)
									{
										?>
										<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP]</span>
										<?php
									}
									if($mpinfo['statut'] == 0)
									{
										?>
										<?=$mpinfo['topic']?>
										<?php
									}
									elseif($mpinfo['statut'] == 1)
									{
										?>
										<span style="color: #555550" onmouseout="this.style.color='#555550'" onmouseover="this.style.color='white'">
											<?=$mpinfo['topic']?>
										</span>
										<?php
									}
									?>
								</a>
							</td>
							<td align="left">
								<a href="index.php?p=profile&perso=<?=$mpinfo['sender']?>">
									<?=$mpinfo['title']?>, <?=$mpinfo['username']?> <?=$mpinfo['nom']?>
								</a>
							</td>
							<td align="left">
								<?=$date?>
							</td>
							<td align="left">
								<?php
								if($mpinfo['statut'] == 0)
								{
									echo "Non Lu";
								}
								elseif($mpinfo['statut'] == 1)
								{
									echo "Lu";
								}
								?>
							</td>
							<td align="center">
								<a title="Désarchiver !" href="index.php?p=mp&mode=archived&msg=<?=$mpinfo['id']?>&action=archive"><span class="username-detail" style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[A]</span></a>
								<a title="Supprimer !" href="index.php?p=mp&mode=archived&msg=<?=$mpinfo['id']?>&action=del"><span class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'">[X]</span></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			<br>
			<input name="recu" type="submit" value="Retour Messages Reçus">
			</form>
			<?php
		}
		elseif(isset($_GET['mode']) AND $_GET['mode'] == "archived" AND isset($_GET['consult']))
		{
			$reqmp = $db->prepare('SELECT mp.id, mp.sender, mp.receiver, mp.date_send, mp.message, mp.topic, mp.statut, mp.archive, mp.rp, mp.vanish, ml.id AS ml_id, ml.username, ml.nom, ml.title FROM mp mp RIGHT JOIN member_list ml ON mp.sender = ml.id WHERE mp.id = ?');
			$reqmp->execute(array($_GET['consult']));
			$mpinfo = $reqmp->fetch();
			
			if($_SESSION['id'] == $mpinfo['receiver'])
			{
				$lu = $db->prepare('UPDATE mp SET statut = 1 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
			}
			
			if(isset($_POST['archived']))
			{
				header('Location: index.php?p=mp&mode=archived');
			}
			
			if(isset($_POST['unarchive']))
			{
				$lu = $db->prepare('UPDATE mp SET archive = 0 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
				header('Location: index.php?p=mp&mode=read&consult='.$_GET['consult'].'');
			}
			
			if(isset($_POST['vanish']))
			{
				$lu = $db->prepare('UPDATE mp SET vanish = 1 WHERE id = ?');
				$lu->execute(array($_GET['consult']));
				header('Location: index.php?p=mp&mode=archived');
			}
			
			$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $mpinfo['date_send']);
			$message = preg_replace('#\n#', '<br>', $mpinfo['message']);
			?>
			<span style="font-size: 20px;font-weight: bold;">Consultation de Message</span>
			<br>
			<br>
			<form method="POST">
			<input type="submit" name="archived" value="Retour Messages Archivés">
			<input type="submit" name="unarchive" value="Désarchiver">
			<input type="submit" name="vanish" value="Supprimer">
			<br>
			<br>
			<table class="mptable" style="border-collapse: collapse" width="100%">
				<tbody>
					<tr>
						<td>
							Auteur:
							<br>
							Envoyé:
							<br>
							Sujet:
						</td>
						<td>
							<a href="index.php?p=profile&perso=<?=$mpinfo['sender']?>">
								<?=$mpinfo['title']?>, <?=$mpinfo['username']?> <?=$mpinfo['nom']?>
							</a>
							<br>
							<?=$date?>
							<br>
							<?php
							if($mpinfo['rp'] == 0)
							{
								?>
								<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP]</span>
								<?php
							}
							elseif($mpinfo['rp'] == 1)
							{
								?>
								<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP]</span>
								<?php
							}
							?>
							<?=$mpinfo['topic']?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?=$message?>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<input type="submit" name="archived" value="Retour Messages Archivés">
			<input type="submit" name="unarchive" value="Désarchiver">
			<input type="submit" name="vanish" value="Supprimer">
			</form>
			<?php
		}
	}
	elseif(isset($_GET['mode']) AND $_GET['mode'] == "write")
	{
		if(isset($_POST['exit']))
		{
			header('Location: index.php?p=mp&mode=read');
		}
		
		if(isset($_POST['valid']))
		{
			if(!empty($_POST['receiver']) AND !empty($_POST['topic']) AND !empty($_POST['msg']))
			{
				$countname = $db->prepare('SELECT id FROM member_list WHERE username = ?');
				$countname->execute(array($_POST['receiver']));
				$countname2 = $countname->rowCount();
				
				if($countname2 >= 1)
				{
					$idsender = $db->prepare('SELECT id FROM member_list WHERE username = ?');
					$idsender->execute(array($_POST['receiver']));
					$senderid = $idsender->fetch();
					
					if($_SESSION['id'] != $senderid['id'])
					{
						$sendmsg = $db->prepare('INSERT INTO mp VALUES(?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)');
						$sendmsg->execute(array(NULL, $_SESSION['id'], $senderid['id'], $_POST['topic'], $_POST['msg'], '0', '0', $_POST['type'], '0'));
						header('Location: index.php?p=mp&mode=read');
					} else {
						$erreur = "Vous ne pouvez pas vous envoyer un message à vous même...";
					}
				} else {
					$erreur = "Cet Utilisateur n'existe pas !";
				}
			} else {
				$erreur = "Vous devez compléter tout les champs !";
			}
		}
		?>
		<span style="font-size: 20px;font-weight: bold;">Écriture de Message</span>
		<br>
		<br>
		<form method="POST">
		<table class="mptable" width="100%">
			<tbody>
				<tr>
					<td>
						Destinataire:
						<br>
						Sujet:
					</td>
					<td>
						<input type="text" name="receiver" placeholder="UNIQUEMENT le Prénom">
						<br>
						<input type="text" name="topic" placeholder="Nom du Message">
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<textarea type="text" name="msg" style="min-height: 300px;min-width: 500px;" placeholder="Votre Message"></textarea>
					</td>
				</tr>
				<tr>
					<td align="center">
						<input type="radio" name="type" value="0" checked="checked"> HRP
						<input type="radio" name="type" value="1"> RP
					</td>
					<td align="center">
						<?php
						if($erreur == false)
						{
						?>
						<span>Une erreur ? Elle apparaîtra ici !</span>
						<?php
						}
						else
						{
						?>
						<span style="color: red"><?=$erreur?></span>
						<?php
						}
						?>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input type="submit" name="exit" value="Retour">
						<input type="submit" name="valid" value="Envoyer">
					</td>
				</tr>
			</tbody>
		</table>
		</form>
		<?php
	}
}
?>