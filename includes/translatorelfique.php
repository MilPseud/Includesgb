<?php function elf_translate()
{
	global $db, $_POST, $_GET, $_SESSION;
	
$patterns1 = array();
$patterns1 = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
				   'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

$patterns2 = array();
$patterns2 = array('z', 'n', 'v', 'f', 'r', 'g', 'h', 'j', 'o', 'k', 'l', 'm', 'q', 'w', 'p', 'a',
				   's', 't', 'd', 'y', 'i', 'b', 'x', 'c', 'u', 'e');

ksort($patterns1);
ksort($patterns2);

if(isset($_POST['TranslateCE']))
{

$paragraph4 = htmlspecialchars($_POST['TextCE']);
$paragraph3 = strtolower($paragraph4);

$paragraph = preg_replace('/'.$patterns1.'/', $patterns2, $paragraph3);
}

if(isset($_POST['TranslateEC']))
{

$paragraph5 = htmlspecialchars($_POST['TextEC']);
$paragraph6 = strtolower($paragraph5);

$paragraph2 = preg_replace('/'.$patterns2.'/', $patterns1, $paragraph5);
}
?>

<form action="index.php?p=elf_translate" method="POST">
					<table class="pnjtable" style="border-radius: 50px;" cellspacing="5" cellpadding="5" align="center">
						<tbody>
								<tr>
									<td colspan="2" style="border-radius: 10px;">
									Traducteur Commun -> Elfique
									<br>
									<br>
									<input type="text" name="TextCE" placeholder="Entrez votre Texte ici">
									<input type="submit" name="TranslateCE" value="Traduire !">
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-radius: 10px;">
									Phrase Traduite:
									<br>
									<br>
									<?= $paragraph?>
									</td>
								</tr>
						</tbody>
					</table>
					<br>
					<br>
					<table class="pnjtable" style="border-radius: 50px;" cellspacing="5" cellpadding="5" align="center">
						<tbody>
								<tr>
									<td colspan="2" style="border-radius: 10px;">
									Traducteur Elfique -> Commun
									<br>
									<br>
									<input type="text" name="TextEC" placeholder="Entrez votre Texte ici">
									<input type="submit" name="TranslateEC" value="Traduire !">
									</td>
								</tr>
								<tr>
									<td colspan="2" style="border-radius: 10px;">
									Phrase Traduite:
									<br>
									<br>
									<?= $paragraph2?>
									</td>
								</tr>
						</tbody>
					</table>
</form>

<?php
}
?>