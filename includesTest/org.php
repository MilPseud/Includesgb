<?php function org()
{ global $db, $_POST, $_GET, $_SESSION;
?>

<h2 style="margin-left:10px">Les &#201l&#233mentaires</h2>

<?php
$select = $db->query("SELECT * FROM organisations WHERE org = 'Elementaires' ORDER BY orgrank DESC, rank DESC, username");
while ($line = $select->fetch())
{
?>

<table>
<tr>
<span class="org">[G<?= $line['orgrank']?>] <img width="24px" src="pics/Grade<?= $line['rank']?>.png"> [<?= $line['title']?>] <?= $line['username']?> <?= $line['nom']?></span>
</tr>
</table>

<?php
}
?>

<br>

<?php
}
?>