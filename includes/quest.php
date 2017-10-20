<?php function quest()
{
	global $db, $_SESSION, $_POST, $_GET;
	
	if($_SESSION['rank'] >= 1)
	{
		?>
		<a class="username-detail" <?php if(isset($_GET['section'])) { ?> style="font-weight: bold; color: #808080" onmouseout="this.style.color='#808080'" onmouseover="this.style.color='white'" <?php } else { ?> style="font-weight: bold; color: #FFD700" onmouseout="this.style.color='#FFD700'" onmouseover="this.style.color='#FFA500'" <?php } ?> href="index.php?p=quest">
			Quêtes Générales
		</a>
		<?php
		if($_SESSION['rank'] >= 4)
		{
			?> | <a class="username-detail" <?php if($_GET['section'] != "staff") { ?> style="font-weight: bold; color: #808080" onmouseout="this.style.color='#808080'" onmouseover="this.style.color='white'" <?php } else { ?> style="font-weight: bold; color: #FFD700" onmouseout="this.style.color='#FFD700'" onmouseover="this.style.color='#FFA500'" <?php } ?> href="index.php?p=quest&section=staff">
					Validation des Quêtes <?php
					$countquest = $db->query('SELECT * FROM quest_get WHERE valid = 2');
					$countquest2 = $countquest->rowCount();
					if($countquest2 != 0)
					{
						?>
						<span style="color: red">[<?=$countquest2?>]</span>
						<?php
					}
					?>
				</a>
			<?php
		}
		?>
		<?php
		if($_SESSION['rank'] >= 5)
		{
			$selectgroup = $db->query('SELECT * FROM group_name ORDER BY guild DESC, name ASC');
			
			while($groupuser = $selectgroup->fetch())
			{
				?> | <a class="username-detail" <?php if($_GET['group'] != $groupuser['id']) { ?> style="font-weight: bold; color: #808080" onmouseout="this.style.color='#808080'" onmouseover="this.style.color='white'" <?php } else { ?> style="font-weight: bold; color: #FFD700" onmouseout="this.style.color='#FFD700'" onmouseover="this.style.color='#FFA500'" <?php } ?> href="index.php?p=quest&section=group&group=<?=$groupuser['id']?>">
					<?=$groupuser['name']?>
				</a>
				<?php
			}
		}
		else
		{
			$selectgroup = $db->prepare('SELECT gm.group_id, gm.user_id, gn.id, gn.name, gn.guild FROM group_members gm RIGHT JOIN group_name gn ON gn.id = gm.group_id WHERE gm.user_id = ? ORDER BY gn.guild DESC, gn.name ASC');
			$selectgroup->execute(array($_SESSION['id']));
			
			while($groupuser = $selectgroup->fetch())
			{
				?> | <a class="username-detail" <?php if($_GET['group'] != $groupuser['group_id']) { ?> style="font-weight: bold; color: #808080" onmouseout="this.style.color='#808080'" onmouseover="this.style.color='white'" <?php } else { ?> style="font-weight: bold; color: #FFD700" onmouseout="this.style.color='#FFD700'" onmouseover="this.style.color='#FFA500'" <?php } ?> href="index.php?p=quest&section=group&group=<?=$groupuser['group_id']?>">
					<?=$groupuser['name']?>
				</a>
				<?php
			}
		}
		?>
		<br>
		<br>
		<?php
		if(!isset($_GET['section']))
		{
			$selectquest = $db->query('SELECT * FROM quest_list WHERE groupacess = 0 AND validmj > 0 ORDER BY id ASC');
			
			if(isset($_GET['acp']) AND $_GET['acp'] == 1)
			{
				$acpadd = $db->prepare('INSERT INTO quest_get VALUES(?, ?, ?, ?, ?, ?)');
				$acpadd->execute(array(NULL, $_GET['onload'], $_SESSION['id'], '1', '0', '0'));
				header('Location: index.php?p=quest&onload='.$_GET['onload'].'');
			}
			
			while($questdiv = $selectquest->fetch())
			{
				if(isset($_POST['answersubmit']))
				{
					if(!empty($_POST['answertext']))
					{
						$acpvalid = $db->prepare('UPDATE quest_get SET valid = 1, text = ? WHERE idquest = ? AND idplayer = ?');
						$acpvalid->execute(array($_POST['answertext'], $_GET['onload'], $_SESSION['id']));
						header('Location: index.php?p=quest&onload='.$_GET['onload'].'');
					}
				}
				
				if(isset($_POST['sendnew']))
				{
						if (isset($_FILES['send_img']))
						{
							if ($_FILES['send_img']['error'] == 0)
							{
								if ($_FILES['send_img']['size'] <= 10000000)
								{
									$info_img = pathinfo($_FILES['send_img']['name']);
									$ext_img = $info_img['extension'];
									$ext_ok = array('png');
													
									if (in_array($ext_img, $ext_ok))
									{
										$name = "pics/questanswer/answer_".$_SESSION['id']."_".$_GET['onload'].".png";
										$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
										$acpvalid = $db->prepare('UPDATE quest_get SET valid = 2 WHERE idquest = ? AND idplayer = ?');
										$acpvalid->execute(array($_GET['onload'], $_SESSION['id']));
										header('Location: index.php?p=quest&onload='.$_GET['onload'].'');
									} else {
										$erreur = "L'image n'est pas au Format PNG !";
									}
								}
							}
						}
				}
				
				if(isset($_GET['acp']) AND $_GET['acp'] == 2)
				{
					$acpdel = $db->prepare('DELETE FROM quest_get WHERE idquest = ? AND idplayer = ?');
					$acpdel->execute(array($_GET['onload'], $_SESSION['id']));
					header('Location: index.php?p=quest&onload='.$_GET['onload'].'');
				}
				
				if(isset($_GET['rcm']) AND $_GET['rcm'] == 1)
				{
					$rcmadd = $db->prepare('UPDATE quest_get SET valid = 1 WHERE idquest = ? AND idplayer = ?');
					$rcmadd->execute(array($_GET['onload'], $_SESSION['id']));
					header('Location: index.php?p=quest&onload='.$_GET['onload'].'');
				}
				
				if(isset($_GET['reboot']) AND $_GET['reboot'] == 1)
				{
					$rcmdel = $db->prepare('DELETE FROM quest_get WHERE idquest = ? AND idplayer = ?');
					$rcmdel->execute(array($_GET['onload'], $_SESSION['id']));
					header('Location: index.php?p=quest&onload='.$_GET['onload'].'');
				}
			
				$selectvalid = $db->prepare('SELECT * FROM quest_get WHERE idplayer = ? AND idquest = ?');
				$selectvalid->execute(array($_SESSION['id'], $questdiv['id']));
				$valid = $selectvalid->fetch();
				
				$selectmember = $db->prepare('SELECT * FROM member_list WHERE id = ?');
				$selectmember->execute(array($questdiv['author']));
				$member = $selectmember->fetch();
				
				switch ($member['rank'])
				{
					default : $color = "#555550"; break;
					case 1:  $color = "#00AA00"; $color = ($member['actif'] == 1)? "#FF5555" : $color;
					$color = ($member['digni'] == 3)? "#5555FF" : $color; break;
					case 2: $color = "#55FF55"; $color = ($member['actif'] == 1)? "#FF5555" : $color;
					$color = ($member['digni'] == 3)? "#5555FF" : $color; break;
					case 3: $color = "#FF55FF"; break;
					case 4: $color = "#00AAAA"; $color = ($member['pnj'] == 1)? "#AAAAAA" : $color;
					$color = ($member['pnj'] == 3)? "#55FFFF" : $color;
					$color = ($member['digni'] == 2)? "#FFFF55" : $color; break;
					case 5: $color = "#FFAA00"; $color = ($member['pnj'] == 2)? "#0200A6" : $color;
					$color = ($member['digni'] == 1)? "#AA00AA" : $color; break;
					case 6: $color = "#AA0000"; break;
					case 7: $color = "#000000"; break;
				}
		
				if ($member['pnj'] == 2)
				{
				$stylebasic = false;
				$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
				}
				else
				{
				$stylebasic = "text-shadow: 2px 2px 2px #000000;";
				$styledieu = false;
				}
				
				$selectvalid2 = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ?');
				$selectvalid2->execute(array($_GET['onload'], $_SESSION['id']));
				$valid2 = $selectvalid2->fetch();
				
				$selectmember2 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
				$selectmember2->execute(array($valid2['validator']));
				$member2 = $selectmember2->fetch();
				
				switch ($member2['rank'])
				{
					default : $color2 = "#555550"; break;
					case 1:  $color2 = "#00AA00"; $color2 = ($member2['actif'] == 1)? "#FF5555" : $color2;
					$color2 = ($member2['digni'] == 3)? "#5555FF" : $color2; break;
					case 2: $color2 = "#55FF55"; $color2 = ($member2['actif'] == 1)? "#FF5555" : $color2;
					$color2 = ($member2['digni'] == 3)? "#5555FF" : $color2; break;
					case 3: $color2 = "#FF55FF"; break;
					case 4: $color2 = "#00AAAA"; $color2 = ($member2['pnj'] == 1)? "#AAAAAA" : $color2;
					$color2 = ($member2['pnj'] == 3)? "#55FFFF" : $color2;
					$color2 = ($member2['digni'] == 2)? "#FFFF55" : $color2; break;
					case 5: $color2 = "#FFAA00"; $color2 = ($member2['pnj'] == 2)? "#0200A6" : $color2;
					$color2 = ($member2['digni'] == 1)? "#AA00AA" : $color2; break;
					case 6: $color2 = "#AA0000"; break;
					case 7: $color2 = "#000000"; break;
				}
		
				if ($member2['pnj'] == 2)
				{
				$stylebasic2 = false;
				$styledieu2 = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
				}
				else
				{
				$stylebasic2 = "text-shadow: 2px 2px 2px #000000;";
				$styledieu2 = false;
				}
				
				$desc = preg_replace('#\n#', '<br>', $questdiv['desc']);
				$ifvalid = preg_replace('#\n#', '<br>', $questdiv['ifvalid']);
				$ifrefused = preg_replace('#\n#', '<br>', $questdiv['ifrefused']);
				
				if($questdiv['previous_quest'] == 0)
				{
					if($_GET['onload'] != $questdiv['id'])
					{
						if($valid['valid'] <= 2)
						{
							?>
							<div valign="middle" class="nav" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 3)
						{
							?>
							<div valign="middle" class="navrefused" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 4)
						{
							?>
							<div valign="middle" class="navvalid" align="center" width="95%">
							<?php
						}
						?>
							<ul>
								<li>
									<?php
									if($valid['valid'] <= 2)
									{
										?>
										<div valign="middle" class="link" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 4)
									{
										?>
										<div valign="middle" class="linkvalid" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 3)
									{
										?>
										<div valign="middle" class="linkrefused" style="padding: 20px;">
										<?php
									}
									?>
										<table style="border-collapse: separate;border-spacing: 2px;" width="100%">
											<tbody>
												<tr>
													<td align="left">
														<?php
														if($questdiv['rp'] == 0)
														{
															?>
															<span valign="middle" class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
															<?php
														}
														elseif($questdiv['rp'] == 1)
														{
															?>
															<span valign="middle" class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
															<?php
														}
														?>
														<a valign="middle" href="index.php?p=quest&onload=<?=$questdiv['id']?>">
															<?=$questdiv['quest']?>
														</a>
													</td>
													<td width="300px" align="right">
														<img valign="middle" style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;" width="300px" src="pics/quest/quest_<?=$questdiv['id']?>.png">
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</li>
							</ul>
						</div>
						<br>
						<?php
					}
					elseif($_GET['onload'] == $questdiv['id'])
					{
						if($valid['valid'] <= 2)
						{
							?>
							<div class="nav" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 3)
						{
							?>
							<div class="navrefused" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 4)
						{
							?>
							<div class="navvalid" align="center" width="95%">
							<?php
						}
						?>
							<ul>
								<li>
									<?php
									$selectanswer = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ?');
									$selectanswer->execute(array($_GET['onload'], $_SESSION['id']));
									$questanswer = $selectanswer->fetch();
									if($valid['valid'] <= 2)
									{
										?>
										<div class="link" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 4)
									{
										?>
										<div class="linkvalid" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 3)
									{
										?>
										<div class="linkrefused" style="padding: 20px;">
										<?php
									}
									?>
											<?php
											if($questdiv['rp'] == 0)
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
												<?php
											}
											elseif($questdiv['rp'] == 1)
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
												<?php
											}
											?>
											<a href="index.php?p=quest">
												<?=$questdiv['quest']?>
											</a>
											<br>
											Initiée par: 
											<?php
											if (file_exists("pics/MiniImage/user_".$member['id'].".png"))
											{
											?>
												<img valign="center" src="pics/MiniImage/user_<?= $member['id']?>.png" alt="" width="20px">
											<?php
											}
											?> <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $member['username']?> <?= $member['nom']?></span>
											<br>
											<br>
											<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/quest/quest_<?=$questdiv['id']?>.png">
											<br>
											<br>
											Description:
											<br>
											<br>
											<?=$desc?>
											<br>
											<br>
											<?php
											if($valid['valid'] == 3)
											{
												?>
												Refusé par:
												<?php
												if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
												<br>
												<br>
												<?=$ifrefused?>
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
												<?php
												}
												?>
												<br>
												<br>
												<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&rcm=1"><span class="username-detail" style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[Recommencer]</span></a>
												<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&reboot=1"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner]</span></a>
												<?php
											}
											elseif($valid['valid'] == 4)
											{
												?>
												Validé par:
												<?php
												if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
												<br>
												<br>
												<?=$ifvalid?>
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
												<?php
												}
											}
											elseif($valid['valid'] == 2)
											{
												?>
												En cours de Validation !
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
												<?php
												}
											}
											elseif($valid['valid'] == 1)
											{
												if($questdiv['answer'] == 0)
												{
												?>
												<p>
													Vous devez entrer le screen demandé ici !
												</p>
												<form method="POST" enctype="multipart/form-data" action="index.php?p=quest&onload=<?=$_GET['onload']?>">
													<input type="file" name="send_img" id="img"> <input type="submit" style="text-align:right;" name="sendnew" value="Envoyer">
												</form>
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<p>
													Vous devez entrer le texte attendu !
												</p>
												<form method="POST">
													<input type="text" name="answertext" placeholder="Texte attendu">
													<input type="submit" name="answersubmit" value="Envoyer la Réponse !">
												</form>
												<?php
												}
												?>
												<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&acp=2"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner la Quête...]</span></a>
												<?php
											}
											elseif(empty($valid['valid']))
											{
												?>
												<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&acp=1"><span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[Accepter la Quête !]</span></a>
												<?php
											}
											?>
									</div>
								</li>
							</ul>
						</div>
						<br>
						<?php
					}
				}
				elseif($questdiv['previous_quest'] >= 1)
				{
					$selectprevious = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ? AND valid = 4');
					$selectprevious->execute(array($questdiv['previous_quest'], $_SESSION['id']));
					$previousquest = $selectprevious->rowCount();
					
					if($previousquest >= 1)
					{
						if($_GET['onload'] != $questdiv['id'])
						{
							if($valid['valid'] <= 2)
							{
								?>
								<div valign="middle" class="nav" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 3)
							{
								?>
								<div valign="middle" class="navrefused" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 4)
							{
								?>
								<div valign="middle" class="navvalid" align="center" width="95%">
								<?php
							}
							?>
								<ul>
									<li>
										<?php
										if($valid['valid'] <= 2)
										{
											?>
											<div valign="middle" class="link" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 4)
										{
											?>
											<div valign="middle" class="linkvalid" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 3)
										{
											?>
											<div valign="middle" class="linkrefused" style="padding: 20px;">
											<?php
										}
										?>
											<table style="border-collapse: separate;border-spacing: 2px;" width="100%">
												<tbody>
													<tr>
														<td align="left">
															<?php
															if($questdiv['rp'] == 0)
															{
																?>
																<span valign="middle" class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
																<?php
															}
															elseif($questdiv['rp'] == 1)
															{
																?>
																<span valign="middle" class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
																<?php
															}
															?>
															<a valign="middle" href="index.php?p=quest&onload=<?=$questdiv['id']?>">
																<?=$questdiv['quest']?>
															</a>
														</td>
														<td width="300px" align="right">
															<img valign="middle" style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;" width="300px" src="pics/quest/quest_<?=$questdiv['id']?>.png">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
							<br>
							<?php
						}
						elseif($_GET['onload'] == $questdiv['id'])
						{
							if($valid['valid'] <= 2)
							{
								?>
								<div class="nav" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 3)
							{
								?>
								<div class="navrefused" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 4)
							{
								?>
								<div class="navvalid" align="center" width="95%">
								<?php
							}
							?>
								<ul>
									<li>
										<?php
										$selectanswer = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ?');
										$selectanswer->execute(array($_GET['onload'], $_SESSION['id']));
										$questanswer = $selectanswer->fetch();
										if($valid['valid'] <= 2)
										{
											?>
											<div class="link" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 4)
										{
											?>
											<div class="linkvalid" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 3)
										{
											?>
											<div class="linkrefused" style="padding: 20px;">
											<?php
										}
										?>
												<?php
												if($questdiv['rp'] == 0)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
													<?php
												}
												elseif($questdiv['rp'] == 1)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
													<?php
												}
												?>
												<a href="index.php?p=quest">
													<?=$questdiv['quest']?>
												</a>
												<br>
												Initiée par: 
												<?php
												if (file_exists("pics/MiniImage/user_".$member['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $member['username']?> <?= $member['nom']?></span>
												<br>
												<br>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/quest/quest_<?=$questdiv['id']?>.png">
												<br>
												<br>
												Description:
												<br>
												<br>
												<?=$desc?>
												<br>
												<br>
												<?php
												if($valid['valid'] == 3)
												{
													?>
													Refusé par:
													<?php
													if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
													{
													?>
														<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
													<?php
													}
													?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
													<br>
													<br>
													<?=$ifrefused?>
													<br>
													<br>
													<?php
													if($questdiv['answer'] == 0)
													{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
													<?php
													}
													?>
													<br>
													<br>
													<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&rcm=1"><span class="username-detail" style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[Recommencer]</span></a>
													<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&reboot=1"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner]</span></a>
													<?php
												}
												elseif($valid['valid'] == 4)
												{
													?>
													Validé par:
													<?php
													if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
													{
													?>
														<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
													<?php
													}
													?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
													<br>
													<br>
													<?=$ifvalid?>
													<br>
													<br>
													<?php
													if($questdiv['answer'] == 0)
													{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
													<?php
													}
												}
												elseif($valid['valid'] == 2)
												{
													?>
													En cours de Validation !
													<br>
													<br>
													<?php
													if($questdiv['answer'] == 0)
													{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
													<?php
													}
												}
												elseif($valid['valid'] == 1)
												{
													if($questdiv['answer'] == 0)
													{
													?>
													<p>
														Vous devez entrer le screen demandé ici !
													</p>
													<form method="POST" enctype="multipart/form-data" action="index.php?p=quest&onload=<?=$_GET['onload']?>">
														<input type="file" name="send_img" id="img"> <input type="submit" style="text-align:right;" name="sendnew" value="Envoyer">
													</form>
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<p>
														Vous devez entrer le texte attendu !
													</p>
													<form method="POST">
														<input type="text" name="answertext" placeholder="Texte attendu">
														<input type="submit" name="answersubmit" value="Envoyer la Réponse !">
													</form>
													<?php
													}
													?>
													<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&acp=2"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner la Quête...]</span></a>
													<?php
												}
												elseif(empty($valid['valid']))
												{
													?>
													<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&acp=1"><span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[Accepter la Quête !]</span></a>
													<?php
												}
												?>
										</div>
									</li>
								</ul>
							</div>
							<br>
							<?php
						}
					}
				}
			}
		}
		elseif($_GET['section'] == "staff")
		{
			if($_SESSION['rank'] >= 4)
			{
				$selectquest = $db->query('SELECT qg.id, qg.idquest, qg.idplayer, qg.valid, qg.text, ql.id AS ql_id, ql.quest, ql.desc, ql.answer, ql.groupacess, ql.rankvalid, ql.author, ql.rp, ql.validmj FROM quest_get qg RIGHT JOIN quest_list ql ON qg.idquest = ql.id WHERE qg.valid = 2 AND ql.validmj > 0 ORDER BY ql.id ASC');
				
				while($questdiv = $selectquest->fetch())
				{
					if(isset($_GET['validate']))
					{
						$ifvalid = $db->prepare('UPDATE quest_get SET valid = 4, validator = ? WHERE id = ?');
						$ifvalid->execute(array($_SESSION['id'], $_GET['onload']));
						header('Location: index.php?p=quest&section=staff&onload='.$_GET['onload'].'');
					}
				
					if(isset($_GET['refused']))
					{
						$ifrefused = $db->prepare('UPDATE quest_get SET valid = 3, validator = ? WHERE id = ?');
						$ifrefused->execute(array($_SESSION['id'], $_GET['onload']));
						header('Location: index.php?p=quest&section=staff&onload='.$_GET['onload'].'');
					}
				
					$selectmember = $db->prepare('SELECT * FROM member_list WHERE id = ?');
					$selectmember->execute(array($questdiv['idplayer']));
					$member = $selectmember->fetch();
					
					switch ($member['rank'])
					{
						default : $color = "#555550"; break;
						case 1:  $color = "#00AA00"; $color = ($member['actif'] == 1)? "#FF5555" : $color;
						$color = ($member['digni'] == 3)? "#5555FF" : $color; break;
						case 2: $color = "#55FF55"; $color = ($member['actif'] == 1)? "#FF5555" : $color;
						$color = ($member['digni'] == 3)? "#5555FF" : $color; break;
						case 3: $color = "#FF55FF"; break;
						case 4: $color = "#00AAAA"; $color = ($member['pnj'] == 1)? "#AAAAAA" : $color;
						$color = ($member['pnj'] == 3)? "#55FFFF" : $color;
						$color = ($member['digni'] == 2)? "#FFFF55" : $color; break;
						case 5: $color = "#FFAA00"; $color = ($member['pnj'] == 2)? "#0200A6" : $color;
						$color = ($member['digni'] == 1)? "#AA00AA" : $color; break;
						case 6: $color = "#AA0000"; break;
						case 7: $color = "#000000"; break;
					}
			
					if ($member['pnj'] == 2)
					{
					$stylebasic = false;
					$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
					}
					else
					{
					$stylebasic = "text-shadow: 2px 2px 2px #000000;";
					$styledieu = false;
					}
					
					$desc = preg_replace('#\n#', '<br>', $questdiv['desc']);
					
					if($_SESSION['rank'] >= $questdiv['rankvalid'])
					{
						if($_GET['onload'] != $questdiv['id'])
						{
							?>
							<div valign="middle" class="nav" align="center" width="95%">
								<ul>
									<li>
										<div valign="middle" class="link" style="padding: 20px;">
											<table style="border-collapse: separate;border-spacing: 2px;" width="100%">
												<tbody>
													<tr>
														<td align="left">
															<?php
															if($questdiv['rp'] == 0)
															{
																?>
																<span valign="middle" class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP] </span>
																<?php
															}
															elseif($questdiv['rp'] == 1)
															{
																?>
																<span valign="middle" class="username-detail" style="font-weight: bold; color: #55FF55">[RP] </span>
																<?php
															}
															?>
															<a valign="middle" href="index.php?p=quest&section=staff&onload=<?=$questdiv['id']?>">
																<?=$questdiv['quest']?>
															</a>
														</td>
														<td width="300px" align="right">
															<img valign="middle" style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;" width="300px" src="pics/quest/quest_<?=$questdiv['idquest']?>.png">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
							<br>
							<?php
						}
						elseif($_GET['onload'] == $questdiv['id'])
						{
							?>
							<div class="nav" align="center" width="95%">
								<ul>
									<li>
										<div class="link" style="padding: 20px;">
												<?php
												if($questdiv['rp'] == 0)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP] </span>
													<?php
												}
												elseif($questdiv['rp'] == 1)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP] </span>
													<?php
												}
												?>
												<a href="index.php?p=quest&section=staff">
													<?=$questdiv['quest']?>
												</a>
												<br>
												Remplie par: 
												<?php
												if (file_exists("pics/MiniImage/user_".$member['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $member['username']?> <?= $member['nom']?></span>
												<br>
												<br>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/quest/quest_<?=$questdiv['idquest']?>.png">
												<br>
												<br>
												Description:
												<br>
												<br>
												<?=$desc?>
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$questdiv['idplayer']?>_<?=$questdiv['idquest']?>.png">
													<?php
												}
												elseif($questdiv['answer'] == 1)
												{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;">
														<?=$questdiv['text']?>
													</span>
													<?php
												}
												?>
												<br>
												<br>
												<a href="index.php?p=quest&section=staff&onload=<?=$_GET['onload']?>&validate=1"><span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[Valider !]</span></a>
												<a href="index.php?p=quest&section=staff&onload=<?=$_GET['onload']?>&refused=1"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Refuser...]</span></a>
										</div>
									</li>
								</ul>
							</div>
							<br>
							<?php
						}
					}
				}
			}
		}
		elseif($_GET['section'] == "group")
		{
			$selectquest = $db->prepare('SELECT * FROM quest_list WHERE groupacess = ? AND validmj > 0 ORDER BY id ASC');
			$selectquest->execute(array($_GET['group']));
			
			if(isset($_GET['acp']) AND $_GET['acp'] == 1)
				{
					$acpadd = $db->prepare('INSERT INTO quest_get VALUES(?, ?, ?, ?, ?, ?)');
					$acpadd->execute(array(NULL, $_GET['onload'], $_SESSION['id'], '1', '0', '0'));
					header('Location: index.php?p=quest&section=group&group='.$_GET['group'].'&onload='.$_GET['onload'].'');
				}
			
			while($questdiv = $selectquest->fetch())
			{
				
				if(isset($_POST['answersubmit']))
				{
					if(!empty($_POST['answertext']))
					{
						$acpvalid = $db->prepare('UPDATE quest_get SET valid = 1, text = ? WHERE idquest = ? AND idplayer = ?');
						$acpvalid->execute(array($_POST['answertext'], $_GET['onload'], $_SESSION['id']));
						header('Location: index.php?p=quest&section=group&group='.$_GET['group'].'&onload='.$_GET['onload'].'');
					}
				}
				elseif(isset($_POST['sendnew']))
				{
						if (isset($_FILES['send_img']))
						{
							if ($_FILES['send_img']['error'] == 0)
							{
								if ($_FILES['send_img']['size'] <= 10000000)
								{
									$info_img = pathinfo($_FILES['send_img']['name']);
									$ext_img = $info_img['extension'];
									$ext_ok = array('png');
													
									if (in_array($ext_img, $ext_ok))
									{
										$name = "pics/questanswer/answer_".$_SESSION['id']."_".$_GET['onload'].".png";
										$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
										$acpvalid = $db->prepare('UPDATE quest_get SET valid = 2 WHERE idquest = ? AND idplayer = ?');
										$acpvalid->execute(array($_GET['onload'], $_SESSION['id']));
										header('Location: index.php?p=quest&section=group&group='.$_GET['group'].'&onload='.$_GET['onload'].'');
									} else {
										$erreur = "L'image n'est pas au Format PNG !";
									}
								}
							}
						}
				}
				
				if(isset($_GET['acp']) AND $_GET['acp'] == 2)
				{
					$acpdel = $db->prepare('DELETE FROM quest_get WHERE idquest = ? AND idplayer = ?');
					$acpdel->execute(array($_GET['onload'], $_SESSION['id']));
					header('Location: index.php?p=quest&section=group&group='.$_GET['group'].'&onload='.$_GET['onload'].'');
				}
				
				if(isset($_GET['rcm']) AND $_GET['rcm'] == 1)
				{
					$acpadd = $db->prepare('UPDATE quest_get SET valid = 1 WHERE idquest = ? AND idplayer = ?');
					$acpadd->execute(array($_GET['onload'], $_SESSION['id']));
					header('Location: index.php?p=quest&section=group&group='.$_GET['group'].'&onload='.$_GET['onload'].'');
				}
				
				if(isset($_GET['reboot']) AND $_GET['reboot'] == 1)
				{
					$acpdel = $db->prepare('DELETE FROM quest_get WHERE idquest = ? AND idplayer = ?');
					$acpdel->execute(array($_GET['onload'], $_SESSION['id']));
					header('Location: index.php?p=quest&section=group&group='.$_GET['group'].'&onload='.$_GET['onload'].'');
				}
			
				$selectvalid = $db->prepare('SELECT * FROM quest_get WHERE idplayer = ? AND idquest = ?');
				$selectvalid->execute(array($_SESSION['id'], $questdiv['id']));
				$valid = $selectvalid->fetch();
				
				$selectmember = $db->prepare('SELECT * FROM member_list WHERE id = ?');
				$selectmember->execute(array($questdiv['author']));
				$member = $selectmember->fetch();
				
				switch ($member['rank'])
				{
					default : $color = "#555550"; break;
					case 1:  $color = "#00AA00"; $color = ($member['actif'] == 1)? "#FF5555" : $color;
					$color = ($member['digni'] == 3)? "#5555FF" : $color; break;
					case 2: $color = "#55FF55"; $color = ($member['actif'] == 1)? "#FF5555" : $color;
					$color = ($member['digni'] == 3)? "#5555FF" : $color; break;
					case 3: $color = "#FF55FF"; break;
					case 4: $color = "#00AAAA"; $color = ($member['pnj'] == 1)? "#AAAAAA" : $color;
					$color = ($member['pnj'] == 3)? "#55FFFF" : $color;
					$color = ($member['digni'] == 2)? "#FFFF55" : $color; break;
					case 5: $color = "#FFAA00"; $color = ($member['pnj'] == 2)? "#0200A6" : $color;
					$color = ($member['digni'] == 1)? "#AA00AA" : $color; break;
					case 6: $color = "#AA0000"; break;
					case 7: $color = "#000000"; break;
				}
		
				if ($member['pnj'] == 2)
				{
				$stylebasic = false;
				$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
				}
				else
				{
				$stylebasic = "text-shadow: 2px 2px 2px #000000;";
				$styledieu = false;
				}
				
				$selectvalid2 = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ?');
				$selectvalid2->execute(array($_GET['onload'], $_SESSION['id']));
				$valid2 = $selectvalid2->fetch();
				
				$selectmember2 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
				$selectmember2->execute(array($valid2['validator']));
				$member2 = $selectmember2->fetch();
				
				switch ($member2['rank'])
				{
					default : $color2 = "#555550"; break;
					case 1:  $color2 = "#00AA00"; $color2 = ($member2['actif'] == 1)? "#FF5555" : $color2;
					$color2 = ($member2['digni'] == 3)? "#5555FF" : $color2; break;
					case 2: $color2 = "#55FF55"; $color2 = ($member2['actif'] == 1)? "#FF5555" : $color2;
					$color2 = ($member2['digni'] == 3)? "#5555FF" : $color2; break;
					case 3: $color2 = "#FF55FF"; break;
					case 4: $color2 = "#00AAAA"; $color2 = ($member2['pnj'] == 1)? "#AAAAAA" : $color2;
					$color2 = ($member2['pnj'] == 3)? "#55FFFF" : $color2;
					$color2 = ($member2['digni'] == 2)? "#FFFF55" : $color2; break;
					case 5: $color2 = "#FFAA00"; $color2 = ($member2['pnj'] == 2)? "#0200A6" : $color2;
					$color2 = ($member2['digni'] == 1)? "#AA00AA" : $color2; break;
					case 6: $color2 = "#AA0000"; break;
					case 7: $color2 = "#000000"; break;
				}
		
				if ($member2['pnj'] == 2)
				{
				$stylebasic2 = false;
				$styledieu2 = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
				}
				else
				{
				$stylebasic2 = "text-shadow: 2px 2px 2px #000000;";
				$styledieu2 = false;
				}
				
				$desc = preg_replace('#\n#', '<br>', $questdiv['desc']);
				$ifvalid = preg_replace('#\n#', '<br>', $questdiv['ifvalid']);
				$ifrefused = preg_replace('#\n#', '<br>', $questdiv['ifrefused']);
				
				if($questdiv['previous_quest'] == 0)
				{
					if($_GET['onload'] != $questdiv['id'])
					{
						if($valid['valid'] <= 2)
						{
							?>
							<div valign="middle" class="nav" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 3)
						{
							?>
							<div valign="middle" class="navrefused" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 4)
						{
							?>
							<div valign="middle" class="navvalid" align="center" width="95%">
							<?php
						}
						?>
							<ul>
								<li>
									<?php
									if($valid['valid'] <= 2)
									{
										?>
										<div valign="middle" class="link" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 4)
									{
										?>
										<div valign="middle" class="linkvalid" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 3)
									{
										?>
										<div valign="middle" class="linkrefused" style="padding: 20px;">
										<?php
									}
									?>
										<table style="border-collapse: separate;border-spacing: 2px;" width="100%">
											<tbody>
												<tr>
													<td align="left">
														<?php
														if($questdiv['rp'] == 0)
														{
															?>
															<span valign="middle" class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
															<?php
														}
														elseif($questdiv['rp'] == 1)
														{
															?>
															<span valign="middle" class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
															<?php
														}
														?>
														<a valign="middle" href="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$questdiv['id']?>">
															<?=$questdiv['quest']?>
														</a>
													</td>
													<td width="300px" align="right">
														<img valign="middle" style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;" width="300px" src="pics/quest/quest_<?=$questdiv['id']?>.png">
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</li>
							</ul>
						</div>
						<br>
						<?php
					}
					elseif($_GET['onload'] == $questdiv['id'])
					{
						if($valid['valid'] <= 2)
						{
							?>
							<div class="nav" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 3)
						{
							?>
							<div class="navrefused" align="center" width="95%">
							<?php
						}
						elseif($valid['valid'] == 4)
						{
							?>
							<div class="navvalid" align="center" width="95%">
							<?php
						}
						?>
							<ul>
								<li>
									<?php
									$selectanswer = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ?');
									$selectanswer->execute(array($_GET['onload'], $_SESSION['id']));
									$questanswer = $selectanswer->fetch();
									if($valid['valid'] <= 2)
									{
										?>
										<div class="link" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 4)
									{
										?>
										<div class="linkvalid" style="padding: 20px;">
										<?php
									}
									elseif($valid['valid'] == 3)
									{
										?>
										<div class="linkrefused" style="padding: 20px;">
										<?php
									}
									?>
											<?php
											if($questdiv['rp'] == 0)
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
												<?php
											}
											elseif($questdiv['rp'] == 1)
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
												<?php
											}
											?>
											<a href="index.php?p=quest&section=group&group=<?=$_GET['group']?>">
												<?=$questdiv['quest']?>
											</a>
											<br>
											Initiée par: 
											<?php
											if (file_exists("pics/MiniImage/user_".$member['id'].".png"))
											{
											?>
												<img valign="center" src="pics/MiniImage/user_<?= $member['id']?>.png" alt="" width="20px">
											<?php
											}
											?> <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $member['username']?> <?= $member['nom']?></span>
											<br>
											<br>
											<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/quest/quest_<?=$questdiv['id']?>.png">
											<br>
											<br>
											Description:
											<br>
											<br>
											<?=$desc?>
											<br>
											<br>
											<?php
											if($valid['valid'] == 3)
											{
												?>
												Refusé par:
												<?php
												if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
												<br>
												<br>
												<?=$ifrefused?>
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
												<?php
												}
												?>
												<br>
												<br>
												<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&rcm=1"><span class="username-detail" style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[Recommencer]</span></a>
												<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&reboot=1"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner]</span></a>
												<?php
											}
											elseif($valid['valid'] == 4)
											{
												?>
												Validé par:
												<?php
												if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
												<br>
												<br>
												<?=$ifvalid?>
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
												<?php
												}
											}
											elseif($valid['valid'] == 2)
											{
												?>
												En cours de Validation !
												<br>
												<br>
												<?php
												if($questdiv['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
												<?php
												}
											}
											elseif($valid['valid'] == 1)
											{
												if($questdiv['answer'] == 0)
												{
												?>
												<p>
													Vous devez entrer le screen demandé ici !
												</p>
												<form method="POST" enctype="multipart/form-data" action="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$_GET['onload']?>">
													<input type="file" name="send_img" id="img"> <input type="submit" style="text-align:right;" name="sendnew" value="Envoyer">
												</form>
												<?php
												}
												elseif($questdiv['answer'] == 1)
												{
												?>
												<p>
													Vous devez entrer le texte attendu !
												</p>
												<form method="POST">
													<input type="text" name="answertext" placeholder="Texte attendu">
													<input type="submit" name="answersubmit" value="Envoyer la Réponse !">
												</form>
												<?php
												}
												?>
												<a href="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$_GET['onload']?>&acp=2"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner la Quête...]</span></a>
												<?php
											}
											elseif(empty($valid['valid']))
											{
												?>
												<a href="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$_GET['onload']?>&acp=1"><span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[Accepter la Quête !]</span></a>
												<?php
											}
											?>
									</div>
								</li>
							</ul>
						</div>
						<br>
						<?php
					}
				}
				elseif($questdiv['previous_quest'] >= 1)
				{
					$selectprevious = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ? AND valid = 4');
					$selectprevious->execute(array($questdiv['previous_quest'], $_SESSION['id']));
					$previousquest = $selectprevious->rowCount();
					
					if($previousquest >= 1)
					{
						if($_GET['onload'] != $questdiv['id'])
						{
							if($valid['valid'] <= 2)
							{
								?>
								<div valign="middle" class="nav" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 3)
							{
								?>
								<div valign="middle" class="navrefused" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 4)
							{
								?>
								<div valign="middle" class="navvalid" align="center" width="95%">
								<?php
							}
							?>
								<ul>
									<li>
										<?php
										if($valid['valid'] <= 2)
										{
											?>
											<div valign="middle" class="link" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 4)
										{
											?>
											<div valign="middle" class="linkvalid" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 3)
										{
											?>
											<div valign="middle" class="linkrefused" style="padding: 20px;">
											<?php
										}
										?>
											<table style="border-collapse: separate;border-spacing: 2px;" width="100%">
												<tbody>
													<tr>
														<td align="left">
															<?php
															if($questdiv['rp'] == 0)
															{
																?>
																<span valign="middle" class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
																<?php
															}
															elseif($questdiv['rp'] == 1)
															{
																?>
																<span valign="middle" class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
																<?php
															}
															?>
															<a valign="middle" href="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$questdiv['id']?>">
																<?=$questdiv['quest']?>
															</a>
														</td>
														<td width="300px" align="right">
															<img valign="middle" style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;" width="300px" src="pics/quest/quest_<?=$questdiv['id']?>.png">
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</li>
								</ul>
							</div>
							<br>
							<?php
						}
						elseif($_GET['onload'] == $questdiv['id'])
						{
							if($valid['valid'] <= 2)
							{
								?>
								<div class="nav" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 3)
							{
								?>
								<div class="navrefused" align="center" width="95%">
								<?php
							}
							elseif($valid['valid'] == 4)
							{
								?>
								<div class="navvalid" align="center" width="95%">
								<?php
							}
							?>
								<ul>
									<li>
										<?php
										$selectanswer = $db->prepare('SELECT * FROM quest_get WHERE idquest = ? AND idplayer = ?');
										$selectanswer->execute(array($_GET['onload'], $_SESSION['id']));
										$questanswer = $selectanswer->fetch();
										if($valid['valid'] <= 2)
										{
											?>
											<div class="link" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 4)
										{
											?>
											<div class="linkvalid" style="padding: 20px;">
											<?php
										}
										elseif($valid['valid'] == 3)
										{
											?>
											<div class="linkrefused" style="padding: 20px;">
											<?php
										}
										?>
												<?php
												if($questdiv['rp'] == 0)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
													<?php
												}
												elseif($questdiv['rp'] == 1)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
													<?php
												}
												?>
												<a href="index.php?p=quest&section=group&group=<?=$_GET['group']?>">
													<?=$questdiv['quest']?>
												</a>
												<br>
												Initiée par: 
												<?php
												if (file_exists("pics/MiniImage/user_".$member['id'].".png"))
												{
												?>
													<img valign="center" src="pics/MiniImage/user_<?= $member['id']?>.png" alt="" width="20px">
												<?php
												}
												?> <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $member['username']?> <?= $member['nom']?></span>
												<br>
												<br>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/quest/quest_<?=$questdiv['id']?>.png">
												<br>
												<br>
												Description:
												<br>
												<br>
												<?=$desc?>
												<br>
												<br>
												<?php
												if($valid['valid'] == 3)
												{
													?>
													Refusé par:
													<?php
													if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
													{
													?>
														<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
													<?php
													}
													?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
													<br>
													<br>
													<?=$ifrefused?>
													<br>
													<br>
													<?php
													if($questdiv['answer'] == 0)
													{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
													<?php
													}
													?>
													<br>
													<br>
													<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&rcm=1"><span class="username-detail" style="font-weight: bold; color: #55FFFF" onmouseout="this.style.color='#55FFFF'" onmouseover="this.style.color='white'">[Recommencer]</span></a>
													<a href="index.php?p=quest&onload=<?=$_GET['onload']?>&reboot=1"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner]</span></a>
													<?php
												}
												elseif($valid['valid'] == 4)
												{
													?>
													Validé par:
													<?php
													if (file_exists("pics/MiniImage/user_".$member2['id'].".png"))
													{
													?>
														<img valign="center" src="pics/MiniImage/user_<?= $member2['id']?>.png" alt="" width="20px">
													<?php
													}
													?> <span style="color:<?= $color2?>;<?= $styledieu2?><?= $stylebasic2?>font-weight: bold;font-size: 1.1em;"><?= $member2['username']?> <?= $member2['nom']?></span>
													<br>
													<br>
													<?=$ifvalid?>
													<br>
													<br>
													<?php
													if($questdiv['answer'] == 0)
													{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
													<?php
													}
												}
												elseif($valid['valid'] == 2)
												{
													?>
													En cours de Validation !
													<br>
													<br>
													<?php
													if($questdiv['answer'] == 0)
													{
													?>
													<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questanswer/answer_<?=$_SESSION['id']?>_<?=$questdiv['id']?>.png">
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questanswer['text']?></span>
													<?php
													}
												}
												elseif($valid['valid'] == 1)
												{
													if($questdiv['answer'] == 0)
													{
													?>
													<p>
														Vous devez entrer le screen demandé ici !
													</p>
													<form method="POST" enctype="multipart/form-data" action="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$_GET['onload']?>">
														<input type="file" name="send_img" id="img"> <input type="submit" style="text-align:right;" name="sendnew" value="Envoyer">
													</form>
													<?php
													}
													elseif($questdiv['answer'] == 1)
													{
													?>
													<p>
														Vous devez entrer le texte attendu !
													</p>
													<form method="POST">
														<input type="text" name="answertext" placeholder="Texte attendu">
														<input type="submit" name="answersubmit" value="Envoyer la Réponse !">
													</form>
													<?php
													}
													?>
													<a href="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$_GET['onload']?>&acp=2"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Abandonner la Quête...]</span></a>
													<?php
												}
												elseif(empty($valid['valid']))
												{
													?>
													<a href="index.php?p=quest&section=group&group=<?=$_GET['group']?>&onload=<?=$_GET['onload']?>&acp=1"><span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[Accepter la Quête !]</span></a>
													<?php
												}
												?>
										</div>
									</li>
								</ul>
							</div>
							<br>
							<?php
						}
					}
				}
			}
		}
		
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