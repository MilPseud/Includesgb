<?php function guilds ()
{
global $_POST,$_GET, $db;

echo "<h2>Groupes et Guildes</h2>";

    if (isset($_GET['add']) AND $_SESSION['connected'])
    {
        $group = intval($_GET['for']);
        $name = htmlspecialchars($_GET['add']);
        
		$supersel = $db->prepare('SELECT gm.id, gm.user_id, gm.group_id, gm.user_rank, m.id, m.username, m.rank, m.title
		FROM group_members gm
		RIGHT JOIN member_list m ON gm.user_id = m.id
		WHERE gm.group_id = ?
		ORDER BY gm.user_rank DESC, m.rank DESC, m.username ASC');
		$supersel->execute(array($group)); $line = $supersel->fetch();
		$verif0 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3');
		$verif0->execute(array($_SESSION['id']));
    
        if ($_SESSION['rank'] > 5 OR $verif0->fetch())
        {
			$verif = $db->prepare('SELECT username, id FROM member_list WHERE username = ?');
			$verif->execute(array($name));
      
      if ($verif = $verif->fetch())
      {
          $user = $verif['id'];
          $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
          $verif->execute(array($user, $group));
          if ($verif = $verif->fetch())
          {
              echo '<p>Navré, mais ce personnage est déjà présent dans ce groupe.</p> <p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
          }
          else
            {
              $verif = $db->prepare('SELECT * FROM group_name WHERE id = ?');
              $verif->execute(array($group));
              
              if ($verif->fetch())
              {
                  $update = $db->prepare("INSERT INTO group_members VALUES ('',? , ?, '0')");
                $update->execute(array($group, $user));
                echo '<p>Le personnage a bien été ajouté.</p> <p><a href="index.php?p=guilds">Cliquez ici</a> pour continuer.</p>';
              }
              else
              {
                echo '<p>Navré, mais ce groupe n\'existe pas.</p> <p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
              }
        }
          }
      else
      {
          echo  '<p>Navré, mais ce personnage n\'existe pas.</p> <p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
      }
        }
      else
      {
          echo '<p>Navré, mais vous n \'avez pas les permissions suffisantes pour effectuer cette requête.</p>';
      }
    }
    elseif (isset($_GET['del']) AND $_SESSION['connected'])
    {
        $group = intval($_GET['from']);
        $user = intval($_GET['del']);
        
    $supersel = $db->prepare('SELECT gm.id, gm.user_id, gm.group_id, gm.user_rank, m.id, m.username, m.rank, m.title
    FROM group_members gm
    RIGHT JOIN member_list m ON gm.user_id = m.id
    WHERE gm.group_id = ? AND user_id = ?
    ORDER BY gm.user_rank DESC, m.rank DESC, m.username ASC');
    $supersel->execute(array($group,$user)); $line = $supersel->fetch();
    $verif0 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND user_rank > ?');
    $verif0->execute(array($_SESSION['id'], $line['user_rank']));
    
        if ($_SESSION['rank'] > 5 OR $verif0->fetch())
        {
            $verif =  $db->prepare('SELECT id FROM member_list WHERE id = ?');
            $verif->execute(array($user));
            if ($verif->fetch())
            {
                $verif = $db->prepare('SELECT id FROM group_name WHERE id = ?');
                $verif->execute(array($group));
                if ($verif->fetch())
                {
                    $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
                    $verif->execute(array($user, $group));
                    if ($verif->fetch())
                    {
                        $update = $db->prepare('DELETE FROM group_members WHERE user_id = ? AND group_id = ?');
                        $update->execute(array($user, $group));
                        echo '<p>Le membre a bien été supprimé du groupe</p> <p><a href="index.php?p=guilds">Cliquez ici</a> pour continuer.</p>';
                    }
                    else
                    {
                        echo '<p>Navré, mais ce personnage n\'est pas dans ce groupe.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
                    }
                }
                else
                {
                    echo '<p>Navré, mais ce groupe n\'existe pas.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
                }
            }
            else
            {
                echo '<p>Navré mais ce personnage n\'existe pas.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
            }
        }
        else
        {
            echo '<p>Navré, mais vous n \'avez pas les permissions suffisantes pour effectuer cette requête.</p>';
        }
    }
    elseif (isset($_GET['up']) AND $_SESSION['connected'])
    {
        $group = intval($_GET['from']);
        $user = intval($_GET['up']);
        
    $supersel = $db->prepare('SELECT gm.id, gm.user_id, gm.group_id, gm.user_rank, m.id, m.username, m.rank, m.title
    FROM group_members gm
    RIGHT JOIN member_list m ON gm.user_id = m.id
    WHERE gm.group_id = ? AND user_id = ?
    ORDER BY gm.user_rank DESC, m.rank DESC, m.username ASC');
    $supersel->execute(array($group,$user)); $line = $supersel->fetch();
    $verif0 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND user_rank > ?');
    $verif0->execute(array($_SESSION['id'], $line['user_rank']));
    $verif2 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
    $verif2->execute(array($_SESSION['id'], $group)); $line3 = $verif2->fetch();
    
        if ($_SESSION['rank'] > 5 OR $verif0->fetch() AND $line3['user_rank'] > $line['user_rank']+1)
        {
            $verif =  $db->prepare('SELECT id FROM member_list WHERE id = ?');
            $verif->execute(array($user));
            if ($verif->fetch())
            {
                $verif = $db->prepare('SELECT id FROM group_name WHERE id = ?');
                $verif->execute(array($group));
                if ($verif->fetch())
                {
                    $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
                    $verif->execute(array($user, $group));
                    if ($verif->fetch())
                    {
                        $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
                        $verif->execute(array($user, $group)); $verif = $verif->fetch();
                        if ($verif['user_rank'] < 5)
                        {
                        $update = $db->prepare('UPDATE group_members SET user_rank = user_rank + 1 WHERE user_id = ? AND group_id = ?');
                        $update->execute(array($user, $group));
                        echo '<p>Le membre a bien été promu !</p> <p><a href="index.php?p=guilds">Cliquez ici</a> pour continuer.</p>';
                        }
                        else
                        {
                            echo '<p>Navré, mais ce personnage ne peut être promu d\'avantage.</p>';
                        }
                    }
                    else
                    {
                        echo '<p>Navré, mais ce personnage n\'est pas dans ce groupe.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
                    }
                }
                else
                {
                    echo '<p>Navré, mais ce groupe n\'existe pas.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
                }
            }
            else
            {
                echo '<p>Navré mais ce personnage n\'existe pas.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
            }
        }
        else
        {
            echo '<p>Navré, mais vous n \'avez pas les permissions suffisantes pour effectuer cette requête.</p>';
        }
    }
    elseif(isset($_GET['down']) AND $_SESSION['connected'])
    {
      $group = intval($_GET['from']);
        $user = intval($_GET['down']);
        
    $supersel = $db->prepare('SELECT gm.id, gm.user_id, gm.group_id, gm.user_rank, m.id, m.username, m.rank, m.title
    FROM group_members gm
    RIGHT JOIN member_list m ON gm.user_id = m.id
    WHERE gm.group_id = ? AND user_id = ?
    ORDER BY gm.user_rank DESC, m.rank DESC, m.username ASC');
    $supersel->execute(array($group, $user)); $line = $supersel->fetch();
    $verif0 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND user_rank > ?');
    $verif0->execute(array($_SESSION['id'], $line['user_rank']));
        if ($_SESSION['rank'] > 5 OR $verif0->fetch() )
        {
            $verif =  $db->prepare('SELECT id FROM member_list WHERE id = ?');
            $verif->execute(array($user));
            if ($verif->fetch())
            {
                $verif = $db->prepare('SELECT id FROM group_name WHERE id = ?');
                $verif->execute(array($group));
                if ($verif->fetch())
                {
                    $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
                    $verif->execute(array($user, $group));
                    if ($verif->fetch())
                    {
                        $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
                        $verif->execute(array($user, $group)); $verif = $verif->fetch();
                        if ($verif['user_rank'] > 0)
                        {
                        $update = $db->prepare('UPDATE group_members SET user_rank = user_rank - 1 WHERE user_id = ? AND group_id = ?');
                        $update->execute(array($user, $group));
                        echo '<p>Le membre a bien été dégradé !</p> <p><a href="index.php?p=guilds">Cliquez ici</a> pour continuer.</p>';
                        }
                        else
                        {
                            echo '<p>Navré, mais ce personnage ne peut être dégradé d\'avantage.</p>';
                        }
                    }
                    else
                    {
                        echo '<p>Navré, mais ce personnage n\'est pas dans ce groupe.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
                    }
                }
                else
                {
                    echo '<p>Navré, mais ce groupe n\'existe pas.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
                }
            }
            else
            {
                echo '<p>Navré mais ce personnage n\'existe pas.</p><p><a href="index.php?p=guilds">Retourner à la page normale.</a></p>';
            }
        }
        else
        {
            echo '<p>Navré, mais vous n \'avez pas les permissions suffisantes pour effectuer cette requête.</p>';
        }
    }
    else
    {
?>
  <p>Ici seront regroupées les informations basiques concernant les guildes et les groupes du site</p>
  <?php
  $select = $db->query('SELECT id, name, vanish, description, guild FROM group_name WHERE vanish = 0 ORDER BY guild DESC, name ASC');
    
  while ($line = $select->fetch())
  {
    $sel = $db->prepare('SELECT gm.id, gm.user_id, gm.group_id, gm.user_rank, m.id, m.username, m.rank, m.title, m.actif, m.pnj, m.digni, m.ban, m.desert
    FROM group_members gm
    RIGHT JOIN member_list m ON gm.user_id = m.id
    WHERE gm.group_id = ?
    ORDER BY gm.user_rank DESC, m.rank DESC, m.username ASC');
    $sel->execute(array($line['id']));
    $prefixe = ($line['guild'] == 1) ? 'Guilde :: ' : 'Groupe :: ';
    $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND group_id = ?');
      $verif->execute(array($_SESSION['id'],$line['id']));
  ?>
  <h3><?=$prefixe, $line['name']?></h3>
  <p><?= $line['description']?></p>
  <img src="pics/guilds/guild_<?= $line['id']?>.png" alt="" width="90%" style="border-radius:10px;" />
  <?php if ($_SESSION['connected']) {
      if ($_SESSION['rank'] > 5 OR $verif->fetch())
  { ?>
  <form action="index.php" method="GET">
    <input type="hidden" name="p" value="guilds" />
    Ajout d'un nouveau membre : <input type="text" name="add" />
    <input type="hidden" name="for" value="<?= $line['id']?>" />
    <input type="submit" value="Confirmer" />
  </form>
  <?php }
  }?>
  <ul>
    <?php
    while ($line2 = $sel->fetch())
    {
      if ($line2['rank'] == 9) { $rank = "titan"; } elseif ($line2['rank'] == 10) { $rank = "crea";} elseif ($line2['rank'] == 3 AND $line2['active'] == 1 AND $_SESSION['rank'] > 4) {	$rank = "active";	} else { $rank = $line2['rank'];}
      $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND user_rank > ? AND group_id = ?');
      $verif->execute(array($_SESSION['id'], $line2['user_rank'],$line['id']));
      $verif2 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
      $verif2->execute(array($_SESSION['id'], $line['id'])); $line3 = $verif2->fetch();
	  
						$imgrank = $line2['rank'];
						$imgrank = ($line2['actif'] == 1)? "A" : $imgrank;
						$imgrank = ($line2['digni'] == 1)? "D1" : $imgrank;
						$imgrank = ($line2['digni'] == 2)? "D2" : $imgrank;
						$imgrank = ($line2['digni'] == 3)? "D3" : $imgrank;
						$imgrank = ($line2['pnj'] == 1)? "PNJ" : $imgrank;
						$imgrank = ($line2['pnj'] == 3)? "E" : $imgrank;
						$imgrank = ($line2['pnj'] == 2)? "DIEU" : $imgrank;
						$imgrank = ($line2['desert'] == 1)? "DEL" : $imgrank;
						$imgrank = ($line2['ban'] == 1)? "BAN" : $imgrank;
	  
      ?>
      <li>
        [G<?= $line2['user_rank']?>] <img src="pics/rank/Grade<?= $imgrank?>.png" alt="" class="magie_type" width="25" /> <?= $line2['title'], ' ', $line2['username']?> <?php
        if ($_SESSION['connected']) {
            if ($_SESSION['rank'] > 5 OR $verif->fetch()) {
          ?><a href="index.php?p=guilds&del=<?= $line2['user_id']?>&from=<?= $line['id']?>" class="name7">[X]</a><?php if ($line2['user_rank'] >= 0 AND $line2['user_rank'] < 5 AND $line3['user_rank'] > $line2['user_rank']+1 OR $line2['user_rank'] >= 0 AND $line2['user_rank'] < 5 AND $_SESSION['rank'] > 5) { ?> <a href="index.php?p=guilds&up=<?= $line2['user_id']?>&from=<?= $line['id']?>" class="name5">[+]</a><?php } echo ' '; if ($line2['user_rank'] > 0 AND $line3['user_rank'] > $line2['user_rank'] OR $line2['user_rank'] > 0 AND $_SESSION['rank'] > 5) { ?><a href="index.php?p=guilds&down=<?= $line2['user_id']?>&from=<?= $line['id']?>" class="name6">[-]</a><? }
        }
        }?>
      </li>
      <?php
    }
    ?>
  </ul>
  <?php
  }
  if ($_SESSION['rank'] > 5){
      $select = $db->query('SELECT id, name, description, vanish, guild FROM group_name WHERE vanish = 1 ORDER BY guild DESC, name ASC');
  } else {
$select = $db->prepare('SELECT gn.id, gn.name, gn.description, gn.vanish, gn.guild, gm.id AS g_id ,gm.user_id, gm.group_id, gm.user_rank
FROM group_members gm
RIGHT JOIN group_name gn ON gn.id = gm.group_id
WHERE vanish = 1 AND user_id = ?
ORDER BY gn.guild DESC, gn.name ASC');
$select->execute(array($_SESSION['id'])); }
  while ($line = $select->fetch())
  {
   $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND group_id = ?');
      $verif->execute(array($_SESSION['id'],$line['id']));
     $sel = $db->prepare('SELECT gm.id, gm.user_id, gm.group_id, gm.user_rank, m.id, m.username, m.rank, m.title, m.actif, m.pnj, m.digni, m.ban, m.desert
    FROM group_members gm
    RIGHT JOIN member_list m ON gm.user_id = m.id
    WHERE gm.group_id = ?
    ORDER BY gm.user_rank DESC, m.rank DESC, m.username ASC');
    $sel->execute(array($line['id']));
    $prefixe = ($line['guild'] == 1) ? 'Guilde :: ' : 'Groupe :: ';
  ?>
  <h3><?=$prefixe, $line['name']?> (groupe secret)</h3>
  <p><?= $line['description']?></p>
  <img src="pics/guilds/guild_<?= $line['id']?>.png" alt="" width="90%" style="border-radius:10px;" />
  <?php if ($_SESSION['connected']) {
  if ($_SESSION['rank'] > 5 OR $verif->fetch())
  { ?>
  <form action="index.php" method="GET">
    <input type="hidden" name="p" value="guilds" />
    Ajout d'un nouveau membre : <input type="text" name="add" />
    <input type="hidden" name="for" value="<?= $line['id']?>" />
    <input type="submit" value="Confirmer" />
  </form>
  <ul>
    <?php
  } }
    while ($line2 = $sel->fetch())
    {
      if ($line2['rank'] == 9) { $rank = "titan"; } elseif ($line2['rank'] == 10) { $rank = "crea";} elseif ($line2['rank'] == 3 AND $line2['active'] == 1 AND $_SESSION['rank'] > 4) {	$rank = "active";	} else { $rank = $line2['rank'];}
      $verif = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND user_rank > 3 AND user_rank > ? AND group_id = ?');
      $verif->execute(array($_SESSION['id'], $line2['user_rank'],$line['id']));
      $verif2 = $db->prepare('SELECT * FROM group_members WHERE user_id = ? AND group_id = ?');
      $verif2->execute(array($_SESSION['id'], $line['id'])); $line3 = $verif2->fetch();
	  
						$imgrank = $line2['rank'];
						$imgrank = ($line2['actif'] == 1)? "A" : $imgrank;
						$imgrank = ($line2['digni'] == 1)? "D1" : $imgrank;
						$imgrank = ($line2['digni'] == 2)? "D2" : $imgrank;
						$imgrank = ($line2['digni'] == 3)? "D3" : $imgrank;
						$imgrank = ($line2['pnj'] == 1)? "PNJ" : $imgrank;
						$imgrank = ($line2['pnj'] == 2)? "DIEU" : $imgrank;
						$imgrank = ($line2['desert'] == 1)? "DEL" : $imgrank;
						$imgrank = ($line2['ban'] == 1)? "BAN" : $imgrank;
      ?>
      <li>
        [G<?= $line2['user_rank']?>] <img src="pics/rank/Grade<?= $imgrank?>.png" alt="" class="magie_type" width="25" /> <?= $line2['title'], ' ', $line2['username']?> <?php
        if ($_SESSION['connected']) {
        if ($_SESSION['rank'] > 5 OR $verif->fetch()) {
          ?><a href="index.php?p=guilds&del=<?= $line2['user_id']?>&from=<?= $line['id']?>" class="name7">[X]</a><?php if ($line2['user_rank'] >= 0 AND $line2['user_rank'] < 5 AND $line3['user_rank'] > $line2['user_rank']+1 OR $line2['user_rank'] >= 0 AND $line2['user_rank'] < 5 AND $_SESSION['rank'] > 5) { ?> <a href="index.php?p=guilds&up=<?= $line2['user_id']?>&from=<?= $line['id']?>" class="name5">[+]</a><?php } echo ' '; if ($line2['user_rank'] > 0 AND $line3['user_rank'] > $line2['user_rank'] OR $line2['user_rank'] > 0 AND $_SESSION['rank'] > 5) { ?><a href="index.php?p=guilds&down=<?= $line2['user_id']?>&from=<?= $line['id']?>" class="name6">[-]</a><? }
        }
        }?>
      </li>
      <?php
    }
    ?>
  </ul>
  <?php
  }
  ?>
  <p>
      <b>
          Vous souhaitez créer un groupe ? Qu'il soit publique ou privé, adressez un Message Privé à un MJE+ en expliquant dans les détails les objectifs dudit groupe ainsi que les membres intégrés à son ouverture, il devra comporter au moins 3 personnes dont au moins un Joueur Actif pour s'assurer de l'activité du groupe.
      </b>
  </p>
  
<?php
}
}
?>
