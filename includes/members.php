<?php function members()
{
	global $db, $_POST, $_GET, $_SESSION;

	if($_SESSION['rank'] >= 4)
	{
		$ranksel = 6;
	}
	else
	{
		$ranksel = 5;
	}
	$pnjsel = 2;
	$dieusel = 0;
	
			if ($line['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 2px 2px 2px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
	?>
	<form method="POST" action="index.php?p=members">
	<?php
	if($_SESSION['rank'] >= 5)
	{
		while ($dieusel >= 0)
		{
			switch ($dieusel)
			{
				default : $name = "???"; break;
				case 0: $name = "Entités Déïques"; $rankdb = 5; $pnjdb = 2; break;
			}
			$select2 = $db->prepare('SELECT * FROM member_list WHERE rank = ? AND pnj = ? ORDER BY rank desc, ban asc, desert asc, actif desc, pnj asc, digni desc, username asc');
			$select2->execute(array($rankdb, $pnjdb));
	?>
			<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
				<tbody>
							<tr style="border:2px solid #FFA500;background-color:#FFD700;">
								<th style="background-color:#FFD700;height:30px;width:30px" align="center"><font style="margin-left:10px"></font></th>
								<th style="background-color:#FFD700;height:30px;width:20px" align="center"><font style="margin-left:10px"></font></th>
								<th style="background-color:#FFD700;height:30px;width:30%;" align="left"><?= $name?></th>
								<th style="background-color:#FFD700;height:30px;width:20%;" align="left">Titre</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Race</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">BG.</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Msg.</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Clé</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Karma</th>
								<th style="background-color:#FFD700;height:30px;width:20%" align="center">Avis</th>
							</tr>
							</tr>
							<?php
								while ($line2 = $select2->fetch())
								{
									$imgrank2 = $line2['rank'];
									$imgrank2 = ($line2['actif'] == 1)? "A" : $imgrank2;
									$imgrank2 = ($line2['actif'] == 1)? "A" : $imgrank2;
									$imgrank2 = ($line2['digni'] == 1)? "D1" : $imgrank2;
									$imgrank2 = ($line2['digni'] == 2)? "D2" : $imgrank2;
									$imgrank2 = ($line2['digni'] == 3)? "D3" : $imgrank2;
									$imgrank2 = ($line2['pnj'] == 1)? "PNJ" : $imgrank2;
									$imgrank2 = ($line2['pnj'] == 3)? "E" : $imgrank2;
									$imgrank2 = ($line2['pnj'] == 2)? "DIEU" : $imgrank2;
									$imgrank2 = ($line2['desert'] == 1)? "DEL" : $imgrank2;
									$imgrank2 = ($line2['ban'] == 1)? "BAN" : $imgrank2;
									
									$reqbgvalid2 = $db->prepare('SELECT bgvalid, bgvalidator, date_valid FROM page_perso WHERE id = ?');
									$reqbgvalid2->execute(array($line2['id']));
									$persoinfo2 = $reqbgvalid2->fetch();
									
									$reqbgvalidator2 = $db->prepare('SELECT username FROM member_list WHERE id = ?');
									$reqbgvalidator2->execute(array($persoinfo2['bgvalidator']));
									$persovalid2 = $reqbgvalidator2->fetch();
									
									$date2 = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $persoinfo2['date_valid']);
									
							$perso = $_GET['perso'];
									if(isset($_GET['plus3']))
						{
							$countplus3 = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countplus3->execute(array($_SESSION['id'], $perso));
							$count7 = $countplus->rowCount();
							
							if($count7 == 0)
							{
								$insertplus3 = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertplus3->execute(array(NULL, $_SESSION['id'], $perso, '1'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updateplus3 = $db->prepare('UPDATE avis SET avis = 1 WHERE player = ? AND player2 = ?');
								$updateplus3->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
						
						if(isset($_GET['moins3']))
						{
							$countmoins3 = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countmoins3->execute(array($_SESSION['id'], $perso));
							$count8 = $countmoins->rowCount();
							
							if($count8 == 0)
							{
								$insertmoins3 = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertmoins3->execute(array(NULL, $_SESSION['id'], $perso, '-1'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updatemoins3 = $db->prepare('UPDATE avis SET avis = -1 WHERE player = ? AND player2 = ?');
								$updatemoins3->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
					
						if(isset($_GET['zero3']))
						{
							$countzero3 = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countzero3->execute(array($_SESSION['id'], $perso));
							$count9 = $countzero3->rowCount();
							
							if($count9 == 0)
							{
								$insertzero3 = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertzero3->execute(array(NULL, $_SESSION['id'], $perso, '0'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updatezero3 = $db->prepare('UPDATE avis SET avis = 0 WHERE player = ? AND player2 = ?');
								$updatezero3->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
					
						
									$rowplus2 = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player2 = ?');
									$rowplus2->execute(array($line2['id']));
									$plus2 = $rowplus2->rowCount();
											
									$rowmoins2 = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player2 = ?');
									$rowmoins2->execute(array($line2['id']));
									$moins2 = $rowmoins2->rowCount();
											
									$total2 = $plus2 - $moins2;
									
									$karma2 = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ?');
									$karma2->execute(array($line2['id']));
									
									$karmacount2 = $karma2->fetch();
									?>
										<tr style="border:2px solid #8B4513;background-color:#A0522D;">
											<td style="min-height:25px" valign="middle" align="center">
												<img valign="center" src="pics/rank/Grade<?= $imgrank2?>.png" alt="" width="24px" />
											</td>
											<td style="height:25px" valign="middle" align="center">
											<?php

											if (file_exists("pics/MiniImage/user_".$line2['id'].".png"))
											{
												?>
												<img valign="center" src="pics/MiniImage/user_<?= $line2['id']?>.png" alt="" width="32px" />
												<?php
											}
											?>
											</td>
											<td style="height:25px" valign="middle" align="left">
												<a href="index.php?p=profile&perso=<?= $line2['id']?>" title="Pseudo Minecraft: <?= $line2['pseudo']?>"><?= $line2['username'], " ", $line2['nom']?></a>
											</td>
											<td style="height:25px" valign="middle" align="left">
												<?php
												if($line2['ban'] == 1)
												{
												?>
												<span style="color: #FF8A00">BANNI</span>
												<?php
												}
												elseif($line2['desert'] == 1)
												{
												?>
												<span style="color: #6A400F">DÉSERTEUR</span>
												<?php
												}
												elseif($line2['ban'] == 0)
												{
												if($line2['desert'] == 0)
												{
												?>
												<?= $line2['title']?>
												<?php
												}
												}
												?>
												<?php
												if($line2['digni'] == 3)
												{
												?>
												<br>
												<span style="color: #00AAAA">(Dignitaire)</span>
												<?php
												}
												elseif($line2['digni'] == 2)
												{
												?>
												<br>
												<span style="color: #FFAA00">(Dignitaire)</span>
												<?php
												}
												elseif($line2['digni'] == 1)
												{
												?>
												<br>
												<span style="color: #AA0000">(Dignitaire)</span>
												<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
											<?php

											if (file_exists("pics/race/Race".$line2['race'].".png"))
											{
												?>
												<img title="Race: <?= $line2['race']?>" src="pics/race/Race<?= $line2['race']?>.png" alt="" width="16px" />
												<?php
											}
											else
											{
												?>
												<img title="Race: <?= $line2['race']?>" src="pics/race/RaceInconnue.png" alt="" width="16px" />
												<?php
											}
											?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($persoinfo2['bgvalid'] == 1)
												{
													?>
													<img title="Sceau d'approbation des MJs apposé par <?=$persovalid2['username']?>, <?=$date2?>" width="16px" src="pics/Accueil/seal.png">
													<?php
												}
												elseif($persoinfo2['bgvalid'] == 2)
												{
													?>
													<img title="Sceau de désapprobation des MJs apposé par <?=$persovalid2['username']?>, <?=$date2?>" width="16px" src="pics/Accueil/seal3.png">
													<?php
												}
												elseif($persoinfo2['bgvalid'] == 0)
												{
													?>
													<img title="En cours d'examination !" width="16px" src="pics/Accueil/seal2.png">
													<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?= $line2['msg']?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?= $line2['keycount']?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($karmacount2['karma_total'] >= 10)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line2['id']?>" class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
														[<?= $karmacount2['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount2['karma_total'] >= 1)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line2['id']?>" class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'">
														[<?= $karmacount2['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount2['karma_total'] == 0)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line2['id']?>" class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'">
														[0]
													</a>
													<?php
												}
												elseif($karmacount2['karma_total'] <= -10)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line2['id']?>" class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">
														[<?= $karmacount2['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount2['karma_total'] <= -1)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line2['id']?>" class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'">
														[<?= $karmacount2['karma_total']?>]
													</a>
													<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($total2 >= 10)
												{
													?>
														<a style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total2 >= 1)
												{
													?>
														<a style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total == 0)
												{
													?>
														<a style="font-weight: bold; color: #000000" onmouseout="this.style.color='#000000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total2 <= -1)
												{
													?>
														<a style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total2 <= -10)
												{
													?>
														<a style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												if($total2 >= 1)
												{
													?>
													[+<?=$total2?>]
													<?php
												}
												elseif($total2 == 0)
												{
													?>
													[0]
													<?php
												}
												elseif($total2 <= -1)
												{
													?>
													[<?=$total2?>]
													<?php
												}
												?></a>
												<?php
											if(($_SESSION['rank'] >= 4 OR $_SESSION['digni'] != 0) AND $_SESSION['id'] != $line2['id'])
											{
											$rowplus4 = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player = ? AND player2 = ?');
											$rowplus4->execute(array($_SESSION['id'], $line2['id']));
											$plus4 = $rowplus4->rowCount();
											
											$rowmoins4 = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player = ? AND player2 = ?');
											$rowmoins4->execute(array($_SESSION['id'], $line2['id']));
											$moins4 = $rowmoins4->rowCount();
											
											$rowzero4 = $db->prepare('SELECT avis FROM avis WHERE avis = 0 AND player = ? AND player2 = ?');
											$rowzero4->execute(array($_SESSION['id'], $line2['id']));
											$zero4 = $rowzero4->rowCount();
											
												if($plus4 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line2['id']?>&plus3=1">[+1]</a>
												<?php
												}
												if($zero4 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line2['id']?>&zero3=1">[0]</a>
												<?php
												}
												if($moins4 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line2['id']?>&moins3=1">[-1]</a>
												<?php
												}
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
						<?php
						$dieusel --;
					}
		}
		if($_SESSION['rank'] >= 4)
		{
			while ($pnjsel >= 0)
			{
				switch ($pnjsel)
				{
					default : $name = "???"; break;
					case 2: $name = "Entités"; $rankdb = 4; $pnjdb = 3; break;
					case 1: $name = "PNJs"; $rankdb = 4; $pnjdb = 1; break;
					case 0: $name = "Joueurs Alphas"; $rankdb = 2; $pnjdb = 0; break;
				}
				$select3 = $db->prepare('SELECT * FROM member_list WHERE rank = ? AND pnj = ? ORDER BY rank desc, ban asc, desert asc, actif desc, pnj asc, digni desc, username asc');
				$select3->execute(array($rankdb, $pnjdb));
				?>
				<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
					<tbody>
							<tr style="border:2px solid #FFA500;background-color:#FFD700;">
								<th style="background-color:#FFD700;height:30px;width:30px" align="center"><font style="margin-left:10px"></font></th>
								<th style="background-color:#FFD700;height:30px;width:20px" align="center"><font style="margin-left:10px"></font></th>
								<th style="background-color:#FFD700;height:30px;width:30%;" align="left"><?= $name?></th>
								<th style="background-color:#FFD700;height:30px;width:20%;" align="left">Titre</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Race</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">BG.</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Msg.</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Clé</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Karma</th>
								<th style="background-color:#FFD700;height:30px;width:20%" align="center">Avis</th>
							</tr>
							</tr>
							</tr>
							<?php
								while ($line3 = $select3->fetch())
								{
									$imgrank3 = $line3['rank'];
									$imgrank3 = ($line3['actif'] == 1)? "A" : $imgrank3;
									$imgrank3 = ($line3['actif'] == 1)? "A" : $imgrank3;
									$imgrank3 = ($line3['digni'] == 1)? "D1" : $imgrank3;
									$imgrank3 = ($line3['digni'] == 2)? "D2" : $imgrank3;
									$imgrank3 = ($line3['digni'] == 3)? "D3" : $imgrank3;
									$imgrank3 = ($line3['pnj'] == 1)? "PNJ" : $imgrank3;
									$imgrank3 = ($line3['pnj'] == 3)? "E" : $imgrank3;
									$imgrank3 = ($line3['pnj'] == 2)? "DIEU" : $imgrank3;
									$imgrank3 = ($line3['desert'] == 1)? "DEL" : $imgrank3;
									$imgrank3 = ($line3['ban'] == 1)? "BAN" : $imgrank3;
									
									$reqbgvalid3 = $db->prepare('SELECT bgvalid, bgvalidator, date_valid FROM page_perso WHERE id = ?');
									$reqbgvalid3->execute(array($line3['id']));
									$persoinfo3 = $reqbgvalid3->fetch();
									
									$reqbgvalidator3 = $db->prepare('SELECT username FROM member_list WHERE id = ?');
									$reqbgvalidator3->execute(array($persoinfo3['bgvalidator']));
									$persovalid3 = $reqbgvalidator3->fetch();
									
									$date3 = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $persoinfo3['date_valid']);
									
							$perso = $_GET['perso'];
									if(isset($_GET['plus2']))
						{
							$countplus2 = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countplus2->execute(array($_SESSION['id'], $perso));
							$count4 = $countplus->rowCount();
							
							if($count4 == 0)
							{
								$insertplus2 = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertplus2->execute(array(NULL, $_SESSION['id'], $perso, '1'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updateplus2 = $db->prepare('UPDATE avis SET avis = 1 WHERE player = ? AND player2 = ?');
								$updateplus2->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
						
						if(isset($_GET['moins2']))
						{
							$countmoins2 = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countmoins2->execute(array($_SESSION['id'], $perso));
							$count5 = $countmoins->rowCount();
							
							if($count5 == 0)
							{
								$insertmoins2 = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertmoins2->execute(array(NULL, $_SESSION['id'], $perso, '-1'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updatemoins2 = $db->prepare('UPDATE avis SET avis = -1 WHERE player = ? AND player2 = ?');
								$updatemoins2->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
					
						if(isset($_GET['zero2']))
						{
							$countzero2 = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countzero2->execute(array($_SESSION['id'], $perso));
							$count6 = $countzero2->rowCount();
							
							if($count6 == 0)
							{
								$insertzero2 = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertzero2->execute(array(NULL, $_SESSION['id'], $perso, '0'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updatezero2 = $db->prepare('UPDATE avis SET avis = 0 WHERE player = ? AND player2 = ?');
								$updatezero2->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
					
									
									$rowplus3 = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player2 = ?');
									$rowplus3->execute(array($line3['id']));
									$plus3 = $rowplus3->rowCount();
											
									$rowmoins3 = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player2 = ?');
									$rowmoins3->execute(array($line3['id']));
									$moins3 = $rowmoins3->rowCount();
											
									$total3 = $plus3 - $moins3;
									
									$karma3 = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ?');
									$karma3->execute(array($line3['id']));
									
									$karmacount3 = $karma3->fetch();
									?>
										<tr style="border:2px solid #8B4513;background-color:#A0522D;">
											<td style="height:25px" valign="middle" align="center">
												<img valign="center" src="pics/rank/Grade<?= $imgrank3?>.png" alt="" width="24px" />
											</td>
											<td style="height:25px" valign="middle" align="center">
											<?php

											if (file_exists("pics/MiniImage/user_".$line3['id'].".png"))
											{
												?>
												<img valign="center" src="pics/MiniImage/user_<?= $line3['id']?>.png" alt="" width="32px" />
												<?php
											}
											?>
											</td>
											<td style="height:25px" valign="middle" align="left">
												<a href="index.php?p=profile&perso=<?= $line3['id']?>" title="Pseudo Minecraft: <?= $line3['pseudo']?>"><?= $line3['username'], " ", $line3['nom']?></a>
											</td>
											<td style="height:25px" valign="middle" align="left">
												<?php
												if($line3['ban'] == 1)
												{
												?>
												<span style="color: #FF8A00">BANNI</span>
												<?php
												}
												elseif($line3['desert'] == 1)
												{
												?>
												<span style="color: #6A400F">DÉSERTEUR</span>
												<?php
												}
												elseif($line3['ban'] == 0)
												{
												if($line3['desert'] == 0)
												{
												?>
												<?= $line3['title']?>
												<?php
												}
												}
												?>
												<?php
												if($line3['digni'] == 3)
												{
												?>
												<br>
												<span style="color: #00AAAA">(Dignitaire)</span>
												<?php
												}
												elseif($line3['digni'] == 2)
												{
												?>
												<br>
												<span style="color: #FFAA00">(Dignitaire)</span>
												<?php
												}
												elseif($line3['digni'] == 1)
												{
												?>
												<br>
												<span style="color: #AA0000">(Dignitaire)</span>
												<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
											<?php

											if (file_exists("pics/race/Race".$line3['race'].".png"))
											{
												?>
												<img title="Race: <?= $line3['race']?>" src="pics/race/Race<?= $line3['race']?>.png" alt="" width="16px" />
												<?php
											}
											else
											{
												?>
												<img title="Race: <?= $line3['race']?>" src="pics/race/RaceInconnue.png" alt="" width="16px" />
												<?php
											}
											?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($persoinfo3['bgvalid'] == 1)
												{
													?>
													<img title="Sceau d'approbation des MJs apposé par <?=$persovalid3['username']?>, <?=$date3?>" width="16px" src="pics/Accueil/seal.png">
													<?php
												}
												elseif($persoinfo3['bgvalid'] == 2)
												{
													?>
													<img title="Sceau de désapprobation des MJs apposé par <?=$persovalid3['username']?>, <?=$date3?>" width="16px" src="pics/Accueil/seal3.png">
													<?php
												}
												elseif($persoinfo3['bgvalid'] == 0)
												{
													?>
													<img title="En cours d'examination !" width="16px" src="pics/Accueil/seal2.png">
													<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?= $line3['msg']?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?= $line3['keycount']?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($karmacount3['karma_total'] >= 10)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line3['id']?>" class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
														[<?= $karmacount3['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount3['karma_total'] >= 1)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line3['id']?>" class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'">
														[<?= $karmacount3['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount3['karma_total'] == 0)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line3['id']?>" class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'">
														[0]
													</a>
													<?php
												}
												elseif($karmacount3['karma_total'] <= -10)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line3['id']?>" class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">
														[<?= $karmacount3['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount3['karma_total'] <= -1)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line3['id']?>" class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'">
														[<?= $karmacount3['karma_total']?>]
													</a>
													<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($total2 >= 10)
												{
													?>
														<a style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total2 >= 1)
												{
													?>
														<a style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total == 0)
												{
													?>
														<a style="font-weight: bold; color: #000000" onmouseout="this.style.color='#000000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total2 <= -1)
												{
													?>
														<a style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												elseif($total2 <= -10)
												{
													?>
														<a style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line2['id']?>">
													<?php
												}
												if($total2 >= 1)
												{
													?>
													[+<?=$total2?>]
													<?php
												}
												elseif($total2 == 0)
												{
													?>
													[0]
													<?php
												}
												elseif($total2 <= -1)
												{
													?>
													[<?=$total2?>]
													<?php
												}
												?></a>
												<?php
											if(($_SESSION['rank'] >= 4 OR $_SESSION['digni'] != 0) AND $_SESSION['id'] != $line2['id'])
											{
											$rowplus4 = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player = ? AND player2 = ?');
											$rowplus4->execute(array($_SESSION['id'], $line2['id']));
											$plus4 = $rowplus4->rowCount();
											
											$rowmoins4 = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player = ? AND player2 = ?');
											$rowmoins4->execute(array($_SESSION['id'], $line2['id']));
											$moins4 = $rowmoins4->rowCount();
											
											$rowzero4 = $db->prepare('SELECT avis FROM avis WHERE avis = 0 AND player = ? AND player2 = ?');
											$rowzero4->execute(array($_SESSION['id'], $line2['id']));
											$zero4 = $rowzero4->rowCount();
											
												if($plus4 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line2['id']?>&plus3=1">[+1]</a>
												<?php
												}
												if($zero4 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line2['id']?>&zero3=1">[0]</a>
												<?php
												}
												if($moins4 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line2['id']?>&moins3=1">[-1]</a>
												<?php
												}
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
					<?php
					$pnjsel --;
					}
		}
		while ($ranksel >= 0)
		{
			if($_SESSION['rank'] >= 4)
			{
			switch ($ranksel)
			{
				default : $name = "???"; break;
				case 6: $name = "Maîtres du Jeu Superviseurs"; $rankdb = 6; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 5: $name = "Maîtres du Jeu"; $rankdb = 5; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 4: $name = "Cadres"; $rankdb = 4; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 3: $name = "VIPs"; $rankdb = 3; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 2: $name = "Joueurs Actifs"; $rankdb = 1; $pnjdb = 0; $actifdb = 1; $vanishdb = 0; break;
				case 1: $name = "Joueurs"; $rankdb = 1; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 0: $name = "Visiteurs"; $rankdb = 0; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
			}
			}
			else
			{
			switch ($ranksel)
			{
				default : $name = "???"; break;
				case 5: $name = "Maîtres du Jeu Superviseurs"; $rankdb = 6; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 4: $name = "Maîtres du Jeu"; $rankdb = 5; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 3: $name = "Cadres"; $rankdb = 4; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 2: $name = "VIPs"; $rankdb = 3; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
				case 1: $name = "Joueurs Actifs"; $rankdb = 1; $pnjdb = 0; $actifdb = 1; $vanishdb = 0; break;
				case 0: $name = "Joueurs"; $rankdb = 1; $pnjdb = 0; $actifdb = 0; $vanishdb = 0; break;
			}
			}
		if($_SESSION['rank'] >= 5)
		{
		$select = $db->prepare('SELECT * FROM member_list WHERE rank = ? AND pnj = ? AND actif = ? ORDER BY rank desc, ban asc, desert asc, actif desc, pnj asc, digni desc, username asc');
		$select->execute(array($rankdb, $pnjdb, $actifdb));
		}
		elseif($_SESSION['rank'] >= 4)
		{
		$select = $db->prepare('SELECT * FROM member_list WHERE rank = ? AND pnj = ? AND actif = ? AND vanish = ? ORDER BY rank desc, ban asc, desert asc, actif desc, pnj asc, digni desc, username asc');
		$select->execute(array($rankdb, $pnjdb, $actifdb, $vanishdb));
		}
		else
		{
		$select = $db->prepare('SELECT * FROM member_list WHERE rank = ? AND pnj = ? AND actif = ? AND vanish = ? AND ban = 0 AND desert = 0 ORDER BY rank desc, actif desc, pnj asc, digni desc, username asc');
		$select->execute(array($rankdb, $pnjdb, $actifdb, $vanishdb));
		}
		?>
			<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
				<tbody>
							<tr style="border:2px solid #FFA500;background-color:#FFD700;">
								<th style="background-color:#FFD700;height:30px;width:30px" align="center"><font style="margin-left:10px"></font></th>
								<th style="background-color:#FFD700;height:30px;width:20px" align="center"><font style="margin-left:10px"></font></th>
								<th style="background-color:#FFD700;height:30px;width:30%;" align="left"><?= $name?></th>
								<th style="background-color:#FFD700;height:30px;width:20%;" align="left">Titre</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Race</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">BG.</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Msg.</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Clé</th>
								<th style="background-color:#FFD700;height:30px;width:5%" align="center">Karma</th>
								<th style="background-color:#FFD700;height:30px;width:20%" align="center">Avis</th>
							</tr>
							</tr>
							</tr>
							<?php
								while ($line = $select->fetch())
								{
									$imgrank = $line['rank'];
									$imgrank = ($line['actif'] == 1)? "A" : $imgrank;
									$imgrank = ($line['actif'] == 1)? "A" : $imgrank;
									$imgrank = ($line['digni'] == 1)? "D1" : $imgrank;
									$imgrank = ($line['digni'] == 2)? "D2" : $imgrank;
									$imgrank = ($line['digni'] == 3)? "D3" : $imgrank;
									$imgrank = ($line['pnj'] == 1)? "PNJ" : $imgrank;
									$imgrank = ($line['pnj'] == 3)? "E" : $imgrank;
									$imgrank = ($line['pnj'] == 2)? "DIEU" : $imgrank;
									$imgrank = ($line['desert'] == 1)? "DEL" : $imgrank;
									$imgrank = ($line['ban'] == 1)? "BAN" : $imgrank;
									
									$reqbgvalid = $db->prepare('SELECT bgvalid, bgvalidator, date_valid FROM page_perso WHERE id = ?');
									$reqbgvalid->execute(array($line['id']));
									$persoinfo = $reqbgvalid->fetch();
									
									$reqbgvalidator = $db->prepare('SELECT username FROM member_list WHERE id = ?');
									$reqbgvalidator->execute(array($persoinfo['bgvalidator']));
									$persovalid = $reqbgvalidator->fetch();
									
									$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $persoinfo['date_valid']);
									
							$perso = $_GET['perso'];
						if(isset($_GET['plus']))
						{
							$countplus = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countplus->execute(array($_SESSION['id'], $perso));
							$count1 = $countplus->rowCount();
							
							if($count1 == 0)
							{
								$insertplus = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertplus->execute(array(NULL, $_SESSION['id'], $perso, '1'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updateplus = $db->prepare('UPDATE avis SET avis = 1 WHERE player = ? AND player2 = ?');
								$updateplus->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
						
						if(isset($_GET['moins']))
						{
							$countmoins = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countmoins->execute(array($_SESSION['id'], $perso));
							$count2 = $countmoins->rowCount();
							
							if($count2 == 0)
							{
								$insertmoins = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertmoins->execute(array(NULL, $_SESSION['id'], $perso, '-1'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updatemoins = $db->prepare('UPDATE avis SET avis = -1 WHERE player = ? AND player2 = ?');
								$updatemoins->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
					
						if(isset($_GET['zero']))
						{
							$countzero = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countzero->execute(array($_SESSION['id'], $perso));
							$count3 = $countzero->rowCount();
							
							if($count3 == 0)
							{
								$insertzero = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertzero->execute(array(NULL, $_SESSION['id'], $perso, '0'));
								header('Location: index.php?p=members');
							}
							else
							{
								$updatezero = $db->prepare('UPDATE avis SET avis = 0 WHERE player = ? AND player2 = ?');
								$updatezero->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=members');
							}
						}
					
						
									$rowplus = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player2 = ?');
									$rowplus->execute(array($line['id']));
									$plus = $rowplus->rowCount();
											
									$rowmoins = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player2 = ?');
									$rowmoins->execute(array($line['id']));
									$moins = $rowmoins->rowCount();
											
									$total = $plus - $moins;
									
									$karma = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ?');
									$karma->execute(array($line['id']));
									
									$karmacount = $karma->fetch();
									?>
										<tr style="border:2px solid #8B4513;background-color:#A0522D;">
											<td style="height:25px" valign="middle" align="center">
												<img valign="center" src="pics/rank/Grade<?= $imgrank?>.png" alt="" width="24px" />
											</td>
											<td style="height:25px" valign="middle" align="center">
											<?php

											if (file_exists("pics/MiniImage/user_".$line['id'].".png"))
											{
												?>
												<img valign="center" src="pics/MiniImage/user_<?= $line['id']?>.png" alt="" width="32px" />
												<?php
											}
											?>
											</td>
											<td style="height:25px" valign="middle" align="left">
												<a href="index.php?p=profile&perso=<?= $line['id']?>" title="Pseudo Minecraft: <?= $line['pseudo']?>"><?= $line['username'], " ", $line['nom']?></a>
												<?php
												if($line['vanish'] == 1)
												{
												?> [V]
												<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="left">
												<?php
												if($line['ban'] == 1)
												{
												?>
												<span style="color: #FF8A00">BANNI</span>
												<?php
												}
												elseif($line['desert'] == 1)
												{
												?>
												<span style="color: #6A400F">DÉSERTEUR</span>
												<?php
												}
												elseif($line['ban'] == 0)
												{
												if($line['desert'] == 0)
												{
												?>
												<?= $line['title']?>
												<?php
												}
												}
												?>
												<?php
												if($line['digni'] == 3)
												{
												?>
												<br>
												<span style="color: #00AAAA">(Dignitaire)</span>
												<?php
												}
												elseif($line['digni'] == 2)
												{
												?>
												<br>
												<span style="color: #FFAA00">(Dignitaire)</span>
												<?php
												}
												elseif($line['digni'] == 1)
												{
												?>
												<br>
												<span style="color: #AA0000">(Dignitaire)</span>
												<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
											<?php

											if (file_exists("pics/race/Race".$line['race'].".png"))
											{
												?>
												<img title="Race: <?= $line['race']?>" src="pics/race/Race<?= $line['race']?>.png" alt="" width="16px" />
												<?php
											}
											else
											{
												?>
												<img title="Race: <?= $line['race']?>" src="pics/race/RaceInconnue.png" alt="" width="16px" />
												<?php
											}
											?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($persoinfo['bgvalid'] == 1)
												{
													?>
													<img title="Sceau d'approbation des MJs apposé par <?=$persovalid['username']?>, <?=$date?>" width="16px" src="pics/Accueil/seal.png">
													<?php
												}
												elseif($persoinfo['bgvalid'] == 2)
												{
													?>
													<img title="Sceau de désapprobation des MJs apposé par <?=$persovalid['username']?>, <?=$date?>" width="16px" src="pics/Accueil/seal3.png">
													<?php
												}
												elseif($persoinfo['bgvalid'] == 0)
												{
													?>
													<img title="En cours d'examination !" width="16px" src="pics/Accueil/seal2.png">
													<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?= $line['msg']?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?= $line['keycount']?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($karmacount['karma_total'] >= 10)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line['id']?>" class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">
														[<?= $karmacount['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount['karma_total'] >= 1)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line['id']?>" class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'">
														[<?= $karmacount['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount['karma_total'] == 0)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line['id']?>" class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'">
														[0]
													</a>
													<?php
												}
												elseif($karmacount['karma_total'] <= -10)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line['id']?>" class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">
														[<?= $karmacount['karma_total']?>]
													</a>
													<?php
												}
												elseif($karmacount['karma_total'] <= -1)
												{
													?>
													<a href="index.php?p=karma&perso=<?=$line['id']?>" class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'">
														[<?= $karmacount['karma_total']?>]
													</a>
													<?php
												}
												?>
											</td>
											<td style="height:25px" valign="middle" align="center">
												<?php
												if($total >= 10)
												{
													?>
														<a style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line['id']?>">
													<?php
												}
												elseif($total >= 1)
												{
													?>
														<a style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line['id']?>">
													<?php
												}
												elseif($total == 0)
												{
													?>
														<a style="font-weight: bold; color: #000000" onmouseout="this.style.color='#000000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line['id']?>">
													<?php
												}
												elseif($total <= -1)
												{
													?>
														<a style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line['id']?>">
													<?php
												}
												elseif($total <= -10)
												{
													?>
														<a style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$line['id']?>">
													<?php
												}
												if($total >= 1)
												{
													?>
													[+<?=$total?>]
													<?php
												}
												elseif($total == 0)
												{
													?>
													[0]
													<?php
												}
												elseif($total <= -1)
												{
													?>
													[<?=$total?>]
													<?php
												}
												?></a>
												<?php
											if(($_SESSION['rank'] >= 4 OR $_SESSION['digni'] != 0) AND $_SESSION['id'] != $line['id'])
											{
											$rowplus6 = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player = ? AND player2 = ?');
											$rowplus6->execute(array($_SESSION['id'], $line['id']));
											$plus6 = $rowplus6->rowCount();
											
											$rowmoins6 = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player = ? AND player2 = ?');
											$rowmoins6->execute(array($_SESSION['id'], $line['id']));
											$moins6 = $rowmoins6->rowCount();
											
											$rowzero6 = $db->prepare('SELECT avis FROM avis WHERE avis = 0 AND player = ? AND player2 = ?');
											$rowzero6->execute(array($_SESSION['id'], $line['id']));
											$zero6 = $rowzero6->rowCount();
											
												if($plus6 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line['id']?>&plus=1">[+1]</a>
												<?php
												}
												if($zero6 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line['id']?>&zero=1">[0]</a>
												<?php
												}
												if($moins6 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=members&perso=<?=$line['id']?>&moins=1">[-1]</a>
												<?php
												}
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
			<?php
			$ranksel --;
		}
		?>
	</form>
	<?php
}
?>