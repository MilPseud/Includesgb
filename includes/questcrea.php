<?php function questcrea()
{
	global $db, $_SESSION, $_POST, $_GET;
	
	if($_SESSION['rank'] >= 4)
	{
		$selectlist = $db->query('SELECT * FROM group_name');
		$selectlistquest = $db->query('SELECT * FROM quest_list');
		
		if(isset($_POST['validate']))
		{
			if(!empty($_POST['title']) AND !empty($_POST['desc']) AND !empty($_POST['valid']) AND !empty($_POST['denied']))
			{
				if(empty($_POST['previous_quest']))
				{
					$questlist['id'] = 0;
				}
				elseif(!empty($_POST['previous_quest']))
				{
					$selectquestlist = $db->prepare('SELECT * FROM quest_list WHERE quest = ?');
					$selectquestlist->execute(array($_POST['previous_quest']));
					$questlist = $selectquestlist->fetch();
				}
				
				if($_POST['answer'] == 0)
				{
					$insertquest = $db->prepare('INSERT INTO quest_list VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
					if($_POST['general'] == 0)
					{
						$insertquest->execute(array(NULL, $_POST['title'], $_POST['desc'], $_POST['answer'], '0', $_POST['rank'], $_SESSION['id'], $_POST['rp'], $_POST['valid'], $_POST['denied'], '0', '0', $questlist['id']));
					}
					elseif($_POST['general'] == 1)
					{
						$selectgroup = $db->prepare('SELECT * FROM group_name WHERE name = ?');
						$selectgroup->execute(array($_POST['group']));
						$group = $selectgroup->fetch();
						$insertquest->execute(array(NULL, $_POST['title'], $_POST['desc'], $_POST['answer'], $group['id'], $_POST['rank'], $_SESSION['id'], $_POST['rp'], $_POST['valid'], $_POST['denied'], '0', '0', $questlist['id']));
					}
					
					$imgid = $db->prepare('SELECT * FROM quest_list WHERE quest = ?');
					$imgid->execute(array($_POST['title']));
					$idimg = $imgid->fetch();
					
					if ($_FILES['send_img']['error'] == 0)
					{
						if ($_FILES['send_img']['size'] <= 10000000)
						{
							$info_img = pathinfo($_FILES['send_img']['name']);
							$ext_img = $info_img['extension'];
							$ext_ok = array('png');
													
							if (in_array($ext_img, $ext_ok))
							{
								$name = "pics/quest/quest_".$idimg['id'].".png";
								$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
								if ($_FILES['send_answer']['error'] == 0)
								{
									if ($_FILES['send_answer']['size'] <= 10000000)
									{
										$info_img = pathinfo($_FILES['send_answer']['name']);
										$ext_img = $info_img['extension'];
										$ext_ok = array('png');
																
										if (in_array($ext_img, $ext_ok))
										{
											$name = "pics/questattendu/quest_".$idimg['id'].".png";
											$finish = move_uploaded_file($_FILES['send_answer']['tmp_name'], $name);
											header('Location: index.php?p=questlist');
										} else {
											$erreur = "L'image n'est pas au Format PNG !";
										}
									}
								}
							} else {
								$erreur = "L'image n'est pas au Format PNG !";
							}
						}
					}
				}
				elseif($_POST['answer'] == 1)
				{
					$insertquest = $db->prepare('INSERT INTO quest_list VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
					if($_POST['general'] == 0)
					{
						$insertquest->execute(array(NULL, $_POST['title'], $_POST['desc'], $_POST['answer'], '0', $_POST['rank'], $_SESSION['id'], $_POST['rp'], $_POST['valid'], $_POST['denied'], $_POST['textatt'], '0', $questlist['id']));
					}
					elseif($_POST['general'] == 1)
					{
						$selectgroup = $db->prepare('SELECT * FROM group_name WHERE name = ?');
						$selectgroup->execute(array($_POST['group']));
						$group = $selectgroup->fetch();
						$insertquest->execute(array(NULL, $_POST['title'], $_POST['desc'], $_POST['answer'], $group['id'], $_POST['rank'], $_SESSION['id'], $_POST['rp'], $_POST['valid'], $_POST['denied'], $_POST['textatt'], '0', $questlist['id']));
					}
					
					$imgid = $db->prepare('SELECT * FROM quest_list WHERE quest = ?');
					$imgid->execute(array($_POST['title']));
					$idimg = $imgid->fetch();
					
					if ($_FILES['send_img']['error'] == 0)
					{
						if ($_FILES['send_img']['size'] <= 10000000)
						{
							$info_img = pathinfo($_FILES['send_img']['name']);
							$ext_img = $info_img['extension'];
							$ext_ok = array('png');
													
							if (in_array($ext_img, $ext_ok))
							{
								$name = "pics/quest/quest_".$idimg['id'].".png";
								$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
								header('Location: index.php?p=questlist');
							} else {
								$erreur = "L'image n'est pas au Format PNG !";
							}
						}
					}
				}
			} else {
				$erreur = "Vous n'avez pas remplis tout les champs... !";
			}
		}
		?>
		<form method="POST" enctype="multipart/form-data" action="index.php?p=questcrea">
			<table class="mptable" width="95%">
				<tbody>
					<tr>
						<td colspan="2" align="center" valign="middle">
							<input type="text" name="title" placeholder="Nom de la Quête">
						</td>
					</tr>
					<tr>
						<td align="center" valign="middle">
							Image de Quête <span style="color: darkred;font-size: 10px">(1920*1018)</span>
							<br>
							<input type="file" name="send_img" id="img">
						</td>
						<td align="center" valign="middle">
							Quête Précédente
							<br>
							<input list="quests" name="previous_quest" placeholder="Vide si pas suite">
							
							<datalist id="quests">
								<?php
								while($listquest = $selectlistquest->fetch())
								{
									?>
									<option value="<?=$listquest['quest']?>">
									<?php
								}
								?>
							</datalist>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center" valign="middle">
							<textarea style="width: 60%;min-height: 150px" type="text" name="desc" placeholder="Description de Quête"></textarea>
						</td>
					</tr>
					<tr>
						<td align="center" valign="middle">
							<textarea style="width: 60%;min-height: 150px" type="text" name="valid" placeholder="Si validé"></textarea>
						</td>
						<td align="center" valign="middle">
							<textarea style="width: 60%;min-height: 150px" type="text" name="denied" placeholder="Si Refusé"></textarea>
						</td>
					</tr>
					<tr>
						<td align="center" valign="middle">
							<input type="radio" name="rp" value="0" checked> HRP
							<input type="radio" name="rp" value="1"> RP
						</td>
						<td align="center" valign="middle">
							<input type="radio" name="general" value="0" checked> Général
							<input type="radio" name="general" value="1"> Groupe
							<br>
							<input list="groups" name="group" placeholder="Groupe">
							
							<datalist id="groups">
								<?php
								while($list = $selectlist->fetch())
								{
									?>
									<option value="<?=$list['name']?>">
									<?php
								}
								?>
							</datalist>
						</td>
					</tr>
					<tr>
						<td align="center" valign="middle">
							<input type="radio" name="rank" value="4" checked> Cadre
							<?php
							if($_SESSION['rank'] >= 5)
							{
								?>
								<input type="radio" name="rank" value="5"> MJ
								<?php
							}
							if($_SESSION['rank'] >= 6)
							{
								?>
								<input type="radio" name="rank" value="6"> MJS
								<?php
							}
							?>
						</td>
						<td align="center" valign="middle">
							<input type="radio" name="answer" value="0" checked> Screen
							<input type="radio" name="answer" value="1"> Texte
						</td>
					</tr>
					<tr>
						<td align="center" valign="middle">
							<input type="text" name="textatt" placeholder="Réponse attendue">
						</td>
						<td align="center" valign="middle">
							Screen Attendu <span style="color: darkred;font-size: 10px">(1920*1018)</span>
							<br>
							<input type="file" name="send_answer" id="answer">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center" valign="middle">
							<input type="submit" name="validate" value="Envoyer la Quête">
							<br>
							<span style="color: red"><?=$erreur?></span>
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
		<span class="username-detail" style="color: darkred;">
			Vous n'avez pas l'autorisation d'être ici...
		</span>
		<?php
	}
}
?>