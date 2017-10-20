<?php function karma()

{ global $db, $_POST, $_GET, $_SESSION;
if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
	$perso = intval($_GET['perso']);
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
$karmacount = $db->prepare('SELECT * FROM karma WHERE player = ? ORDER BY id asc');
$karmacount->execute(array($perso));

$positifrequest = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ? AND karma > 0');
$positifrequest->execute(array($perso));
									
$positif = $positifrequest->fetch();
									
$neutrerequest = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ? AND karma = 0');
$neutrerequest->execute(array($perso));
									
$neutre = $neutrerequest->fetch();
									
$negatifrequest = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ? AND karma < 0');
$negatifrequest->execute(array($perso));
									
$negatif = $negatifrequest->fetch();

$totalrequest = $db->prepare('SELECT SUM(karma) AS karma_total FROM karma WHERE player = ?');
$totalrequest->execute(array($perso));
									
$total = $totalrequest->fetch();
?>

<div class="nav">
	<ul>
		<li>
			<div class="link">
				<?php
							while($karma = $karmacount->fetch())
							{	
								if($karma['karma'] == 3)
								{
								?>
									<img height="24px" src="pics/Accueil/karma3.png" title="<?=$karma['detail']?>">
								<?php
								}
								elseif($karma['karma'] == 2)
								{
								?>
									<img height="24px" src="pics/Accueil/karma2.png" title="<?=$karma['detail']?>">
								<?php
								}
								elseif($karma['karma'] == 1)
								{
								?>
									<img height="24px" src="pics/Accueil/karma1.png" title="<?=$karma['detail']?>">
								<?php
								}
								elseif($karma['karma'] == 0)
								{
								?>
									<img height="24px" src="pics/Accueil/karma0.png" title="<?=$karma['detail']?>">
								<?php
								}
								elseif($karma['karma'] == -1)
								{
								?>
									<img height="24px" src="pics/Accueil/karma-1.png" title="<?=$karma['detail']?>">
								<?php
								}
								elseif($karma['karma'] == -2)
								{
								?>
									<img height="24px" src="pics/Accueil/karma-2.png" title="<?=$karma['detail']?>">
								<?php
								}
								elseif($karma['karma'] == -3)
								{
								?>
									<img height="24px" src="pics/Accueil/karma-3.png" title="<?=$karma['detail']?>">
								<?php
								}
								
							}
							?>
			</div>
		</li>
	</ul>
</div>

<table align="center" width="60%">
	<tbody>
		<tr>
			<td>
				<span class="username-detail" style="font-weight: bold; color: #55FF55">
					<?php
					if($positif['karma_total'] == 0)
					{
						?>
						Positif: [0]
						<?php
					}
					else
					{
						?>
						Positif: [<?=$positif['karma_total']?>]
						<?php
					}
					?>
				</span>
			</td>
			<td>
				<span class="username-detail" style="font-weight: bold; color: #AAAAAA">
					<?php
					if($neutre['karma_total'] == 0)
					{
						?>
						Neutre: [0]
						<?php
					}
					else
					{
						?>
						Neutre: [<?=$neutre['karma_total']?>]
						<?php
					}
					?>
				</span>
			</td>
			<td>
				<span class="username-detail" style="font-weight: bold; color: #FF5555">
					<?php
					if($negatif['karma_total'] == 0)
					{
						?>
						Négatif: [0]
						<?php
					}
					else
					{
						?>
						Négatif: [<?=$negatif['karma_total']?>]
						<?php
					}
					?>
				</span>
			</td>
			<td>
				<?php
												if($total['karma_total'] >= 10)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #00AA00">
														Total: [<?= $total['karma_total']?>]
													</span>
													<?php
												}
												elseif($total['karma_total'] >= 1)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #55FF55">
														Total: [<?= $total['karma_total']?>]
													</span>
													<?php
												}
												elseif($total['karma_total'] == 0)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #AAAAAA">
														Total: [0]
													</span>
													<?php
												}
												elseif($total['karma_total'] <= -10)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #AA0000">
														Total: [<?= $total['karma_total']?>]
													</span>
													<?php
												}
												elseif($total['karma_total'] <= -1)
												{
													?>
													<span class="username-detail" style="font-weight: bold; color: #FF5555">
														Total: [<?= $total['karma_total']?>]
													</span>
													<?php
												}
												?>
			</td>
		</tr>
	</tbody>
</table>

<?php
	if ($userinfo['pnj'] == 2)
			{
			$stylebasic = false;
			$styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
			}
			else
			{
			$stylebasic = "text-shadow: 2px 2px 2px #000000;";
			$styledieu = false;
			}
			
	switch ($userinfo['rank'])
			{
				default : $color = "#555550"; break;
				case 1:  $color = "#00AA00"; $color = ($userinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($userinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 2: $color = "#55FF55"; $color = ($userinfo['actif'] == 1)? "#FF5555" : $color;
				$color = ($userinfo['digni'] == 3)? "#5555FF" : $color; break;
				case 3: $color = "#FF55FF"; break;
				case 4: $color = "#00AAAA"; $color = ($userinfo['pnj'] == 1)? "#AAAAAA" : $color;
				$color = ($userinfo['pnj'] == 3)? "#55FFFF" : $color;
				$color = ($userinfo['digni'] == 2)? "#FFFF55" : $color; break;
				case 5: $color = "#FFAA00"; $color = ($userinfo['pnj'] == 2)? "#0200A6" : $color;
				$color = ($userinfo['digni'] == 1)? "#AA00AA" : $color; break;
				case 6: $color = "#AA0000"; break;
				case 7: $color = "#000000"; break;
			}
?>

<p>
	Voici le Karma de <span style="color:<?= $color?>;<?= $styledieu?><?= $stylebasic?>font-weight: bold;font-size: 1.1em;"><?= $userinfo['username']?></span>
	<br>C'est l'ensemble de tout vos actes, bons ou mauvais.
	<br>Faîtes attention à ne pas dépasser un certain seuil...

<?php
}

}
?>