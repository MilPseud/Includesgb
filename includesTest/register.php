<?php function register()
{ global $db, $_SESSION, $_POST, $_GET;

if(isset($_POST['forminscription'])) {
   $username = htmlspecialchars($_POST['username']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp = sha1($_POST['mdp']);
   $mdp2 = sha1($_POST['mdp2']);
	if(!empty($_POST['username']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
      $usernamelength = strlen($username);
	  $usercount = $db->prepare('SELECT username FROM member_list WHERE username = ?');
	  $usercount->execute(array($username));
	  $countuser = $usercount->rowCount();
		if($countuser == 0) {
		  if($usernamelength <= 225) {
			 if($mail == $mail2) {
				if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				   $reqmail = $db->prepare("SELECT * FROM member_list WHERE mail = ?");
				   $reqmail->execute(array($mail));
				   $mailexist = $reqmail->rowCount();
					 if($mailexist == 0) {
					  if($mdp == $mdp2) {
						 $insertmbr = $db->prepare("INSERT INTO member_list(username, mail, password, registration_date) VALUES(?, ?, ?, NOW())");
						 $insertmbr->execute(array($username, $mail, $mdp));
						 $insertprs = $db->prepare("INSERT INTO page_perso(id) VALUES(?)");
						 $insertprs->execute(array(NULL));
						 $insertach = $db->prepare("INSERT INTO achievement(id) VALUES(?)");
						 $insertach->execute(array(NULL));
						 $insertalt = $db->prepare("INSERT INTO div_alert(id) VALUES(?)");
						 $insertalt->execute(array(NULL));
						 $insertmsg = $db->prepare('INSERT INTO Chatbox(message, playerid, date_send) VALUES(?, ?, NOW())');
						 $insertmsg->execute(array('<span class="username-detail" style="font-weight: bold;color: #00AAAA">Bienvenue à '.$username.' sur GaaranStröm !</span>', 22));
						 $erreur = "Votre compte a bien été créé ! <a href=\"?p=connect\">Me connecter</a>";
						} else {
						   $erreur = "Vos Mots de Passes ne correspondent pas !";
						}
				   } else {
					  $erreur = "Cette Adresse Mail déjà utilisée !";
				   }
				} else {
				   $erreur = "Votre Adresse Mail n'est pas valide !";
				}
			 } else {
				$erreur = "Vos Adresses Mails ne correspondent pas !";
			 }
		  } else {
			 $erreur = "Le Prénom de votre Personnage ne doit pas excéder 225 caractères !";
		  }
		} else {
			$erreur = "Ce prénom existe déjà !";
		}
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>
   
<html>
   <head>
      <title>Inscription</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Inscription</h2>
         <br /><br />
         <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label for="username">Nom du Personnage :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Prénom UNIQUEMENT" id="username" name="username" value="<?php if(isset($username)) { echo $username; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail">Adresse Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Adresse Mail" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mail2">Confirmation de l'Adresse Mail :</label>
                  </td>
                  <td>
                     <input type="email" placeholder="Adresse Mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Mot de passe" id="mdp" name="mdp" />
                  </td>
               </tr>
               <tr>
                  <td align="right">
                     <label for="mdp2">Confirmation du mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Mot de Passe" id="mdp2" name="mdp2" />
                  </td>
               </tr>
            </table>
                     <br />
                     <input type="submit" name="forminscription" value="Valider" />
<p align="center">Vous vous apprêtez à vous inscrire sur le Site de GaaranStröm.<br>
         </form>
         <?php
         if(isset($erreur)) {
            echo '<font color="red">'.$erreur."</font>";
         }
         ?>
      </div>
   </body>
</html>
<?php
 } 
?>