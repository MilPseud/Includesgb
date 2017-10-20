<?php function deconnect()
{ global $db, $_POST, $_GET, $_SESSION;

$_SESSION = array();
session_destroy();
header('Location: index.php');
?>

<?php
}
?>