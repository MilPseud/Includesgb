<?php function forum ()
{
	global $db, $_GET, $_SESSION;
	
	$view = (isset($_SESSION['connected'])) ? $_SESSION['rank'] : 0;
	$guild_access = (isset($_GET['p']) AND $_GET['p'] == "forumg")? true : false;
	$global_access = true;
	
	//Défini les liens de redirection, Généraux ou de Guilde
	$f_method = ($_GET['p'] == "forumg")? "forumg" : "forum";
	
	
	$moderator_access = ($_SESSION['rank'] > 4)? true : false;
	$admin_access = ($_SESSION['rank'] > 5)? true : false;
	$cadre_access = ($_SESSION['rank'] > 3)? true : false;
	
	/*
	$admin_access = false;
	$moderator_access = false;
	$cadre_access = false;
	*/
	?>
	<style>
		.member_top
		{
			background-color: #FFD700;
		}
		.forumbg
		{
			margin: 5px;
			padding: 1%;
			border: gold double 2px;
			background-color: #CD853F;
		}
		.forumf
		{
			background-color: #A0522D;
		}
		.forumrank0
		{
			    background-color: rgba(160, 82, 45, 0.2);
		}
		.forumrank1
		{
			    background-color: rgba(160, 82, 45, 0.3);
		}
		.forumrank2
		{
			    background-color: rgba(160, 82, 45, 0.4);
		}
		.forumrank3
		{
			    background-color: rgba(160, 82, 45, 0.5);
		}
		.forumrank4
		{
			    background-color: rgba(160, 82, 45, 0.6);
		}
		.forumrank5
		{
			    background-color: rgba(160, 82, 45, 0.7);
		}
		.forumrank6
		{
			    background-color: rgba(160, 82, 45, 0.8);
		}
		.forumrank7
		{
			    background-color: rgba(160, 82, 45, 0.9);
		}
		.red
		{
			color: #FF0000;
			text-shadow: 2px 2px 2px #000000;
			font-weight: bold;
			font-size: 1.1em;
		}
		.black
		{
			color: #000000;
			text-shadow: 2px 2px 2px #000000;
			font-weight: bold;
			font-size: 1.1em;
		}
		

	</style>
	
	<h2>Forums</h2>
	
	<?php
	if (isset($_GET['forum']))
	{
		$global_access = false;
		$forum = intval($_GET['forum']);
		
		//Boutons
		if (isset($_GET['del']))
		{
			if ($moderator_access)
			{
				$delete = intval($_GET['del']);
				$del = $db->prepare('UPDATE forum_post SET del = 1, deleter_id = ? WHERE id = ?');
				$del->execute(array($_SESSION['id'], $delete));
				$msg = "Le post a bien été supprimé.";
			}
		}
		elseif (isset($_GET['restore']))
		{
			if ($admin_access)
			{
				$restore = intval($_GET['restore']);
				$rest = $db->prepare('UPDATE forum_post SET del = 0, deleter_id = 0 WHERE id = ?');
				$rest->execute(array($restore));
				$msg = "Le post a bien été restauré.";
			}
		}
		elseif (isset($_GET['setp']))
		{
			if ($admin_access)
			{
				$ano = intval($_GET['setp']);
				$rest = $db->prepare('UPDATE forum_post SET unknow = 0 WHERE id = ?');
				$rest->execute(array($ano));
				$msg = "Le post a bien été rendu public.";
			}
		}
		elseif (isset($_GET['seta']))
		{
			if ($admin_access)
			{
				$ano = intval($_GET['seta']);
				$rest = $db->prepare('UPDATE forum_post SET unknow = 1 WHERE id = ?');
				$rest->execute(array($ano));
				$msg = "Le post a bien été rendu anonyme.";
			}
		}
		
		//Nouveau Post
		if (isset($_POST['sendnew']))
		{
			if (isset($_POST['edit']))
			{
				if ($admin_access)
				{
					$ok_to_edit = true;
					$method = "edit";
				}
				elseif ($cadre_access)
				{
					$verif = $db->prepare("SELECT f.id AS f_id, f.user_id, m.id, m.rank FROM forum_post f
					RIGHT JOIN member_list m ON f.user_id = m.id
					WHERE f.id = ?");
					$verif->execute(array(intval($_POST['edit'])));
					if ($v = $verif->fetch())
					{
						if ($_SESSION['rank'] > $v['rank'] OR $_SESSION['id'] == $v['user_id'])
						{
							$ok_to_edit = true;
							$method = "edit";
						}
						else
						{
							$ok_to_edit = false;
						}
					}
					else
					{
						$ok_to_edit = false;
					}
				}
				else
				{
					$verif = $db->prepare("SELECT id, user_id, post_date, post_date + interval '5' minute AS max_date FROM forum_post WHERE id = ?");
					$verif->execute(array(intval($_POST['edit'])));
					
					if ($v = $verif->fetch())
					{
						if ($_SESSION['id'] == $v['user_id'])
						{
							$now = date("Y-m-d H:i:s");
							$now = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}):(.{2}):(.{2})$#', '$1$2$3$4$5$6', $now);
							$maxtime = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}):(.{2}):(.{2})$#', '$1$2$3$4$5$6', $v['max_date']);
							if ($now < $maxtime)
							{
								$ok_to_edit = true;
								$method = "edit";
							}
							else
							{
								$ok_to_edit = false;
							}
						}
						else
						{
							$ok_to_edit = false;
						}
					}
					else
					{
						$ok_to_edit = false;
					}
				}
			}
			else
			{
				$ok_to_edit = true;
				$method = "new";
			}
			
			if ($ok_to_edit)
			{
				$text = (!empty($_POST['newpost']))? htmlspecialchars($_POST['newpost']) : "Message vierge (à supprimer)";
				$anonyme = (isset($_POST["sendunknow"]))? 1 : 0;
				if ($moderator_access)
				{
					$text = preg_replace('#(?<!\|)\(b\)([^<>]+)\(/b\)#isU', '<span style="font-weight: bold;">$1</span>', $text);
					$text = preg_replace('#(?<!\|)\(i\)([^<>]+)\(/i\)#isU', '<span style="font-style: italic;">$1</span>', $text);
					$text = preg_replace('#(?<!\|)\(u\)([^<>]+)\(/u\)#isU', '<span style="text-decoration: underline;">$1</span>', $text);
					$text = preg_replace('#(?<!\|)\(a (https?://[a-z0-9._\-/&\?^()]+)\)([^<>]+)\(/a\)#isU', '<a href="$1" style="color: #FF8D1C;">$2</a>', $text);
					$text = preg_replace('#(?<!\|)\(img (https?://[a-z0-9._\-/&\?^()]+)\)#isU', '<img src="$1" width="100%" alt=" "/>', $text);
					$text = preg_replace('#(?<!\|)\(c ([^<>]+)\)([^<>]+)\(/c\)#isU', '<span style="color: $1">$2</span>', $text);
				}
				
				if (isset($_FILES['send_img']))
				{
					if ($_FILES['send_img']['error'] == 0)
					{
						if ($_FILES['send_img']['size'] <= 10000000)
						{
							$info_img = pathinfo($_FILES['send_img']['name']);
							$ext_img = $info_img['extension'];
							$ext_ok = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
							
							if (in_array($ext_img, $ext_ok))
							{
								$code = md5(uniqid(rand(), true));
								$name = "pics/uploads/". $code . ".png";
								$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
								$is_image = 1;
							}
							else
							{
								$is_image = 0;
								$code = false;
							}
						}
						else
						{
							$is_image = 0;
							$code = false;
						}
					}
					else
					{
						$is_image = 0;
						$code = false;
					}
				}
				else
				{
					$is_image = 0;
					$code = false;
				}
				
				if ($method == "edit")
				{
					//Vérifie s'il y a déjà une image
					$verify = $db->prepare("SELECT id, is_image FROM forum_post WHERE id = ?");
					$verify->execute(array(intval($_POST['edit']))); $vv = $verify->fetch();
					$already_image = ($vv['is_image'] == 1)? true : false;
					
					if ($is_image == 1)
					{
						$del_img = 1;
					}
					else
					{
						if ($already_image)
						{
							$del_img = (isset($_POST['del_img']))? 0 : 1;
						}
						else
						{
							$del_img = 0;
						}
					}
					
					
					$update = $db->prepare("UPDATE forum_post SET post = ?, is_image = ?, img_code = ? WHERE id = ?");
					$update->execute(array($text, $del_img, $code, intval($_POST['edit'])));
				}
				else
				{
					$add = $db->prepare("INSERT INTO forum_post VALUES('', ?, NOW(), ?, ?, ?, 0, 0, ? , ?)");
					$add->execute(array($text, $_SESSION['id'], $forum, $anonyme, $is_image, $code));
					
					$mbr = $db->prepare("SELECT * FROM member_list WHERE id = ?");
					$mbr->execute(array($_SESSION['id']));
					$userinfo = $mbr->fetch();
					
					$addmsg = $db->prepare("UPDATE member_list SET msg = ? WHERE id = ?");
					$addmsg->execute(array($userinfo['msg'] + 1, $_SESSION['id']));
					
					// [à mettre] MàJ unread
					$update = $db->prepare("UPDATE forum_unread SET unread = 1 WHERE forum_id = ?");
					$update->execute(array($forum));
					
					// MàJ du last_post
					$temp = $db->prepare('SELECT id, forum_id FROM forum_post WHERE forum_id = ? ORDER BY id DESC LIMIT 1');
					$temp->execute(array($forum)); $t = $temp->fetch();
					$last = $db->prepare('UPDATE forum_forum SET last_post = ?, last_activity = NOW() WHERE id = ?');
					$last->execute(array($t['id'], $forum));
				}
			}
		}
		
		//Affichage
		$forum = intval($_GET['forum']);
		
		$verify = $db->prepare('SELECT c.id, f.category, f.id AS f_id, c.rank FROM forum_category c RIGHT JOIN forum_forum f ON f.category = c.id WHERE f.id = ?');
		$verify->execute(array($forum));
		$verify = $verify->fetch();
		
		if ($verify['rank'] <= $view)
		{
			$fcat = $db->prepare('SELECT * FROM forum_category WHERE id = ?');
			$fcat->execute(array($verify['category']));
			if ($fcat = $fcat->fetch())
			{
				$local_access = ($guild_access)? $fcat['guild'] : '0';
				if ($fcat['guild'] == $local_access)
				{
					if ($_GET['p'] == "forumg")
					{
						if ($admin_access)
						{
							$this_access = true;
						}
						else
						{
							$verif = $db->prepare("SELECT * FROM group_members WHERE group_id = ? AND user_id = ?");
							$verif->execute(array($fcat['guild'], $_SESSION['id']));
							if ($verif->fetch())
							{
								$this_access = true;
							}
							else
							{
								$this_access = false;
							}
						}
					}
					else
					{
						$this_access = true;
					}
					
					if ($this_access)
					{
						//Pagination
						$page = (isset($_GET['page']) AND $_GET['page'] > 0)? intval($_GET['page']) : 1;
						
						//Commandes - bouttons
						if ($admin_access)
						{
							if (isset($_POST['important']))
							{
								$update = $db->prepare('UPDATE forum_forum SET important = 1 WHERE id = ?');
								$update->execute(array($forum));
								$msg = "Le sujet a bien été défini comme important !";
							}
							elseif (isset($_POST['normal']))
							{
								$update = $db->prepare('UPDATE forum_forum SET important = 0 WHERE id = ?');
								$update->execute(array($forum));
								$msg = "Le sujet a bien été défini comme standard.";
							}
							elseif (isset($_POST['rp'])) 
							{
								$update = $db->prepare('UPDATE forum_forum SET rp = 1 WHERE id = ?');
								$update->execute(array($forum));
								$msg =  "Le sujet a bien été rendu RP !";
							}
							elseif (isset($_POST['hrp']))
							{
								$update = $db->prepare('UPDATE forum_forum SET rp = 0 WHERE id = ?');
								$update->execute(array($forum));
								$msg = "Le sujet a bien été rendu HRP.";
							}
							elseif (isset($_POST['lock']))
							{
								$update = $db->prepare('UPDATE forum_forum SET locked = 1, locker_id = ? WHERE id = ?');
								$update->execute(array($_SESSION['id'], $forum));
								$msg = "Le sujet a bien été vérouillé.";
							}
							elseif (isset($_POST['unlock']))
							{
								$update = $db->prepare('UPDATE forum_forum SET locked = 0, locker_id = 0 WHERE id = ?');
								$update->execute(array($forum));
								$msg = "Le sujet a bien été déverrouillé !";
							}
							elseif (isset($_POST['del']))
							{
								$update = $db->prepare('UPDATE forum_forum SET del = 1, deleter_id = ? WHERE id = ?');
								$update->execute(array($_SESSION['id'], $forum));
								$msg = "Le sujet a bien été supprimé.";
							}
							elseif (isset($_POST['restore']))
							{
								$update = $db->prepare('UPDATE forum_forum SET del = 0, deleter_id = 0 WHERE id = ?');
								$update->execute(array($forum));
								$msg = "Le sujet a bien été réstauré !";
							}
							else
							{
								$msg = false;
							}
						}
						
						//Séléction du nom
						$fname = $db->prepare('SELECT fc.id, fc.name AS fc_name, ff.name, ff.id AS ff_id, ff.category, ff.locker_id, ff.deleter_id, ff.rp, ff.important, ff.del, ff.locked FROM forum_category fc
						RIGHT JOIN forum_forum ff ON fc.id = ff.category
						WHERE ff.id = ?');
						$fname->execute(array($forum));
						$fname = $fname->fetch();
						
						//MàJ de f_unread
						$verif = $db->prepare("SELECT * FROM forum_unread WHERE forum_id = ? AND user_id = ?");
						$verif->execute(array($forum, $_SESSION['id']));
						if ($verif->fetch())
						{
							$update = $db->prepare('UPDATE forum_unread SET unread = 0, page = ? WHERE user_id = ? AND forum_id = ?');
							$update->execute(array($page, $_SESSION['id'], $forum));
						}
						else
						{
							$add = $db->prepare("INSERT INTO forum_unread VALUES('', ?, ?, 0, 1)");
							$add->execute(array($forum, $_SESSION['id']));
						}
						
						
						if (!$admin_access)
						{
							$fcount = $db->prepare('SELECT COUNT(*) AS pages FROM forum_post WHERE forum_id = ? AND del = 0'); $fcount->execute(array($forum));
							$count = $fcount->fetch();
							$msg_par_page = 10;
							
							//S'il y a 0 message à afficher
							$count['pages'] = ($count['pages'] == 0)? 1 : $count['pages'];
							
							$pagesmax = ceil($count['pages'] / $msg_par_page);
							if(isset($_GET['page']) AND $_GET['page'] != 0) 
							{
								$page = intval($_GET['page']);

								if($page > $pagesmax)
								{
									$page = 1;
								}
							}
							else
							{
								 $page = 1; 
							}
							$first = ($page - 1) * $msg_par_page;
		
							$select = $db->query("SELECT * FROM forum_post WHERE forum_id = $forum AND del = 0 ORDER BY post_date ASC LIMIT $first, $msg_par_page");
						}
						else
						{
							$fcount = $db->prepare('SELECT COUNT(*) AS pages FROM forum_post WHERE forum_id = ?'); $fcount->execute(array($forum));
							$count = $fcount->fetch();
							$msg_par_page = 10;
							
							//S'il y a 0 message à afficher
							$count['pages'] = ($count['pages'] == 0)? 1 : $count['pages'];
							
							$pagesmax = ceil($count['pages'] / $msg_par_page);
							if(isset($_GET['page']) AND $_GET['page'] != 0) 
							{
								$page = intval($_GET['page']);

								if($page > $pagesmax)
								{
									$page = 1;
								}
							}
							else
							{
								 $page = 1; 
							}
							$first = ($page - 1) * $msg_par_page;
							$select = $db->query("SELECT id, post,is_image, img_code, post_date, user_id, forum_id, unknow, del, deleter_id, post_date + interval '5' minute AS max_date FROM forum_post WHERE forum_id = $forum ORDER BY post_date ASC LIMIT $first, $msg_par_page");
						}
						
						
						//Statuts
						$anonymebutton = ($fname['rp'] == 1)? "<label for='sendunknow'>Envoyer sans signature</label> <input type='checkbox' name='sendunknow' id='sendunknow' /><br />": " ";
						$dname = $db->prepare('SELECT id,username FROM member_list WHERE id = ?'); $dname->execute(array($fname['deleter_id']));
						$dname = $dname->fetch();
						$vname = $db->prepare('SELECT id,username FROM member_list WHERE id = ?'); $vname->execute(array($fname['locker_id']));
						$vname = $vname->fetch();
						$isrp = ($fname['rp'] == 1)? "<span class='black' style=\"color:lime;\">[RP]</span> ": false;
						$isimportant = ($fname['important'] == 1)? "<span class='black' style=\"color:#cc5500;\">[Important]</span> ": false;
						$isdel = ($fname['del'] == 1 AND $admin_access)? "<span class='black' style=\"color:#990000;\">[Supprimé (par ". $dname['username'].")]</span> ": false;
						$lockinfo = ($admin_access) ? " (par ". $vname['username'].")" : "";
						$islock = ($fname['locked'] == 1)? "<span class='black' style=\"color:#990000;\">[Vérrouillé". $lockinfo ."]</span> ": false;
						?>
						<h4><?=$islock , $isimportant, $isdel, $isrp?><a href="index.php?p=<?= $f_method?>">Forum</a> > <a href="index.php?p=<?= $f_method?>&cat=<?= $fname['id']?>"><?= $fname['fc_name'] ?></a> > <?= $fname['name']?></h4>
						
						<?php
						echo $msg;
						if ($admin_access)
						{
						?>
							<div>
								<form action="index.php?p=<?= $f_method?>&forum=<?= $forum?>" method="POST">
									<?php
									if ($fname['important'] == 0)
									{
									?>
										<input type="submit" name="important" style="color:#ff9900;" value="[I]"/>
									<?php
									}
									else
									{
									?>
										<input type="submit" name="normal" style="color:#0090ff;" value="[N]"/>
									<?php
									}
									if ($fname['rp'] == 1)
									{
									?>
										<input type="submit" name="hrp" style="color:#909090;" value="[HRP]"/>
									<?php
									}
									else
									{
									?>
										<input type="submit" name="rp" style="color:lime;" value="[RP]"/>
									<?php
									}
									if ($fname['locked'] == 1)
									{
									?>
										<input type="submit" name="unlock" style="color:lime;" value="[dV]"/>
									<?php
									}
									else
									{
									?>
										<input type="submit" name="lock" style="color:#ff7777;" value="[V]"/>
									<?php
									}
									if ($fname['del'] == 1)
									{
									?>
										<input type="submit" name="restore" style="color:aqua;" value="[X]"/>
									<?php
									}
									else
									{
									?>
										<input type="submit" name="del" style="color:#ff7777;" value="[X]"/>
									<?php
									}
									?>
								</form>
							</div>
						<?php
						}
						?>
						
						<div align="right" style="padding:1%">
							<?php
							if ($page > 1)
							{
							?>
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=1" class="black">[<<]</a> 
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $page -1?>" class="black">[<]</a>
							<?php
							}
							else
							{
							?>
								<span class="red">[<<]</span> 
								<span class="red">[<]</span>
							<?php
							}
							
							if ($page >= $pagesmax)
							{
							?>
								<span class="red">[>]</span> 
								<span class="red">[>>]</span>
							<?php
							}
							else
							{
							?>
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $page +1?>" class="black">[>]</a> 
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $pagesmax?>" class="black">[>>]</a>
							<?php
							}
							?>
						</div>
						<table cellspacing="1" cellpadding="5" width="90%" align="center">
							<tbody>
								<tr class="member_top">
									<th>Message</th> <th width="25%">Envoyé par :</th>
								</tr>
								<?php
								while ($line = $select->fetch())
								{
									$post = preg_replace('#\n#', '<br />', $line['post']);
									
									//Informations expéditeur
									$ranksel = $db->prepare('SELECT * FROM member_list WHERE id = ?'); $ranksel->execute(array($line['user_id']));
									$ranksel = $ranksel->fetch();
									if ($line['unknow'] == 0)
									{
										$rank = $ranksel['rank'];
										$member = $db->prepare('SELECT * FROM member_list WHERE id = ?'); $member->execute(array($line['user_id']));
										$member = $member->fetch();
										
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
										
										$title = $member['title'];
										$title = ($member['pionier']== 1)? "Pionnier" : $title;
										$title = ($member['ban'] == 1)? "Banni" : $title;
										$title = ($member['desert'] == 1)? "Déserteur" : $title;
										$user = $member['username'];
										$tech = ($member['technician'] == 1)? "-T" : false;
										$pionier = ($member['pionier'] == 1)? "-P" : false;
										$a = "<a style='font-weight: bold;font-size: 1.1em;color: ". $color .";text-shadow: 2px 2px 2px #000000;' href='index.php?p=profile&perso=" . $member['id'] ."'>";
										$aend = "</a>";
										$img = (file_exists("pics/MiniImage/user_" . $line['user_id'] . ".png"))? "<img src='pics/MiniImage/user_" . $line['user_id'] . ".png' alt='' width='6%' />": false ;
									}
									else
									{
										$member = $db->prepare('SELECT * FROM member_list WHERE id = ?'); $member->execute(array($line['user_id']));
										$member = $member->fetch();
										$stafftrue = ($_SESSION['rank'] == 5)? "<br />(posté par ". $member['username'] .")" : false;
										$rank = 3;
										$title = "Message";
										$user = "Anonyme";
										$color = "style='font-weight: bold;font-size: 1.1em;color: #00AA00;text-shadow: 2px 2px 2px #000000;'";
										$a = "<span class='name1' " . $color . ">";
										$aend = "</span>";
										$img = false;
									}
									$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $line['post_date']);
									$isdel = ($line['del'] == 1)? "style='background-color:rgba(70,0,0,.5);'" : false;
									
									if ($line['del'] == 1)
									{
										$deleter = $db->prepare('SELECT username, id FROM member_list WHERE id = ?');
										$deleter->execute(array($line['deleter_id'])); $del = $deleter->fetch();
										$delmsg = ($line['del'] == 1)? "<br />(Message Supprimé par " . $del['username'] .")" : false ;
									}
									else
									{
										$delmsg = false;
									}
									
									if ($line['del'] == 0)
									{
										$delbutton = ($moderator_access AND $view >= $ranksel['rank'])? "<br /><a href='index.php?p=". $f_method ."&forum=" . $forum ."&page=" . $page ."&del=" . $line['id'] ."' style='color:red; text-shadow: 1px 1px 1px black;' />[Supprimer]</a>" : false;
									}
									else
									{
										$delbutton = ($admin_access)? "<br /><a href='index.php?p=". $f_method ."&forum=" . $forum ."&page=" . $page . "&restore=" . $line['id'] ."' style='color:aqua; text-shadow: 1px 1px 1px black;' />[Restaurer]</a>" : false;
									}
									
									$now = date("Y-m-d H:i:s");
									$now = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}):(.{2}):(.{2})$#', '$1$2$3$4$5$6', $now);
									$maxtime = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}):(.{2}):(.{2})$#', '$1$2$3$4$5$6', $line['max_date']);
									$editbutton = ($cadre_access AND $view >= $ranksel['rank'] OR $_SESSION['id'] == $line['user_id'] AND $now < $maxtime)? 
									"<br /><a href='index.php?p=". $f_method ."&forum=" . $forum ."&page=" . $page. "&edit=" . $line['id'] ."' style='color:blue; text-shadow: 1px 1px 1px black;' />[Modifier]</a>": false;
									
									if ($line['unknow'] == 1)
									{
										$setanonyme = ($admin_access)? "<br /><a href='index.php?p=". $f_method ."&forum=" . $forum ."&page=" . $page. "&setp=" . $line['id'] ."' style='color:lime; text-shadow: 1px 1px 1px black;' />[Public]</a>" : false;
									}
									else
									{
										$setanonyme = ($admin_access)? "<br /><a href='index.php?p=". $f_method ."&forum=" . $forum ."&page=" . $page. "&seta=" . $line['id'] ."' style='color:lime; text-shadow: 1px 1px 1px black;' />[Anonyme]</a>" : false;
									}
								?>
									<tr class="forumrank<?= $rank?>" <?=$isdel?> >
										<td valign="top">
											<p><?= $post?></p>
											<?php
											if ($line['is_image'])
											{
											?>
											<p><img src="pics/uploads/<?= $line['img_code']?>.png" width="100%" alt=" " /></p>
											<?php
											}
											?>
										</td>
										<td valign="top">
											<?= $img, " " , $a , $title , " ", $user, $aend,   $stafftrue, "<br />", $date, $delmsg;?>
											<div>
												<?= $delbutton, $editbutton, $setanonyme; ?>
											</div>
										</td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
						
						<?php
						if ($page == $pagesmax)
						{
							if ($fname['locked'] == 1)
							{
							?>
								<p style="font-size:1.1em;">Ce forum est verrouillé et n'accepte plus de nouvelles réponses.</p>
							<?php
							}
							else
							{
								if ($_GET['edit'])
								{
									$editing = $db->prepare("SELECT id, post, is_image FROM forum_post WHERE id = ?");
									$editing->execute(array(intval($_GET['edit'])));
									$e = $editing->fetch();
								}
							?>
								<div style="margin:3%;" width="100%">
									<form action="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $page?>" method="POST" enctype="multipart/form-data">
										
										<label for="newpost" style="text-align:right;">Envoyer une réponse</label><br />
										<textarea style="width: 95%; height: 120px;" id="newpost" name="newpost"><?= $e['post']?></textarea><br />
										<?php echo $anonymebutton?>
										<label for="img">Envoyer une image : </label><input type="file" name="send_img" id="img" />
										<?php
										if ($_GET['edit'])
										{
											if ($e['is_image'] == 1)
											{
											?>
												<label for='del_img'>Retirer l'image</label> <input type='checkbox' name='del_img' id='del_img' />
											<?php										
											}
										?>
											<input type="hidden" value="<?= intval($_GET['edit'])?>" name="edit" />
										<?php
										}
										?>
										<input type="submit" style="text-align:right;" name="sendnew" value="Envoyer" />
									</form>
								</div>
							<?php
							}
						}
						else
						{
						?>
							<p>Pour poster un nouveau message, rendez-vous à la dernière page en <a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $pagesmax?>">cliquant ici</a>.</p>
						<?php
						}
						?>
						
						<div align="right" style="padding:1%">
							<?php
							if ($page > 1)
							{
							?>
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=1" class="black">[<<]</a> 
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $page -1?>" class="black">[<]</a>
							<?php
							}
							else
							{
							?>
								<span class="red">[<<]</span> 
								<span class="red">[<]</span>
							<?php
							}
							
							if ($page >= $pagesmax)
							{
							?>
								<span class="red">[>]</span> 
								<span class="red">[>>]</span>
							<?php
							}
							else
							{
							?>
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $page +1?>" class="black">[>]</a> 
								<a href="index.php?p=<?= $f_method?>&forum=<?= $forum?>&page=<?= $pagesmax?>" class="black">[>>]</a>
							<?php
							}
							?>
						</div>
						<?php
					}
				}
				else
				{
					$global_access = true;
				}
			}
			else
			{
				$global_access = true;
			}
		}
		else
		{
			echo "<p>Navré mais vous n'avez pas le grade suffisant pour accéder à ce forum.</p>";
		}
	}
	
	if ($global_access)
	{
		if (isset($_GET['cat']))
		{
			$cat = intval($_GET['cat']);
		
			$verify = $db->prepare('SELECT * FROM forum_category WHERE id = ?'); $verify->execute(array($cat));
			if ($verify = $verify->fetch())
			{
				$local_access = ($guild_access)? $verify['guild'] : '0';
				
				if ($verify['guild'] == $local_access)
				{
					$total_access = true;
				}
				else
				{
					$total_access = false;
				}
			}
			else
			{
				$total_access = false;
			}
				
			// Nouveau forum
			if (isset($_POST['send']))
			{
				//Importance du sujet
				if ($moderator_access)
				{
					$imp = (isset($_POST['send_important']))? 1 : 0;
				}
				else
				{
					$imp = 0;
				}
				
				//Sujet RP ou HRP ?
				$is_rp = (isset($_POST['send_rp']))? 1 : 0;
				
				//Si le nom a bien été saisi
				if (isset($_POST['new_topic']) AND !empty($_POST['new_topic']))
				{
					$topic = htmlspecialchars($_POST['new_topic']);
					
					$add = $db->prepare("INSERT INTO forum_forum VALUES('', ?, ?, 0, NOW(), ?, ?, 0, 0, 0, 0)");
					$add->execute(array($topic, $cat, $imp, $is_rp));
				}
			}
		}
		
		if ($total_access)
		{
			$select = $db->prepare('SELECT * FROM forum_category WHERE id = ?'); $select->execute(array($cat));
		}
		else
		{
			if ($guild_access)
			{
				$select = $db->prepare('SELECT * FROM forum_category WHERE guild > 0 AND rank <= ? ORDER BY rank ASC, name ASC'); $select->execute(array($view));
			}
			else
			{
				$select = $db->prepare('SELECT * FROM forum_category WHERE guild = 0 AND rank <= ? ORDER BY rank ASC, name ASC'); $select->execute(array($view));
			}
		}
	?>
	<div width="100%" style="padding:1%" class="forumbg">
		<?php
		$access_count = 0 ;
		while ($line = $select->fetch())
		{
			
			if ($_GET['p'] == "forumg")
			{
				if ($admin_access)
				{
					$this_access = true;
					$access_count ++;
				}
				else
				{
					$verif = $db->prepare("SELECT * FROM group_members WHERE group_id = ? AND user_id = ?");
					$verif->execute(array($line['guild'], $_SESSION['id']));
					if ($verif->fetch())
					{
						$this_access = true;
						$access_count ++;
					}
					else
					{
						$this_access = false;
					}
				}
			}
			else
			{
				$this_access = true;
				$access_count = 1;
			}
			
			if ($this_access)
			{
				?>
				<h4><?= $line['name']?></h4>
				<?php
				$filename = 'pics/forum/forum_' . $line['id']. '.png' ;
				if (file_exists($filename))
				{
					$ban_exist = true;
				}
				else
				{
					$ban_exist = false;
				}
					
				if ($ban_exist)
				{
				?>
					<p><img src="pics/forum/forum_<?= $line['id']?>.png" width="100%" style="border-radius: 10px;" /></p>
				<?php
				}
				
				if ($total_access)
				{
					if ($moderator_access)
					{
						$flist = $db->prepare('SELECT * FROM forum_forum  WHERE category = ? ORDER BY important DESC, last_activity DESC');
					}
					else
					{
						$flist = $db->prepare('SELECT * FROM forum_forum  WHERE category = ? AND del = 0 ORDER BY important DESC, last_activity DESC');
					}
					
				}
				else
				{
					$flist = $db->prepare('SELECT * FROM forum_forum  WHERE category = ? AND del = 0 ORDER BY important DESC, last_activity DESC LIMIT 10');
				}
					$flist->execute(array($line['id']));
				?>
				<style>
					.f-select:hover
					{
						background-color : rgba(255,255,255, 0.5);
					}
				</style>
				<?php
				if (isset($_GET['cat']))
				{
				?>
				<p><a href="index.php?p=<?= $f_method?>">[Retourner à la liste globale]</a></p>
				<?php
				}
				?>
				<table cellspacing="0" cellpadding="0" align="center" width="95%">
					<tbody>
						<tr class="member_top">
							<th>Sujet</th><th width="5%">Msg.</th> <th width="5%">Vues.</th> <th width="25%">Dernière activité</th>
						</tr>
						<?php
						while ($fline = $flist->fetch())
						{
							$isimportant = ($fline['important'] == 1)? "<span style='font-weight: bold; font-size: 1.1em; color: #cc5500; text-shadow: 2px 2px 2px #000000;'>[Important]</span> " : false;
							$isrp = ($fline['rp'] == 1)? "<span style='font-weight: bold; font-size: 1.1em; color: #00ff00; text-shadow: 2px 2px 2px #000000;'>[RP]</span> " : false;
							$lock = ($fline['locked'] == 1)? "<span class='black' style=\"color:#990000;\">[Vérrouillé]</span> " : false;
							$isdel = ($fline['del'] == 1)? "<span style='font-weight: bold; font-size: 1.1em; color: #900000; text-shadow: 2px 2px 2px #000000;'>[Supprimé]</span> " : false;
							
							//Information du dernier post
							$latest = $db->prepare('SELECT * FROM forum_post WHERE forum_id = ? ORDER BY id DESC'); $latest->execute(array($fline['id']));
							if ($latest = $latest->fetch())
							{
								if ($latest['unknow'] == 0)
								{
									$member = $db->prepare('SELECT * FROM member_list WHERE id = ?'); $member->execute(array($latest['user_id']));
									$member = $member->fetch();
									
									//Couleur du grade
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
									
									$title = $member['title'];
									$title = ($member['pionier']== 1)? "Pionnier" : $title;
									$title = ($member['ban'] == 1)? "Banni" : $title;
									$title = ($member['desert'] == 1)? "Déserteur" : $title;
									$user = $member['username'];
									$tech = ($member['technician'] == 1)? "-T" : "";
									$pionier = ($member['pionier'] == 1)? "-P" : "";
									$a = "<a style='font-weight: bold;font-size: 1.1em;color: ". $color .";text-shadow: 2px 2px 2px #000000;' href='index.php?p=profile&perso=" . $member['id'] ."'>";
									$aend = "</a>";
									
									$img = (file_exists("pics/MiniImage/user_" . $latest['user_id'] . ".png"))? "<img src='pics/MiniImage/user_" . $latest['user_id'] . ".png' alt='' width='6%' />": false;
								}
								else
								{
									$title = "Message";
									$user = "Anonyme";
									$color = "#00AA00";
									$a = "<span style='font-weight: bold;font-size: 1.1em;color: #00AA00;text-shadow: 2px 2px 2px #000000;'>";
									$aend = "</span>";
									$img = false;
								}
								
								$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $latest['post_date']);
								
								$last = $img ." ". $a  . $title . " ". $user. $aend ."<br />" . $date;
							}
							else
							{
								$last = "Aucun message dans ce forum.";
							}
							
							//Si le post n'a pas été lu encore
							if ($_SESSION['connected'])
							{
								$verif = $db->prepare('SELECT * FROM forum_unread WHERE user_id = ? AND forum_id = ?');
								$verif->execute(array($_SESSION['id'], $fline['id']));
								if ($verif = $verif->fetch())
								{
									$unread = ($verif['unread'] == 1)? "style='background-color: #9d9d9d;'" : false;
									$page = $verif['page'];
								}
								else
								{
									$unread = "style='background-color: #9d9d9d;'";
									$page = 1;
								}
							}
							else
							{
								$unread = false;
							}
							
							//Compte du nombre de vues
							$views = $db->prepare("SELECT COUNT(*) AS views FROM forum_unread WHERE unread = 0 AND forum_id = ?");
							$views->execute(array($fline['id'])); $views = $views->fetch(); $views = $views['views'];
							
							//Compte du nombre de messages
							$msgs = $db->prepare("SELECT COUNT(*) AS msgs FROM forum_post WHERE forum_id = ? AND del = 0");
							$msgs->execute(array($fline['id'])); $msgs = $msgs->fetch(); $msgs = $msgs['msgs'];
						?>
							<tr class="forumf" <?= $unread?>>
								<td class="f-select" style="border-bottom: solid 2px black; border-left: solid 2px black;">
									<a href="index.php?p=<?= $f_method?>&forum=<?= $fline['id']?>&page=<?= $page?>">
										<div width="100%" style="padding:5px">
											<?= $isimportant, $isrp, $isdel, $lock, $fline['name']?>
										</div>
									</a>
								</td>
								<td style="text-align: center; border-bottom: solid 2px black; border-left: solid 2px black;"><?= $msgs?></td>
								<td style="text-align: center; border-bottom: solid 2px black; border-left: solid 2px black;"><?= $views?></td>
							
								<td style="padding:2px; border-bottom: solid 2px black; border-right: black 2px solid; border-left: black 2px solid; text-align:center;">
									<?= $last ?>
								</td>
							</tr>
						<?php
						}
						if (!$total_access)
						{
						?>
							<tr class="forumf">
								<td colspan="4" style="padding:5px; text-align:center; border-bottom: solid 2px black; border-left: solid 2px black; border-right: black 2px solid;">
									<a href="index.php?p=<?= $f_method?>&cat=<?= $line['id']?>">[Afficher tous les posts]</a>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
				<?php
				if ($total_access)
				{
				?>
					<form action="index.php?p=<?= $f_method?>&cat=<?= $cat?>" method="POST" style="margin: 10px;">
						<label for="new_topic">Nouveau forum : </label> <input type="text" autocomplete="off" id="new_topic" name="new_topic" /> <input type="submit" name="send" value="Créer" /><br />
						<?php
						if ($moderator_access)
						{
						?>
							<label for="send_important">Définir le sujet comme important :</label> <input type="checkbox" id="send_important" name="send_important" /><br />
						<?php
						}
						?>
						<label for="send_rp">Définir le sujet comme roleplay :</label> <input type="checkbox" id="send_rp" name="send_rp" />
					</form>
				<?php
				}
			}
		}
		
		if ($access_count == 0)
		{
		?>
			<p>Vous ne semblez appartenir à aucune guilde vous ayant autorisé à consulter leurs archives.</p>
		<?php
		}
		?>
	</div>
	<?php
	}
}
?>