<?php function connect()

{ global $db, $_POST, $_GET, $_SESSION;

?>

<?php
if(isset($_POST['formconnexion'])) {
   $usernameconnect = htmlspecialchars($_POST['usernameconnect']);
   $mdpconnect = sha1($_POST['mdpconnect']);
   if(!empty($usernameconnect) AND !empty($mdpconnect)) {
      $requser = $db->prepare("SELECT * FROM member_list WHERE username = ? AND password = ?");
      $requser->execute(array($usernameconnect, $mdpconnect));
      $userexist = $requser->rowCount();
      if($userexist == 1) {
					$userinfo = $requser->fetch();
					$_SESSION['id'] = $userinfo['id'];
         			$_SESSION['username'] = $userinfo['username'];
        			$_SESSION['mail'] = $userinfo['mail'];
        			$_SESSION['color'] = $userinfo['color'];
        			$_SESSION['rank'] = $userinfo['rank'];
        			$_SESSION['nom'] = $userinfo['nom'];
        			$_SESSION['title'] = $userinfo['title'];
					$_SESSION['pseudo'] = $userinfo['pseudo'];
					$_SESSION['pnj'] = $userinfo['pnj'];
					$_SESSION['digni'] = $userinfo['digni'];
					$_SESSION['actif'] = $userinfo['actif'];
					$_SESSION['ban'] = $userinfo['ban'];
					$_SESSION['desert'] = $userinfo['desert'];
					$_SESSION['connected'] = true;
					$_SESSION['userinfo'] = true;
			if($_SESSION['desert'] == 0) {
				if ($_SESSION['ban'] == 0) {
					header('Location: index.php?p=profile&perso='.$_SESSION['id'].'');
			} else {
				$erreur = "Ce personnage est banni...";
			}
		} else {
			$erreur = "Ce personnage est supprimé...";
		 }
      } else {
         $erreur = "Mauvais Personnage ou Mot de Passe !";
      }
   } else {
      $erreur = "Tout les champs doivent &#0234tre compl&#0233t&#0233s !";
   }
}
?>

<html>
   <head>
      <title>Connexion</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Connexion</h2>
         <br />
         <form method="POST" action="">
            <table>
               <tr>
                  <td align="right">
                     <label form"username">Nom du Personnage :</label>
                  </td>
                  <td>
                     <input type="text" placeholder="Personnage" id="usernameconnect" name="usernameconnect" value="<?php if(isset($usernameconnect)) { echo $usernameconnect; } ?>" />
                  </td>
               </tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
               <tr>
                  <td align="right">
                     <label for="mdp">Mot de passe :</label>
                  </td>
                  <td>
                     <input type="password" placeholder="Mot de passe" id="mdpconnect" name="mdpconnect" />
                  </td>
               </tr>
            </table>
                     <br />
                     <input type="submit" name="formconnexion" value="Connexion !" />
                     <p>Vous vous appr&#0234tez &#0224 vous connecter sur le Site de GaaranStr&#0246m.<p>
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