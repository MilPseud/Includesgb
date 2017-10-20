<?php function staffedit()
{	global $db, $_POST, $_SESSION, $_GET;

if(isset($_GET['perso']) AND $_GET['perso'] > 0)
{
	$perso = intval($_GET['perso']);
	$reqstaff = $db->prepare('SELECT m.id, m.username, m.rank, m.digni, s.idplayer, s.citation, s.age, s.ville, s.surnom FROM staff_team s RIGHT JOIN member_list m ON s.idplayer = m.id WHERE m.id = ?');
	$reqstaff->execute(array($perso));
	$staffinfo = $reqstaff->fetch();
	
if($_SESSION['id'] == $staffinfo['id'] OR $_SESSION['rank'] >= 6)
{
	
if(isset($_POST['sendnew']))
{	if (isset($_FILES['send_img']))
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
					$name = 'pics/staff/Staff'.$perso.'.png';
					$finish = move_uploaded_file($_FILES['send_img']['tmp_name'], $name);
				} else {
					$erreur = "L'image n'est pas au Format PNG !";
				}
			} else {
				$erreur = "0";
			}
		} else {
			$erreur = "1";
		}
	} else {
		$erreur = "2";
	}
	
					$reqstaff2 = $db->prepare('SELECT * FROM staff_team WHERE idplayer = ?');
					$reqstaff2->execute(array($perso));
					$staffinfo2 = $reqstaff2->rowCount();
					
					$citation2 = htmlspecialchars($_POST['citation']);
					$age = htmlspecialchars($_POST['age']);
					$ville = htmlspecialchars($_POST['ville']);
					$surnom = htmlspecialchars($_POST['surnom']);
					
					if($staffinfo2 == 0)
					{
						$staffadd = $db->prepare('INSERT INTO staff_team VALUES(?, ?, ?, ?, ?)');
						$staffadd->execute(array($perso, $citation2, $age, $ville, $surnom));
						
						$staffalt = $db->prepare('UPDATE div_alert SET staffteam = ? WHERE id = ?');
						$staffalt->execute(array('1', $perso));
						header('Location: index.php?p=staff');
					}
					else				
					{
						$staffupdate = $db->prepare('UPDATE staff_team SET citation = ?, age = ?, ville = ?, surnom = ? WHERE idplayer = ?');
						$staffupdate->execute(array($citation2, $age, $ville, $surnom, $perso));
						header('Location: index.php?p=staff');
					}
					
}
?>

<table style="border-collapse: collapse;margin-left: 2%;margin-right: 2%;width: 95%;">
	<tbody>
		<tr style="border:2px solid #FFA500;background-color:#FFD700;">
			<th colspan="4" style="background-color:#FFD700;height:30px;width:100%;" align="center"><?= $staffinfo['username']?></th>
		</tr>
		<tr style="border:2px solid #8B4513;background-color:#A0522D;">
			<td>
				<form method="POST" enctype="multipart/form-data">
					<input type="file" name="send_img" id="img">
				</form>
			</td>
			<form method="POST">
			<td valign="middle" align="center">
				<input name="age" type="text" placeholder="Age" value="<?=$staffinfo['age']?>">
				<br>
				<input name="ville" type="text" placeholder="Ville" value="<?=$staffinfo['ville']?>">
				<br>
				<input name="surnom" type="text" placeholder="Surnom" value="<?=$staffinfo['surnom']?>">
			</td>
			<td rowspan="3" valign="middle" align="center">
				<textarea name="citation" type="text" placeholder="Citation"><?=$staffinfo['citation']?></textarea>
			</td>
			<td>
				<input rowspan="3" name="sendnew" type="submit" value="Valider">
			</td>
			</form>
		</tr>
	</tbody>
</table>
<span style="color: red"><?= $erreur?></span>
</form>
<?php
}
else
{
?>
<span style="color: darkred;" class="username-detail">
	Vous n'avez pas l'autorisation d'être ici...
</span>
<?php
}

}

}
?>