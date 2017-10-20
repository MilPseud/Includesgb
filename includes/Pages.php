<?php function Pages()
{
	global $db, $_GET;
	$page = (isset($_GET['p'])) ? $_GET['p'] : '';
	
	$reqreload = $db->prepare("SELECT * FROM member_list WHERE id = ?");
    $reqreload->execute(array($_SESSION['id']));
	
	if($_SESSION['connected'] == true)
	{
		$reloadinfo = $reqreload->fetch();
      	$_SESSION['username'] = $reloadinfo['username'];
        $_SESSION['mail'] = $reloadinfo['mail'];
        $_SESSION['color'] = $reloadinfo['color'];
        $_SESSION['rank'] = $reloadinfo['rank'];
       	$_SESSION['nom'] = $reloadinfo['nom'];
        $_SESSION['title'] = $reloadinfo['title'];
		$_SESSION['pseudo'] = $reloadinfo['pseudo'];
		$_SESSION['pnj'] = $reloadinfo['pnj'];
		$_SESSION['digni'] = $reloadinfo['digni'];
		$_SESSION['actif'] = $reloadinfo['actif'];
		$_SESSION['ban'] = $reloadinfo['ban'];
		$_SESSION['desert'] = $reloadinfo['desert'];
		$_SESSION['connected'] = true;
		$_SESSION['userinfo'] = true;
	}
	
	$Ban = $db->prepare("SELECT id, username, rank, ban, desert FROM member_list WHERE id = ?");
	$Ban->execute(array($_SESSION['id']));
	$Ban2 = $Ban->fetch();
	
	$Timer = $db->prepare("UPDATE member_list SET last_action = NOW() WHERE id = ?");
	$Timer->execute(array($_SESSION['id']));
	
	
	

	if($Ban2['ban'] == 1)
	{
	header('Location: index.php?p=deconnect');
	}
	
	if($Ban2['desert'] == 1)
	{
	header('Location: index.php?p=deconnect');
	}

	?>
		<div width="100%">
			<table width="100%" cellspacing="0" cellpadding="0">
				<tbody>
					<tr class="header">
						<td colspan="2">
							<table width="100%" cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td width="12%">
											<a href="index.php">
												<img src="pics/gslog.png" alt="" width="85px" style="text-align:center;"/>
												<img src="pics/gslog2.png" alt="" width="60%" style="text-align:center;"/>
											</a>
										</td>
										<td style="text-align:right; margin-right:1%;font-weight:bold;">
											<?php
											$allnews = $db->query('SELECT * FROM news ORDER BY id DESC LIMIT 0, 1');
											while($news = $allnews->fetch())
											{
											?>
												<table>
													<tbody>
														<tr>
															<? echo $news['news']; ?>
														</tr>
													</tbody>
												</table>
											<?php
											}
											?>
										</td>
										<td width="5%">
											<div width="100%" valign="center">
												<a href="https://minecraft.net/fr/">
													<img src="pics/minecraftlogo.png" alt="" target=_blank style="text-align:right;margin:1%;" title="Site officiel de Minecraft" onmouseover="this.src='pics/minecraftlogo2.png';" onmouseout="this.src='pics/minecraftlogo.png';">
												</a>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td rowspan="2" width="15%" valign="top">
							<?php include ('includes/nav.php'); nav(); ?>
						</td>
						<td valign="top">
							<?php include ('includes/alert.php'); alert(); ?>
							<div class="main">
								<?php
								switch ($page)
									{
										default : { include('includes/home.php'); break; }
										case '': { include('includes/home.php'); home(); break; }
										case 'members': { include('includes/members.php'); members(); break; }
										case 'connect': { include('includes/connect.php'); connect(); break; }
										case 'deconnect': { include('includes/deconnect.php'); deconnect(); break; }
										case 'register': { include('includes/register.php'); register(); break; }
										case 'rules': { include('includes/rules.php'); rules(); break; }
										case 'staff': { include('includes/staffteam.php'); staff(); break; }
										case 'staffedit': { include('includes/staffteamedit.php'); staffedit(); break; }
										case 'history': { include('includes/history.php'); history(); break; } 
										case 'convo': { include('includes/convo.php'); convo(); break; }
										case 'atlas': { include('includes/atlas.php'); atlas(); break; }
										case 'news': { include('includes/news.php'); news(); break; }
										case 'mp': { include('includes/mp.php'); mp(); break; }
										case 'chatbox': { include('includes/chatboxfull.php'); chatbox(); break; }
										case 'chatboxAjax': { include('includes/chatboxAjax.php'); chatboxAjax(); break; }
										case 'profile': { include('includes/profile.php'); profile(); break; }
										case 'editprofile': { include('includes/editprofile.php'); editprofile(); break; }
										case 'editprofilemj': { include('includes/editprofilemj.php'); editprofilemj(); break; }
										case 'race': { include('includes/race.php'); race(); break; } 
										case 'forum': { include('includes/forum.php'); forum(); break; }
										case 'forumg': { include('includes/forum.php'); forum(); break; }
										case 'grades': { include('includes/grades.php'); grades(); break; } 
										case 'alert': { include('includes/alert.php'); alert(); break; } 
										case 'candid': { include('includes/candid.php'); candid(); break; } 
										case 'candidmj': { include('includes/candidmj.php'); candidmj(); break; }
										case 'faq': { include('includes/faq.php'); faq(); break; }
										case 'sucesskey': { include('includes/key.php'); sucesskey(); break; }
										case 'achievement': { include('includes/achievement.php'); achievement(); break; } 
										case 'achievementmj': { include('includes/achievementmj.php'); achievementmj(); break; }
										case 'keylist': { include('includes/keylist.php'); keylist(); break; }
										case 'lexique': { include('includes/lexique.php'); lexique(); break; }
										case 'testpage': { include('includes/testpage.php'); testpage(); break; } 
										case 'guilds': { include('includes/guilds.php'); guilds(); break; }
										case 'competence': { include('includes/competence.php'); competence(); break; }
										case 'competencelist': { include('includes/competencelist.php'); competencelist(); break; }
										case 'sondage': { include('includes/sondage.php'); sondage(); break; }
										case 'enca': { include('includes/enca.php'); enca(); break; }
										case 'avis': { include('includes/avis.php'); avis(); break; }
										case 'quest': { include('includes/quest.php'); quest(); break; }
										case 'questlist': { include('includes/questlist.php'); questlist(); break; }
										case 'questcrea': { include('includes/questcrea.php'); questcrea(); break; }
										case 'support': { include('includes/support.php'); support(); break; }
										case 'delaccount': { include('includes/delete.php'); delaccount(); break; }
										case 'karma': { include('includes/karma.php'); karma(); break; }
										case 'sorts': { include('includes/sorts.php'); sorts(); break; }
										case 'aptitudes': { include('includes/aptitudes.php'); aptitudes(); break; }
										case 'events': { include('includes/events.php'); events(); break; }
										case 'calendrier': { include('includes/calendrier.php'); calendrier(); break; }
										case 'morts': { include('includes/morts.php'); morts(); break; }
										case '400': { include('includes/400.php'); break; }
										case '401': { include('includes/401.php'); break; }
										case '403': { include('includes/403.php'); break; }
										case '404': { include('includes/404.php'); break; }
										case '500': { include('includes/500.php'); break; }
									}
								?>
							</div>
							<?php
							if (function_exists('chatbox'))
							{
							}
							else
							{
							?>
								<div class="main">
							<?php
							include ('includes/chatbox.php'); chatbox();
							?>
								</div>
							<?php
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<td colspan="2" class="footer">
							GaaranStröm est un serveur à destination des joueurs de Minecraft. Le contenu de ce serveur est une fiction - 2016
							<br>L'ensemble du Code de GaaranStröm appartient à Elenyah et Nikho - 2017
							<br>Tout droits réservés à Brumen - 2017
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php
}
?>