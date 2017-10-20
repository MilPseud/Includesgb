<?php function sondage()
{	global $db, $_POST, $_SESSION, $_GET;
	
	if(!isset($_GET['id']))
	{	
		if($_SESSION['rank'] >= 4)
		{
			if(isset($_POST['sendsondage']))
			{
				if(!empty($_POST['sujet']) AND !empty($_POST['msg']))
				{
					$insertsondage = $db->prepare('INSERT INTO sondage VALUES(?, ?, ?, 0, ?, ?, ?, NOW())');
					$insertsondage->execute(array(NULL, $_POST['rank'], $_POST['sujet'], $_POST['RP'], $_SESSION['id'], $_POST['msg']));
				} else {
					$erreur = "Un des champs n'a pas était rempli... !";
				}
			}
			?>
			<div class="nav">
				<ul>
					<li>
						<div class="link">
							<form method="POST">
							<input type="text" name="sujet" placeholder="Sujet du Sondage">
							<br>
							<br>
							<textarea style="width: 60%; max-width: 90%; min-height: 120px" name="msg" type="text" placeholder="Message du Sondage"></textarea>
							<br>
							<br>
							<span style="color: red"><?=$erreur?></span>
							<br>
							<br>
							<input type="radio" name="RP" value="RP"> RP
							<input type="radio" name="RP" value="HRP"> HRP
							<br>
							<br>
							<input type="radio" name="rank" value="1"> Joueur
							<input type="radio" name="rank" value="4"> Cadre
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
							<br>
							<br>
							<input type="submit" name="sendsondage" value="Envoyer">
							</form>
						</div>
					</li>
				</ul>
			</div>
			<br>
			<?php
		}
		if($_SESSION['rank'] >= 6)
		{
			$sondagesel = 3;
		}
		elseif($_SESSION['rank'] >= 5)
		{
			$sondagesel = 2;
		}
		elseif($_SESSION['rank'] >= 4)
		{
			$sondagesel = 1;
		}
		elseif($_SESSION['rank'] >= 1)
		{
			$sondagesel = 0;
		}
		
		while ($sondagesel >= 0)
		{
			if($_SESSION['rank'] >= 6)
			{
				switch ($sondagesel)
				{
					default : $name = "???"; break;
					case 3: $name = "Sondages Joueurs"; $rankdb = 1; break;
					case 2: $name = "Sondages Cadres"; $rankdb = 4; break;
					case 1: $name = "Sondages MJs"; $rankdb = 5; break;
					case 0: $name = "Sondages MJSs"; $rankdb = 6; break;
				}
			}
			elseif($_SESSION['rank'] >= 5)
			{
				switch ($sondagesel)
				{
					default : $name = "???"; break;
					case 2: $name = "Sondages Joueurs"; $rankdb = 1; break;
					case 1: $name = "Sondages Cadres"; $rankdb = 4; break;
					case 0: $name = "Sondages MJs"; $rankdb = 5; break;
				}
			}
			elseif($_SESSION['rank'] >= 4)
			{
				switch ($sondagesel)
				{
					default : $name = "???"; break;
					case 1: $name = "Sondages Joueurs"; $rankdb = 1; break;
					case 0: $name = "Sondages Cadres"; $rankdb = 4; break;
				}
			}
			elseif($_SESSION['rank'] >= 1)
			{
				switch ($sondagesel)
				{
					default : $name = "???"; break;
					case 0: $name = "Sondages Joueurs"; $rankdb = 1; break;
				}
			}
			$reqsondage = $db->prepare('SELECT * FROM sondage WHERE rankacess = ? ORDER BY rankacess asc, id desc');
			$reqsondage->execute(array($rankdb));
			
			$reqsondage2 = $db->prepare('SELECT * FROM sondage WHERE rankacess = ? ORDER BY rankacess asc, id desc');
			$reqsondage2->execute(array($rankdb));
			$sondageinfo2 = $reqsondage2->fetch();
			
			?>
				<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
					<tbody>
						<tr style="border:2px solid #FFA500;background-color:#FFD700;">
							<th style="background-color:#FFD700;height:30px;width:65%" align="center"><?=$name?></th>
							<th style="background-color:#FFD700;height:30px;width:35%" align="center">Auteur</th>
						</tr>
						<tr>
							<?php
							while($sondageinfo = $reqsondage->fetch())
							{
								$reqmember = $db->prepare('SELECT * FROM member_list WHERE id = ?');
								$reqmember->execute(array($sondageinfo['author']));
								$cadre = $reqmember->fetch();
								
								if ($cadre['pnj'] == 2)
								{
									$stylebasic = false;
									$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
								}
								else
								{
									$stylebasic = "text-shadow: 2px 2px 2px #000000;";
									$styledieu = false;
								}
								
								switch ($cadre['rank'])
								{
									default : $color = "#555550"; break;
									case 1:  $color = "#00AA00"; $color = ($cadre['actif'] == 1)? "#FF5555" : $color;
									$color = ($cadre['digni'] == 3)? "#5555FF" : $color; break;
									case 2: $color = "#55FF55"; $color = ($cadre['actif'] == 1)? "#FF5555" : $color;
									$color = ($cadre['digni'] == 3)? "#5555FF" : $color; break;
									case 3: $color = "#FF55FF"; break;
									case 4: $color = "#00AAAA"; $color = ($cadre['pnj'] == 1)? "#55FFFF" : $color;
									$color = ($cadre['digni'] == 2)? "#FFFF55" : $color; break;
									case 5: $color = "#FFAA00"; $color = ($cadre['pnj'] == 2)? "#0200A6" : $color;
									$color = ($cadre['digni'] == 1)? "#AA00AA" : $color; break;
									case 6: $color = "#AA0000"; break;
									case 7: $color = "#000000"; break;
								}
								
								$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $sondageinfo['date_send']);
								?>
								<tr style="border:2px solid #8B4513;background-color:#A0522D;">
									<td valign="middle" align="center">
										<a href="index.php?p=sondage&id=<?=$sondageinfo['id']?>">
											<?php
											if($sondageinfo['verr'] == 1)
											{
												?>
												<span class="username-detail" style="color:#990000;font-weight: bold;">[Vérrouillé] </span>
												<?php
											}
											if($sondageinfo['rp'] == "HRP")
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP] </span>
												<?php
											}
											elseif($sondageinfo['rp'] == "RP")
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP] </span>
												<?php
											}
											?>
											<?=$sondageinfo['name']?> <?php
											if($sondageinfo['verr'] == 0)
											{
												$sqlvotecount = $db->prepare('SELECT yes, no, neutral FROM vote WHERE player = ? AND id = ?');
												$sqlvotecount->execute(array($_SESSION['id'], $sondageinfo['id']));
												$votecount = $sqlvotecount->rowCount();
												
												if($votecount <= 0)
												{
													?>
													<span style="color: red">[!]</span>
													<?php
												}
											}
											?>
										</a>
									</td>
									<td valign="middle" align="center">
										<?php
										if(file_exists('pics/MiniImage/user_'.$sondageinfo['author'].'.png'))
										{
											?>
											<img src="pics/MiniImage/user_<?=$sondageinfo['author']?>.png">
											<?php
										}
										?> <span class="username-detail" style="color: <?=$color?>;<?=$stylebasic?><?=$styledieu?>">
											<?=$cadre['title']?> <?=$cadre['username']?>
										</span>
										<br>
										<?=$date?>
									</td>
								</tr>
								<?php
							}
							?>
						</tr>
					</tbody>
				</table>
				<br>
			<?php
			$sondagesel --;
		}
	}
	elseif(isset($_GET['id']))
	{
		$reqvote = $db->prepare('SELECT v.*, s.id AS s_id, s.rankacess, s.name, s.verr, s.rp, s.author, s.msg, s.date_send FROM vote v RIGHT JOIN sondage s ON v.id = s.id WHERE v.id = ?');
		$reqvote->execute(array($_GET['id']));
		$voteinfo = $reqvote->fetch();
			
		if($_SESSION['rank'] >= $voteinfo['rankacess'])
		{
			$reqsondage = $db->prepare('SELECT name, verr, author, date_send, rp, msg FROM sondage WHERE id = ?');
			$reqsondage->execute(array($_GET['id']));
			$sondageinfo = $reqsondage->fetch();
			
			$reqmember2 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$reqmember2->execute(array($sondageinfo['author']));
			$player2 = $reqmember2->fetch();
			
			$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $sondageinfo['date_send']);
			$msg = preg_replace('#\n#', '<br>', $sondageinfo['msg']);
			
			if ($player2['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
			switch ($player2['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($player2['actif'] == 1)? "#FF5555" : $color;
				$color = ($player2['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($player2['actif'] == 1)? "#FF5555" : $color;
				$color = ($player2['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($player2['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($player2['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($player2['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($player2['pnj'] == 2)? "#0200A6" : $color;
				$color = ($player2['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
			
			if(isset($_GET['neutral']))
			{
				$countneutral = $db->prepare('SELECT * FROM vote WHERE player = ? AND id = ?');
				$countneutral->execute(array($_SESSION['id'], $_GET['id']));
				$neutralcount = $countneutral->rowCount();
				
				if($neutralcount == 0)
				{
					$insertneutral = $db->prepare('INSERT INTO vote VALUES(?, ?, ?, ?, ?, ?, ?)');
					$insertneutral->execute(array(NULL, '0', '1', '0', $_GET['id'], $_SESSION['id'], '0'));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
				else
				{
					$updateneutral = $db->prepare('UPDATE vote SET yes = 0, neutral = 1, no = 0 WHERE player = ? AND id = ?');
					$updateneutral->execute(array($_SESSION['id'], $_GET['id']));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
			}
			
			if(isset($_GET['no']))
			{
				$countno = $db->prepare('SELECT * FROM vote WHERE player = ? AND id = ?');
				$countno->execute(array($_SESSION['id'], $_GET['id']));
				$nocount = $countno->rowCount();
				
				if($nocount == 0)
				{
					$insertno = $db->prepare('INSERT INTO vote VALUES(?, ?, ?, ?, ?, ?, ?)');
					$insertno->execute(array(NULL, '0', '0', '1', $_GET['id'], $_SESSION['id'], '0'));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
				else
				{
					$updateno = $db->prepare('UPDATE vote SET yes = 0, neutral = 0, no = 1 WHERE player = ? AND id = ?');
					$updateno->execute(array($_SESSION['id'], $_GET['id']));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
			}
			
			if(isset($_GET['yes']))
			{
				$countyes = $db->prepare('SELECT * FROM vote WHERE player = ? AND id = ?');
				$countyes->execute(array($_SESSION['id'], $_GET['id']));
				$yescount = $countyes->rowCount();
				
				if($yescount == 0)
				{
					$insertyes = $db->prepare('INSERT INTO vote VALUES(?, ?, ?, ?, ?, ?, ?)');
					$insertyes->execute(array(NULL, '1', '0', '0', $_GET['id'], $_SESSION['id'], '0'));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
				else
				{
					$updateyes = $db->prepare('UPDATE vote SET yes = 1, neutral = 0, no = 0 WHERE player = ? AND id = ?');
					$updateyes->execute(array($_SESSION['id'], $_GET['id']));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
			}
			
			if(isset($_GET['verr']))
			{
				if($_GET['verr'] == 1)
				{
					$updateVERR = $db->prepare('UPDATE sondage SET verr = 1 WHERE id = ?');
					$updateVERR->execute(array($_GET['id']));
					
					$updateVERR2 = $db->prepare('UPDATE vote SET verr2 = 1 WHERE id = ?');
					$updateVERR2->execute(array($_GET['id']));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
				elseif($_GET['verr'] == 2)
				{
					$updateVERR = $db->prepare('UPDATE sondage SET verr = 0 WHERE id = ?');
					$updateVERR->execute(array($_GET['id']));
					
					$updateVERR2 = $db->prepare('UPDATE vote SET verr2 = 0 WHERE id = ?');
					$updateVERR2->execute(array($_GET['id']));
					header('Location: index.php?p=sondage&id='.$_GET['id'].'');
				}
			}
			
			if(isset($_POST['return']))
			{
				header('Location: index.php?p=sondage');
			}
			?>
			<form method="POST">
				<input type="submit" name="return" value="Retour">
			</form>
			<br>
			<div class="nav">
				<ul>
					<li>
						<div class="link">
							<?php
											if($sondageinfo['verr'] == 1)
											{
												?>
												<span class="username-detail" style="color:#990000;font-weight: bold;">[Vérrouillé] </span>
												<?php
											}
											if($sondageinfo['rp'] == "HRP")
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #FF8A00">[HRP] </span>
												<?php
											}
											elseif($sondageinfo['rp'] == "RP")
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #55FF55">[RP] </span>
												<?php
											}
											?>
											<?=$sondageinfo['name']?>
							<br>
							<br>
							<?php
							if($_SESSION['rank'] >= 6)
							{
								if($sondageinfo['verr'] == 0)
								{
									?>
									<a href="index.php?p=sondage&id=<?=$_GET['id']?>&verr=1"><span class="username-detail" style="font-weight: bold; color: #990000" onmouseout="this.style.color='#990000'" onmouseover="this.style.color='white'">[Vérrouiller]</span></a>
									<?php
								}
								else
								{
									?>
									<a href="index.php?p=sondage&id=<?=$_GET['id']?>&verr=2"><span class="username-detail" style="font-weight: bold; color: lime" onmouseout="this.style.color='lime'" onmouseover="this.style.color='white'">[Dévérouiller]</span></a>
									<?php
								}
								?>
								<br>
								<br>
								<?php
							}
							?>
							<span class="username-detail" style="color: <?=$color?>;<?=$stylebasic?><?=$styledieu?>"><?=$player2['title']?> <?=$player2['username']?></span>
							<br>
							<?=$date?>
							<br>
							<br>
							<?=$msg?>
							<br>
							<br>
							<?php
							if($sondageinfo['verr'] == 0)
							{
								?>
								<a href="index.php?p=sondage&id=<?=$_GET['id']?>&yes=1">
									<img width="15%" src="pics/Accueil/Yes.png" onmouseover="this.src='pics/Accueil/YesBlink.png';" onmouseout="this.src='pics/Accueil/Yes.png';">
								</a>
								<a href="index.php?p=sondage&id=<?=$_GET['id']?>&neutral=1">
									<img width="15%" src="pics/Accueil/Neutral.png" onmouseover="this.src='pics/Accueil/NeutralBlink.png';" onmouseout="this.src='pics/Accueil/Neutral.png';">
								</a>
								<a href="index.php?p=sondage&id=<?=$_GET['id']?>&no=1">
									<img width="15%" src="pics/Accueil/No.png" onmouseover="this.src='pics/Accueil/NoBlink.png';" onmouseout="this.src='pics/Accueil/No.png';">
								</a>
								<?php
							}
							else
							{
								?>
								<img width="15%" src="pics/Accueil/Yes.png">
								<img width="15%" src="pics/Accueil/Neutral.png">
								<img width="15%" src="pics/Accueil/No.png">
								<?php
							}
							?>
						</div>
					</li>
				</ul>
			</div>
			<br>
			<br>
			<?php
			$reqvote2 = $db->prepare('SELECT yes, no, neutral, player FROM vote WHERE id = ? ORDER BY id asc');
			$reqvote2->execute(array($_GET['id']));
			?>
			<div class="nav">
				<ul>
					<li>
						<div class="link">
							Votes:
							<br>
							<br>
							<table width="100%">
								<tbody>
									<tr>
										<td align="center" valign="middle">
											<?php
											$selectyes = $db->prepare('SELECT * FROM vote WHERE yes = 1 AND id = ?');
											$selectyes->execute(array($_GET['id']));
											$yes = $selectyes->rowCount();
											?>
											<span style="color: green;font-weight: bold">
												<?=$yes?>
											</span>
										</td>
										<td align="center" valign="middle">
											<?php
											$selectneutral = $db->prepare('SELECT * FROM vote WHERE neutral = 1 AND id = ?');
											$selectneutral->execute(array($_GET['id']));
											$neutral = $selectneutral->rowCount();
											?>
											<span style="color: white;font-weight: bold">
												<?=$neutral?>
											</span>
										</td>
										<td align="center" valign="middle">
											<?php
											$selectno = $db->prepare('SELECT * FROM vote WHERE no = 1 AND id = ?');
											$selectno->execute(array($_GET['id']));
											$no = $selectno->rowCount();
											?>
											<span style="color: red;font-weight: bold">
												<?=$no?>
											</span>
										</td>
									</tr>
								</tbody>
							</table>
							<br>
							<br>
							<?php
							while($voteinfo2 = $reqvote2->fetch())
							{
								$reqmember = $db->prepare('SELECT * FROM member_list WHERE id = ?');
								$reqmember->execute(array($voteinfo2['player']));
								$player = $reqmember->fetch();
								
								if($voteinfo2['yes'] == 1)
								{
								?>
									<span class="username-detail" style="color: green"><?=$player['username']?> </span>
								<?php
								}
								elseif($voteinfo2['neutral'] == 1)
								{
								?>
									<span class="username-detail" style="color: white"><?=$player['username']?> </span>
								<?php
								}
								elseif($voteinfo2['no'] == 1)
								{
								?>
									<span class="username-detail" style="color: red"><?=$player['username']?> </span>
								<?php
								}
								
							}
							?>
						</div>
					</li>
				</ul>
			</div>
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
}
?>