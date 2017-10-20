<?php function profile()

{ global $db, $_POST, $_SESSION, $_GET;
if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
   $perso = intval($_GET['perso']);
   $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $requser->execute(array($perso)); 
   $userinfo = $requser->fetch();
   
   $reqperso = $db->prepare('SELECT * FROM page_perso WHERE id = ?');
   $reqperso->execute(array($perso));
   $persoinfo = $reqperso->fetch();
   
   $reqvalid = $db->prepare('SELECT * FROM member_list WHERE id = ?');
   $reqvalid->execute(array($persoinfo['bgvalidator'])); 
   $persovalid = $reqvalid->fetch();
   
   $achperso = $db->prepare('SELECT * FROM achievement WHERE id = ?');
   $achperso->execute(array($perso));
   $achinfo = $achperso->fetch();
   
   $bg = preg_replace('#\n#', '<br>', $persoinfo['bg']);
   $descm = preg_replace('#\n#', '<br>', $persoinfo['Descm']);
   $descp = preg_replace('#\n#', '<br>', $persoinfo['Descp']);
   
if(isset($_POST['upgrade']))
{
	if($userinfo['rank'] == 6)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade MJS", $userinfo['rank'] + 1, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 1, 0, 0, 0, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation MJS !</span>', 22));
	}
	if($userinfo['rank'] == 5)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade MJE", $userinfo['rank'] + 1, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 1, 0, 0, 0, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation MJS !</span>', 22));
	}
	if($userinfo['rank'] == 4)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade MJ", $userinfo['rank'] + 1, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 1, 0, 0, 0, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation MJ !</span>', 22));
	}
	if($userinfo['rank'] == 3)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade Cadre", $userinfo['rank'] + 1, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 1, 0, 0, 0, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation Cadre !</span>', 22));
	}
	if($userinfo['rank'] == 2)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade Cadre", $userinfo['rank'] + 2, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 2, 0, 0, 0, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation Cadre !</span>', 22));
	}
	if($userinfo['rank'] == 1)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade Cadre", $userinfo['rank'] + 3, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 3, 0, 0, 0, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation Cadre !</span>', 22));
	}
	if($userinfo['rank'] == 0)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a gradé ".$userinfo["username"]." au grade Joueur", $userinfo['rank'] + 1, '+'));

	$upgrade = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$upgrade->execute(array($userinfo['rank'] + 1, 0, 0, 0, $perso));
	
	$insertalt = $db->prepare('UPDATE div_alert SET candid = ? WHERE id = ?'); 
	$insertalt->execute(array(2, $perso));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Gradation Joueur !</span>', 22));
	}
	
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['retrograde']))
{
	if($userinfo['rank'] == 7)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade MJE", $userinfo['rank'] - 1, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 1, 0, 0, 0, $perso));
	}
	if($userinfo['rank'] == 6)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade MJ", $userinfo['rank'] - 1, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 1, 0, 0, 0, $perso));
	}
	if($userinfo['rank'] == 5)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade Cadre", $userinfo['rank'] - 1, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 1, 0, 0, 0, $perso));
	}
	if($userinfo['rank'] == 4)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade Joueur", $userinfo['rank'] - 3, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 3, 0, 0, 0, $perso));
	}
	if($userinfo['rank'] == 3)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade Joueur", $userinfo['rank'] - 2, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 2, 0, 0, 0, $perso));
	}
	if($userinfo['rank'] == 2)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade Joueur", $userinfo['rank'] - 1, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 1, 0, 0, 0, $perso));
	}
	if($userinfo['rank'] == 1)
	{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a rétrogradé ".$userinfo["username"]." au grade Visiteur", $userinfo['rank'] - 1, '-'));

	$retrograde = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$retrograde->execute(array($userinfo['rank'] - 1, 0, 0, 0, $perso));
	
	$insertalt = $db->prepare('UPDATE div_alert SET candid = ? WHERE id = ?'); 
	$insertalt->execute(array(0, $perso));
	}
	
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Humain']))
{
	$humain = $db->prepare('UPDATE member_list SET pnj = ? WHERE id = ?'); 
	$humain->execute(array(0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['PNJ']))
{
	$pnj = $db->prepare('UPDATE member_list SET pnj = ? WHERE id = ?'); 
	$pnj->execute(array(1, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['E']))
{
	$entity = $db->prepare('UPDATE member_list SET pnj = ? WHERE id = ?'); 
	$entity->execute(array(3, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Dieu']))
{
	$dieu = $db->prepare('UPDATE member_list SET pnj = ? WHERE id = ?'); 
	$dieu->execute(array(2, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Vanish']))
{
	$vanish = $db->prepare('UPDATE member_list SET vanish = ? WHERE id = ?'); 
	$vanish->execute(array(1, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['unVanish']))
{
	$vanish2 = $db->prepare('UPDATE member_list SET vanish = ? WHERE id = ?'); 
	$vanish2->execute(array(0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Ban']))
{
	$ban = $db->prepare('UPDATE member_list SET ban = ? WHERE id = ?'); 
	$ban->execute(array(1, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Pardon']))
{
	$unban = $db->prepare('UPDATE member_list SET ban = ? WHERE id = ?'); 
	$unban->execute(array(0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Delete']))
{
	header('Location: index.php?p=delaccount&perso='.$perso.'');
}

if(isset($_POST['unDelete']))
{
	$unDEL = $db->prepare('UPDATE member_list SET desert = ? WHERE id = ?'); 
	$unDEL->execute(array(0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Actif']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a promu ".$userinfo["username"]." au grade Joueur Actif", 'A', '+'));
	
	$insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
	$insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Félicitations à '.$userinfo['username'].' pour sa Promotion Actif !</span>', 22));
	
	$actif = $db->prepare('UPDATE member_list SET actif = ? WHERE id = ?'); 
	$actif->execute(array(1, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['inActif']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a démis ".$userinfo["username"]." au grade Joueur", '1', '-'));

	$inActif = $db->prepare('UPDATE member_list SET actif = ? WHERE id = ?'); 
	$inActif->execute(array(0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['VIP']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a promu ".$userinfo["username"]." au grade VIP", '3', '+'));

	$VIP = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$VIP->execute(array(3, 0, 0, 0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Alpha']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a promu ".$userinfo["username"]." au grade Joueur Alpha", '2', '+'));

	$Alpha = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$Alpha->execute(array(2, 0, 0, 0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['Joueur']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$_SESSION["username"]." a démis ".$userinfo["username"]." au grade Joueur", '1', '-'));

	$Joueur = $db->prepare('UPDATE member_list SET rank = ?, pnj = ?, actif = ?, digni = ? WHERE id = ?'); 
	$Joueur->execute(array(1, 0, 0, 0, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['DigniMJE']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$userinfo["username"]." part à la retraite", 'D1', '-'));

	$DigniMJE = $db->prepare('UPDATE member_list SET rank = ?, digni = ? WHERE id = ?'); 
	$DigniMJE->execute(array($userinfo['rank'] - 1, 1, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['DigniMJ']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$userinfo["username"]." part à la retraite", 'D2', '-'));

	$DigniMJ = $db->prepare('UPDATE member_list SET rank = ?, digni = ? WHERE id = ?'); 
	$DigniMJ->execute(array($userinfo['rank'] - 1, 2, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['DigniCadre']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$userinfo["username"]." part à la retraite", 'D3', '-'));

	$DigniCadre = $db->prepare('UPDATE member_list SET rank = ?, digni = ? WHERE id = ?'); 
	$DigniCadre->execute(array($userinfo['rank'] - 3, 3, $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['unDigni']))
{
	$insertgrad = $db->prepare('INSERT INTO grada_history VALUES(?, ?, ?, ?, ?)'); 
	$insertgrad->execute(array(NULL, $perso, "".$userinfo["username"]." reprend sa place", $userinfo['rank'] + 1, '+'));

	$unDigni = $db->prepare('UPDATE member_list SET rank = ?, digni = ? WHERE id = ?'); 
	if($userinfo['rank'] == 1)
	{
	$unDigni->execute(array($userinfo['rank'] + 3, 0, $perso));
	}
	else
	{
	$unDigni->execute(array($userinfo['rank'] + 1, 0, $perso));
	}
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if(isset($_POST['skin']))
{
	if(file_exists("pics/ClassicImage/user_".$userinfo['id'].".png"))
	{
		unlink("pics/ClassicImage/user_".$userinfo['id'].".png");
	}
	if(file_exists("pics/MiniImage/user_".$userinfo['id'].".png"))
	{
		unlink("pics/MiniImage/user_".$userinfo['id'].".png");
	}
	copy("https://minotar.net/armor/body/".$userinfo['pseudo']."/125.png", "pics/ClassicImage/user_".$userinfo['id'].".png");
	copy("https://minotar.net/armor/bust/".$userinfo['pseudo']."/32.png", "pics/MiniImage/user_".$userinfo['id'].".png");
	copy("https://minotar.net/skin/".$userinfo['pseudo']."", "pics/Skins/user_".$userinfo['id'].".png");
	header('Location: index.php?p=members');
}

if( isset($_POST['download'])) 
{ 
	header('Location: http://gaaranstrom.890m.com/pics/Skins/user_'.$userinfo['id'].'.png');
}

if(isset($_POST['unskin']))
{
	if(file_exists("pics/ClassicImage/user_".$userinfo['id'].".png"))
	{
		unlink("pics/ClassicImage/user_".$userinfo['id'].".png");
	}
	if(file_exists("pics/MiniImage/user_".$userinfo['id'].".png"))
	{
		unlink("pics/MiniImage/user_".$userinfo['id'].".png");
	}
	if(file_exists("pics/Skins/user_".$userinfo['id'].".png"))
	{
		unlink("pics/Skins/user_".$userinfo['id'].".png");
	}
}

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

if (isset($_POST['bgvalid']))
{
	$bgvalid = $db->prepare('UPDATE page_perso SET bgvalid = 1, date_valid = NOW(), bgvalidator = ? WHERE id = ?'); 
	$bgvalid->execute(array($_SESSION['id'], $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}

if (isset($_POST['bgrefuse']))
{
	$bgvalid = $db->prepare('UPDATE page_perso SET bgvalid = 2, date_valid = NOW(), bgvalidator = ? WHERE id = ?'); 
	$bgvalid->execute(array($_SESSION['id'], $perso));
	header('Location: index.php?p=profile&perso='.$perso.'');
}
			
if (isset($_POST['sendnew']))
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
								$name = "pics/GrandImage/user_".$perso.".png";
								$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
							} else {
								$erreur = "L'image n'est pas au Format PNG !";
							}
						}
					}
				}
			}
if (isset($_POST['del_img']))
				{
					if(file_exists("pics/GrandImage/user_".$userinfo['id'].".png"))
					{
						unlink("pics/GrandImage/user_".$userinfo['id'].".png");
					}
				}
				
$date = preg_replace('#^(.{4})-(.{2})-(.{2}) (.{2}:.{2}):.{2}$#', 'Le $3/$2/$1 à $4', $persoinfo['date_valid']);
?>

<?php

if($_SESSION['id'] == $perso)
{
?>

<?php
include ('includes/pagedeprofile.php');
?>

<?php
}
else
{
if($userinfo['pnj'] == 0 OR $userinfo['pnj'] == 3)
{
?>

<?php
include ('includes/pagedeprofile.php');
?>

<?php
}
elseif($userinfo['pnj'] == 1)
{
if($_SESSION['rank'] >= 4)
{
?>

<?php
include ('includes/pagedeprofile.php');
?>

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
elseif($userinfo['pnj'] == 2)
{
if($_SESSION['rank'] >= 5)
{
?>

<?php
include ('includes/pagedeprofile.php');
?>

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
elseif($userinfo['rank'] == 2)
{
if($_SESSION['rank'] == 2 OR $_SESSION['rank'] >= 5)
{
?>

<?php
include ('includes/pagedeprofile.php');
?>

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

<?php   
}

}
?>