<?php function questlist()
{
	global $db, $_SESSION, $_POST, $_GET;
	if($_SESSION['rank'] >= 4)
	{
		$selectquest = $db->query('SELECT * FROM quest_list ORDER BY validmj ASC, groupacess ASC, id ASC');
		while($questlist = $selectquest->fetch())
		{	
			if($_SESSION['rank'] >= 6)
			{
				if(isset($_GET['validate']))
					{
						$ifvalid = $db->prepare('UPDATE quest_list SET validmj = ? WHERE id = ?');
						$ifvalid->execute(array($_SESSION['id'], $_GET['onload']));
						header('Location: index.php?p=questlist');
					}
				
				if(isset($_GET['refused']))
					{
						$ifrefused = $db->prepare('DELETE FROM quest_list WHERE id = ?');
						$ifrefused->execute(array($_GET['onload']));
						header('Location: index.php?p=questlist');
					}
			}
					
			$selectmember = $db->prepare('SELECT * FROM member_list WHERE id = ?');
			$selectmember->execute(array($questlist['author']));
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
				
				$selectmember2 = $db->prepare('SELECT * FROM member_list WHERE id = ?');
				$selectmember2->execute(array($questlist['validmj']));
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
				
				$desc = preg_replace('#\n#', '<br>', $questlist['desc']);
				$ifvalid = preg_replace('#\n#', '<br>', $questlist['ifvalid']);
				$ifrefused = preg_replace('#\n#', '<br>', $questlist['ifrefused']);
			
			if($_SESSION['rank'] >= $questlist['rankvalid'])
			{
				if($_GET['onload'] != $questlist['id'])
				{
					if($questlist['validmj'] == 0)
					{
						?>
						<div valign="middle" class="nav" align="center" width="95%">
						<?php
					}
					elseif($questlist['validmj'] >= 1)
					{
						?>
						<div valign="middle" class="navvalid" align="center" width="95%">
						<?php
					}
					?>
						<ul>
							<li>
								<?php
								if($questlist['validmj'] == 0)
								{
									?>
									<div valign="middle" class="link" style="padding: 20px;">
									<?php
								}
								elseif($questlist['validmj'] >= 1)
								{
									?>
									<div valign="middle" class="linkvalid" style="padding: 20px;">
									<?php
								}
								?>
										<table style="border-collapse: separate;border-spacing: 2px;" width="100%">
											<tbody>
												<tr>
													<td align="left">
														<?php
														if($questlist['rp'] == 0)
														{
															?>
															<span valign="middle" class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
															<?php
														}
														elseif($questlist['rp'] == 1)
														{
															?>
															<span valign="middle" class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
															<?php
														}
														?>
														<a valign="middle" href="index.php?p=questlist&onload=<?=$questlist['id']?>">
															<?=$questlist['quest']?>
														</a>
													</td>
													<td width="300px" align="right">
														<img valign="middle" style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;" width="300px" src="pics/quest/quest_<?=$questlist['id']?>.png">
													</td>
												</tr>
											</tbody>
										</table>
								</div>
							</li>
						</ul>
					</div>
					<?php
				}
				elseif($_GET['onload'] == $questlist['id'])
				{
					if($questlist['validmj'] == 0)
					{
						?>
						<div valign="middle" class="nav" align="center" width="95%">
						<?php
					}
					elseif($questlist['validmj'] >= 1)
					{
						?>
						<div valign="middle" class="navvalid" align="center" width="95%">
						<?php
					}
					?>
						<ul>
							<li>
								<?php
								if($questlist['validmj'] == 0)
								{
									?>
									<div valign="middle" class="link" style="padding: 20px;">
									<?php
								}
								elseif($questlist['validmj'] >= 1)
								{
									?>
									<div valign="middle" class="linkrefused" style="padding: 20px;">
									<?php
								}
								?>
									<?php
											if($questlist['rp'] == 0)
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #FF8A00"> [HRP]</span>
												<?php
											}
											elseif($questlist['rp'] == 1)
											{
												?>
												<span class="username-detail" style="font-weight: bold; color: #55FF55"> [RP]</span>
												<?php
											}
											?>
											<a href="index.php?p=questlist">
												<?=$questlist['quest']?>
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
											<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/quest/quest_<?=$questlist['id']?>.png">
											<?php
											if($questlist['previous_quest'] >= 1)
											{
												?>
												<br>
												<br>
												Quête précédente:
												<br>
												<?php
												$selectprevious = $db->prepare('SELECT * FROM quest_list WHERE id = ?');
												$selectprevious->execute(array($questlist['previous_quest']));
												$previous = $selectprevious->fetch();
												?>
												<a href="index.php?p=questlist&onload=<?=$previous['id']?>" style="font-weight: bold;">
													<?=$previous['quest']?>
												</a>
												<?php
											}
											?>
											<br>
											<br>
											Description:
											<br>
											<br>
											<?=$desc?>
											<br>
											<br>
											Si validé:
											<br>
											<br>
											<span style="color: green"><?=$ifvalid?></span>
											<br>
											<br>
											Si refusé:
											<br>
											<br>
											<span style="color: darkred"><?=$ifrefused?></span>
											<br>
											<br>
											<?php
											if($questlist['validmj'] >= 1)
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
												<?php
												if($questlist['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questattendu/quest_<?=$questlist['id']?>.png">
												<?php
												}
												elseif($questlist['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questlist['textattendu']?></span>
												<?php
												}
											}
											elseif($questlist['validmj'] == 0)
											{
												?>
												En cours de Validation !
												<br>
												<br>
												<?php
												if($questlist['answer'] == 0)
												{
												?>
												<img style="box-shadow: 0px 0px 5px #000000;border-radius: 10px;width: 100%;max-width: 900px;" src="pics/questattendu/quest_<?=$questlist['id']?>.png">
												<?php
												}
												elseif($questlist['answer'] == 1)
												{
												?>
												<span style="color: darkred;font-weight: bold;font-size: 15px;"><?=$questlist['textattendu']?></span>
												<?php
												}
												?>
												<br>
												<br>
												<?php
												if($_SESSION['rank'] >= 6)
												{
													?>
													<a href="index.php?p=questlist&onload=<?=$_GET['onload']?>&validate=1"><span class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'">[Valider !]</span></a>
													<a href="index.php?p=questlist&onload=<?=$_GET['onload']?>&refused=1"><span class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'">[Refuser...]</span></a>
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