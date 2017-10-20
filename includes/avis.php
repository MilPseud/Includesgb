<?php function avis()
{	global $db, $_POST, $_SESSION, $_GET;
	
	if(isset($_GET['perso']) AND $_GET['perso'] > 0)
	{
	
	$perso = $_GET['perso'];
	?>
	<div class="nav">
		<ul>
			<li>
				<div class="link">
					<?php
					$reqavis2 = $db->prepare('SELECT a.id, a.player, a.player2, a.avis, m.id AS m_id, m.username, m.title FROM avis a RIGHT JOIN member_list m ON a.player = m.id WHERE a.player2 = ? ORDER BY a.id asc');
					$reqavis2->execute(array($perso));
					$avisinfo2 = $reqavis2->rowCount();
					
					$reqavis = $db->prepare('SELECT a.id, a.player, a.player2, a.avis, m.id AS m_id, m.username, m.title FROM avis a RIGHT JOIN member_list m ON a.player = m.id WHERE a.player2 = ? ORDER BY a.id asc');
					$reqavis->execute(array($perso));
					
					if($_SESSION['id'] != $perso)
					{
						if(isset($_GET['plus']))
						{
							$countplus = $db->prepare('SELECT * FROM avis WHERE player = ? AND player2 = ?');
							$countplus->execute(array($_SESSION['id'], $perso));
							$count1 = $countplus->rowCount();
							
							if($count1 == 0)
							{
								$insertplus = $db->prepare('INSERT INTO avis VALUES(?, ?, ?, ?)');
								$insertplus->execute(array(NULL, $_SESSION['id'], $perso, '1'));
								header('Location: index.php?p=avis&perso='.$perso.'');
							}
							else
							{
								$updateplus = $db->prepare('UPDATE avis SET avis = 1 WHERE player = ? AND player2 = ?');
								$updateplus->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=avis&perso='.$perso.'');
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
								header('Location: index.php?p=avis&perso='.$perso.'');
							}
							else
							{
								$updatemoins = $db->prepare('UPDATE avis SET avis = -1 WHERE player = ? AND player2 = ?');
								$updatemoins->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=avis&perso='.$perso.'');
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
								header('Location: index.php?p=avis&perso='.$perso.'');
							}
							else
							{
								$updatezero = $db->prepare('UPDATE avis SET avis = 0 WHERE player = ? AND player2 = ?');
								$updatezero->execute(array($_SESSION['id'], $perso));
								header('Location: index.php?p=avis&perso='.$perso.'');
							}
						}
					}
					?>
					<table align="center" width="95%">
						<tbody>
							<?php
							if($avisinfo2 >= 1)
							{
								while($avisinfo = $reqavis->fetch())
								{
									?>
									<tr>
										<td align="left" valign="middle">
											<?=$avisinfo['username']?>
										</td>
										<td align="right" valign="middle">
											<?php
											if($avisinfo['avis'] >= 1)
											{
												?>
												+<?=$avisinfo['avis']?>
												<?php
											}
											elseif($avisinfo['avis'] == 0)
											{
												?>
												0
												<?php
											}
											elseif($avisinfo['avis'] <= -1)
											{
												?>
												<?=$avisinfo['avis']?>
												<?php
											}
											?>
										</td>
									</tr>
									<?php
								}
								?>
								<tr>
									<td colspan="2" align="center" valign="middle">
										TOTAL: <?php
											
											$rowplus = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player2 = ?');
											$rowplus->execute(array($perso));
											$plus = $rowplus->rowCount();
											
											$rowmoins = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player2 = ?');
											$rowmoins->execute(array($perso));
											$moins = $rowmoins->rowCount();
											
											$total = $plus - $moins;
											
												if($total >= 10)
												{
													?>
														<a class="username-detail" style="font-weight: bold; color: #55FF55" onmouseout="this.style.color='#55FF55'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>">
													<?php
												}
												elseif($total >= 1)
												{
													?>
														<a style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>">
													<?php
												}
												elseif($total <= -1)
												{
													?>
														<a style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>">
													<?php
												}
												elseif($total <= -10)
												{
													?>
														<a style="font-weight: bold; color: #FF5555" onmouseout="this.style.color='#FF5555'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>">
													<?php
												}
												?>
											<?php
											if($total >= 1)
											{
												?>
												[+<?=$total?>]
												<?php
											}
											else
											{
												?>
												[<?=$total?>]
												<?php
											}
											?></a>
											<?php
											if(($_SESSION['rank'] >= 4 OR $_SESSION['digni'] != 0) AND $_SESSION['id'] != $perso)
											{
											
											$rowplus2 = $db->prepare('SELECT avis FROM avis WHERE avis = 1 AND player = ? AND player2 = ?');
											$rowplus2->execute(array($_SESSION['id'], $perso));
											$plus2 = $rowplus2->rowCount();
											
											$rowmoins2 = $db->prepare('SELECT avis FROM avis WHERE avis = -1 AND player = ? AND player2 = ?');
											$rowmoins2->execute(array($_SESSION['id'], $perso));
											$moins2 = $rowmoins2->rowCount();
											
											$rowzero2 = $db->prepare('SELECT avis FROM avis WHERE avis = 0 AND player = ? AND player2 = ?');
											$rowzero2->execute(array($_SESSION['id'], $perso));
											$zero2 = $rowzero2->rowCount();

												if($plus2 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #00AA00" onmouseout="this.style.color='#00AA00'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>&plus=1">[+1]</a>
												<?php
												}
												if($zero2 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #AAAAAA" onmouseout="this.style.color='#AAAAAA'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>&zero=1">[0]</a>
												<?php
												}
												if($moins2 == 0)
												{
												?>
													<a class="username-detail" style="font-weight: bold; color: #AA0000" onmouseout="this.style.color='#AA0000'" onmouseover="this.style.color='white'" href="index.php?p=avis&perso=<?=$perso?>&moins=1">[-1]</a>
												<?php
												}
											}
											?>
									</td>
								</tr>
								<?php
							}
							else
							{
							?>
							<tr>
								<td>
									Vous n'avez pas d'avis pour le moment !
								</td>
							</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</li>
		</ul>
	</div>
	<?php
	}
}
?>