<?php

function nav() {
    global $db, $_SESSION, $_POST, $_GET;
    ?>

    <?php
//Le méchant, le méchant, oui c'est lui c'est le méchant.~
//Il s'habille tout en noir, c'est un méchant.~
//Est-ce que vous avez compris ?~
    ?>
    <?php
    if ($_SESSION['connected']) {

        $Nav = $db->prepare("SELECT id, username, rank FROM member_list WHERE id = ?");
        $Nav->execute(array($_SESSION['id']));
        $Nav2 = $Nav->fetch();

        $Nav2['rank'] = $_SESSION['rank'];
        $Nav2['username'] = $_SESSION['username'];

        switch ($_SESSION['rank']) {
            default : $color = "#555550";
                break;
            case 1: $color = "#00AA00";
                $color = ($_SESSION['actif'] == 1) ? "#FF5555" : $color;
                $color = ($_SESSION['digni'] == 3) ? "#5555FF" : $color;
                break;
            case 2: $color = "#55FF55";
                $color = ($_SESSION['actif'] == 1) ? "#FF5555" : $color;
                $color = ($_SESSION['digni'] == 3) ? "#5555FF" : $color;
                break;
            case 3: $color = "#FF55FF";
                break;
            case 4: $color = "#00AAAA";
                $color = ($_SESSION['pnj'] == 1) ? "#AAAAAA" : $color;
                $color = ($_SESSION['pnj'] == 3) ? "#55FFFF" : $color;
                $color = ($_SESSION['digni'] == 2) ? "#FFFF55" : $color;
                break;
            case 5: $color = "#FFAA00";
                $color = ($_SESSION['pnj'] == 2) ? "#0200A6" : $color;
                $color = ($_SESSION['digni'] == 1) ? "#AA00AA" : $color;
                break;
            case 6: $color = "#AA0000";
                break;
            case 7: $color = "#000000";
                break;
        }

        if ($_SESSION['pnj'] == 2) {
            $stylebasic = false;
            $styledieu = "text-shadow: 0.5px 0.5px 1px #FFFFFF;";
        } else {
            $stylebasic = "text-shadow: 2px 2px 2px #000000;";
            $styledieu = false;
        }
        ?>

        <div class="nav">
            <ul>
                <li>
                    <div class="link">
                        Bienvenue <br>
        <?php
        if (file_exists("pics/MiniImage/user_" . $_SESSION['id'] . ".png")) {
            ?>
                            <img src="pics/MiniImage/user_<?= $_SESSION['id'] ?>.png" alt="">
            <?php
        }
        ?> <span style="color:<?= $color ?>;<?= $styledieu ?><?= $stylebasic ?>"><?= $_SESSION['username'] ?> <?= $_SESSION['nom'] ?></span>
                    </div>
                </li>
            </ul>
        </div>

        <?php
    }
    ?>

    <div class="nav">
        ==Basique==
        <ul>
            <li>
                <a href="index.php">
                    <div class="link">
                        Accueil
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=rules">
                    <div class="link">
                        Règles
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=staff">
                    <div class="link">
                        Staff
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=grades">
                    <div class="link">
                        Grades
                    </div>
                </a>
            </li>
    <?php
    if ($_SESSION['connected']) {
        if ($_SESSION['rank'] == 0) {
            ?>
                    <li>
                        <a href="index.php?p=candid&perso=<?= $_SESSION['id'] ?>">
                            <div class="link">
                                Candidature
                            </div>
                        </a>
                    </li>
                    <?php
                }
            }
            if ($_SESSION['connected']) {
                if ($_SESSION['rank'] >= 0) {
                    ?>
                    <?php
                    $countspr = $db->prepare('SELECT * FROM support WHERE valid < ?');
                    $countspr->execute(array('3'));
                    $countspr2 = $countspr->rowCount();
                    ?>
                    <li>
                        <a href="index.php?p=support">
                            <div class="link">
                                Support <?php
                    if ($countspr2 != 0) {
                        ?>
                                    <span class="notif">[<?= $countspr2 ?>]</span>
                                    <?php
                                }
                                ?>
                            </div>
                        </a>
                    </li>
                    <?php
                }
            }
            ?>
            <?php
            if ($_SESSION['connected']) {
                ?>
                <li>
                    <a href="index.php?p=calendrier&month=now">
                        <div class="link WIP">
                            Calendrier
                        </div>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>

    <div class="nav">
        ==Communauté==
        <ul>
            <li>
                <a href="index.php?p=news">
                    <div class="link">
                        News
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=members">
                    <div class="link">
                        Membres
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=guilds">
                    <div class="link">
                        Groupes
                    </div>
                </a>
            </li>

            <?php
            if ($_SESSION['rank'] >= 1) {
                ?>
                <li>
                    <a href="index.php?p=sondage">
                        <div class="link">
                            Sondages <?php
                            $getvotecount = $db->prepare('SELECT yes, neutral, no FROM vote WHERE player = ? AND verr2 = ?');
                            $getvotecount->execute(array($_SESSION['id'], '0'));
                            $countvote = $getvotecount->rowCount();

                            $getsondagecount = $db->prepare('SELECT id FROM sondage WHERE rankacess < ? AND verr = 0');
                            $getsondagecount->execute(array($_SESSION['rank'] + 1));
                            $countsondage = $getsondagecount->rowCount();

                            if ($countsondage > $countvote) {
                                $sondagevote = $countsondage - $countvote;
                                ?>
                                <span>[<?= $sondagevote ?>]</span>
                                <?php
                            }
                            ?>
                        </div>
                    </a>
                </li>
                <?php
            }
            if ($_SESSION['connected']) {
                ?>
                <li>
                    <a href="index.php?p=forum">
                        <div class="link">
                            Forums Généraux
                        </div>
                    </a>
                </li>

                <li>
                    <a href="index.php?p=forumg">
                        <div class="link">
                            Forums Spéciaux
                        </div>
                    </a>
                </li>
                <?php
            }
            ?>

            <li>
                <a href="index.php?p=chatbox">
                    <div class="link">
                        Discussion
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=faq">
                    <div class="link">
                        Foire aux Questions
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <div class="nav">
        ==Univers==
        <ul>
            <li>
                <a href="index.php?p=race">
                    <div class="link">
                        Races
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=atlas">
                    <div class="link">
                        Carte du Monde
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=history">
                    <div class="link">
                        Mise en Situation
                    </div>
                </a>
            </li>

            <li>
                <a href="index.php?p=competencelist">
                    <div class="link">
                        Liste des Métiers/Compétences
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <?php
    if ($_SESSION['connected']) {
        if ($_SESSION['rank'] >= 5 OR $_SESSION['id'] == 166) {
            ?>
            <div class="nav">
                ==Administration==
                <ul>
            <?php
            if ($_SESSION['rank'] >= 6 OR $_SESSION['id'] == 171) {
                ?>
                        <li>
                            <a href="index.php?p=events">
                                <div class="link">
                                    Evènements
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?p=lexique&trad=0">
                                <div class="link">
                                    Lexique
                                </div>
                            </a>
                        </li>
                <?php
            }
            if ($_SESSION['rank'] >= 5) {
                ?>
                        <li>
                            <a href="index.php?p=keylist">
                                <div class="link">
                                    Liste des Clés
                                </div>
                            </a>
                        </li>
                <?php
                if ($_SESSION['rank'] >= 6) {
                    $countkey = $db->prepare('SELECT * FROM key_get WHERE valid = ?');
                } else {
                    $countkey = $db->prepare('SELECT kg.*, kl.id AS kl_id, kl.level FROM key_get kg RIGHT JOIN key_list kl ON kl.id = kg.keyd WHERE kg.valid = ? AND kl.level < 6');
                }
                $countkey->execute(array('0'));
                $countkey2 = $countkey->rowCount();
                ?>
                        <li>
                            <a href="index.php?p=sucesskey">
                                <div class="link">
                                    Clés des Joueurs <?php
                        if ($countkey2 != 0) {
                            ?>
                                        <span class="notif">[<?= $countkey2 ?>]</span>
                            <?php
                        }
                        ?>
                                </div>
                            </a>
                        </li>
                                    <?php
                                }
                                ?>
                </ul>
            </div>
                                <?php
                            }
                        }
                        ?>

            <?php
            if ($_SESSION['connected']) {
                if ($_SESSION['rank'] >= 4) {
                    ?>
            <div class="nav">
                ==Modération==
                <ul>
            <?php
            $countenca = $db->prepare('SELECT * FROM enca WHERE valid = ?');
            $countenca->execute(array('0'));
            $countenca2 = $countenca->rowCount();
            ?>
                    <li>
                        <a href="index.php?p=enca">
                            <div class="link">
                                Encadrement <?php
            if ($countenca2 != 0) {
                ?>
                                    <span>[<?= $countenca2 ?>]</span>
                        <?php
                    }
                    ?>
                            </div>
                        </a>
                    </li>
            <?php
            $countcandid = $db->prepare('SELECT * FROM candid WHERE Encours = ?');
            $countcandid->execute(array('1'));
            $countcandid2 = $countcandid->rowCount();
            ?>
                    <li>
                        <a href="index.php?p=candidmj&perso=0">
                            <div class="link">
                                Validation des Candidatures <?php
                                if ($countcandid2 != 0) {
                                    ?>
                                    <span class="notif">[<?= $countcandid2 ?>]</span>
                        <?php
                    }
                    ?>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?p=questcrea">
                            <div class="link">
                                Création des Quêtes
                            </div>
                        </a>
                    </li>
                                <?php
                                $countquestlist = $db->prepare('SELECT * FROM quest_list WHERE validmj = ?');
                                $countquestlist->execute(array('0'));
                                $countquestlist2 = $countquestlist->rowCount();
                                ?>
                    <li>
                        <a href="index.php?p=questlist">
                            <div class="link">
                                Liste des Quêtes <?php
                                if ($countquestlist2 != 0) {
                                    ?>
                                    <span class="notif">[<?= $countquestlist2 ?>]</span>
                <?php
            }
            ?>
                            </div>
                        </a>
                    </li>
                    <?php
                    $countquest = $db->query('SELECT * FROM quest_get WHERE valid = 2');
                    $countquest2 = $countquest->rowCount();
                    ?>
                    <li>
                        <a href="index.php?p=quest&section=staff">
                            <div class="link">
                                Validation des Quêtes <?php
                    if ($countquest2 != 0) {
                        ?>
                                    <span class="notif">[<?= $countquest2 ?>]</span>
                                    <?php
                                }
                                ?>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <?php
        }
    }
    ?>

                        <?php
                        if ($_SESSION['connected']) {
                            ?>
        <div class="nav">
            ==Fonctions==
            <ul>
                <li>
                    <a href="index.php?p=morts">
                        <div class="link WIP">
                            Journal des Morts
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <?php
    }
    ?>

    <div class="nav">
        ==Session==
        <ul>
    <?php
    if ($_SESSION['connected']) {
        ?>
                <li>
                    <a href="index.php?p=profile&perso=<?= $_SESSION['id'] ?>">
                        <div class="link">
                            Personnage
                        </div>
                    </a>
                </li>
        <?php
        $countmp = $db->prepare('SELECT * FROM mp WHERE receiver = ? AND statut = 0');
        $countmp->execute(array($_SESSION['id']));
        $countmp2 = $countmp->rowCount();
        ?>
                <li>
                    <a href="index.php?p=mp&mode=read">
                        <div class="link">
                            Messages Privés <?php
                if ($countmp2 != 0) {
                    ?>
                                <span class="notif">[<?= $countmp2 ?>]</span>
            <?php
        }
        ?>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=competence&perso=<?= $_SESSION['id'] ?>">
                        <div class="link">
                            Métiers/Compétences
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=quest">
                        <div class="link">
                            Quêtes
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=sorts">
                        <div class="link WIP">
                            Sorts
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=aptitudes">
                        <div class="link WIP">
                            Aptitudes
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=achievement&perso=<?= $_SESSION['id'] ?>">
                        <div class="link">
                            Succès
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=karma&perso=<?= $_SESSION['id'] ?>">
                        <div class="link">
                            Karma
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=avis&perso=<?= $_SESSION['id'] ?>">
                        <div class="link">
                            Avis
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=sucesskey&perso=<?= $_SESSION['id'] ?>">
                        <div class="link">
                            Clés
                        </div>
                    </a>
                </li>
                <li>
                    <a href="index.php?p=deconnect">
                        <div class="link">
                            Déconnexion
                        </div>
                    </a>
                </li>
        <?php
    } else {
        ?>
                <li>
                    <a href="index.php?p=connect">
                        <div class="link">
                            Connexion
                        </div>
                    </a>
                </li>

                <li>
                    <a href="index.php?p=register">
                        <div class="link">
                            Inscription
                        </div>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <div class="nav">
        <ul>
            <li>
                <div class="link">
                    Découvrez:
                    <br>
                    <a href="http://rpnix.com/" target="_blank">
                        <img id="nix" src="pics/Rpnix.png"> Rpnix.com
                    </a>
                    <br>
                    <a href="http://runeterra.890m.com/" target="_blank">
                        <img id="runeterra" src="pics/rt.png"> Runeterra.com
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <div class="nav">
        <ul>
            Réseaux
            <li>
                <div class="link">
                    <a href="index.php?p=convo">
                        <img src="https://lh5.ggpht.com/1CxNUEdzrREikWZoaHIU5J63x2gOxTb7R-ZIbJd51uPBFt0jUj8AX2bMOhKiIBcuAqtH=w300" alt=""> Skype
                    </a>
                </div>
            </li>
            <li>
                <div class="link">
                    <a href="https://discord.gg/BCPHean" target="_blank">
                        <img src="https://lh3.googleusercontent.com/jgHA50UkErljsRAu97XzZYrRcg3eAE0qTHdpxqCWfcd2dn9x1eDkblhVoXVWfxTTJTsF=w300" alt=""> Discord
                    </a>
                </div>
            </li>
            <li>
                <div class="link">
                    <a href="https://twitter.com/GaaranStrom" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/c/c8/Twitter_Bird.svg/1259px-Twitter_Bird.svg.png" alt=""> Twitter
                    </a>
                </div>
            </li>
            <li>
                <div class="link">
                    <a href="http://steamcommunity.com/groups/Gaaranstrom" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Steam_icon_logo.svg/1024px-Steam_icon_logo.svg.png" alt=""> Steam
                    </a>
                </div>
            </li>
            <li>
                <div class="link">
                    <img src="http://vignette1.wikia.nocookie.net/leagueoflegends/images/1/12/League_of_Legends_Icon.png/revision/latest?cb=20150402234343" alt=""> League of Legends
                </div>
            </li>
        </ul>
    </div>
    <?php
    if ($_SESSION['rank'] >= 1) {
        ?>
        <div class="nav">
            <ul>
                <li>
                    <div class="link">
                        Gradations
                        <br>
        <?php
        $grada = $db->query('SELECT gh.order, gh.idgrada, gh.text, gh.rank, gh.symbol, m.id, m.username FROM grada_history gh RIGHT JOIN member_list m ON gh.idgrada = m.id ORDER BY gh.order desc LIMIT 0, 5');
        while ($degrad = $grada->fetch()) {
            ?>
                            <table>
                                <tbody>
                                    <tr>
                                <div title="<?= $degrad['text'] ?>">
                                    [<?= $degrad['symbol'] ?>] <a href="index.php?p=profile&perso=<?= $degrad['id'] ?>"><img class="rang" src="pics/rank/Grade<?= $degrad['rank'] ?>.png"> <?= $degrad['username'] ?></a>
            <?php
        }
        ?>
                            </div>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
        </div>
        <?php
    }
    ?>
                    <?php
                    if ($_SESSION['rank'] <= 4) {
                        $login = $db->query('SELECT *
				FROM member_list
				WHERE ADDDATE(last_action, INTERVAL 15 MINUTE) > NOW() AND vanish = 0
				ORDER BY rank desc, actif desc, pnj asc, digni desc, username asc');
                        $logged = $db->query('SELECT *
				FROM member_list
				WHERE ADDDATE(last_action, INTERVAL 1 DAY) > NOW() AND ADDDATE(last_action, INTERVAL 30 MINUTE) < NOW() AND vanish = 0
				ORDER BY rank desc, actif desc, pnj asc, digni desc, username asc');
                    }

                    if ($_SESSION['rank'] >= 5) {
                        $login = $db->query('SELECT *
				FROM member_list
				WHERE ADDDATE(last_action, INTERVAL 15 MINUTE) > NOW()
				ORDER BY rank desc, actif desc, pnj asc, digni desc, username asc');
                        $logged = $db->query('SELECT *
				FROM member_list
				WHERE ADDDATE(last_action, INTERVAL 1 DAY) > NOW() AND ADDDATE(last_action, INTERVAL 30 MINUTE) < NOW()
				ORDER BY rank desc, actif desc, pnj asc, digni desc, username asc');
                    }
                    ?>
    <div class="nav">
        <ul>
            <li>
                <div class="link">
                    Connectés:
    <?php
    while ($line = $login->fetch()) {
        $imgrank = $line['rank'];
        $imgrank = ($line['actif'] == 1) ? "A" : $imgrank;
        $imgrank = ($line['actif'] == 1) ? "A" : $imgrank;
        $imgrank = ($line['digni'] == 1) ? "D1" : $imgrank;
        $imgrank = ($line['digni'] == 2) ? "D2" : $imgrank;
        $imgrank = ($line['digni'] == 3) ? "D3" : $imgrank;
        $imgrank = ($line['pnj'] == 1) ? "PNJ" : $imgrank;
        $imgrank = ($line['pnj'] == 3) ? "E" : $imgrank;
        $imgrank = ($line['pnj'] == 2) ? "DIEU" : $imgrank;
        $imgrank = ($line['desert'] == 1) ? "DEL" : $imgrank;
        $imgrank = ($line['ban'] == 1) ? "BAN" : $imgrank;
        ?>
                        <br>
                        <a href="index.php?p=profile&perso=<?= $line['id'] ?>" title="<?= $line['pseudo'] ?>"><img src="pics/rank/Grade<?= $imgrank ?>.png" alt=""/> <?= $line['username'] ?></a><?php if ($line['vanish'] == 1) { ?> [V]<?php } ?>
        <?php
    }
    ?>
        </ul>
    </div>
    </li>
    </ul>
    </div>

    <div class="nav">
        <ul>
            <li>
                <div class="link">
                    Passés Recemment:
                    <?php
                    while ($line = $logged->fetch()) {
                        $imgrank = $line['rank'];
                        $imgrank = ($line['actif'] == 1) ? "A" : $imgrank;
                        $imgrank = ($line['actif'] == 1) ? "A" : $imgrank;
                        $imgrank = ($line['digni'] == 1) ? "D1" : $imgrank;
                        $imgrank = ($line['digni'] == 2) ? "D2" : $imgrank;
                        $imgrank = ($line['digni'] == 3) ? "D3" : $imgrank;
                        $imgrank = ($line['pnj'] == 1) ? "PNJ" : $imgrank;
                        $imgrank = ($line['pnj'] == 3) ? "E" : $imgrank;
                        $imgrank = ($line['pnj'] == 2) ? "DIEU" : $imgrank;
                        $imgrank = ($line['desert'] == 1) ? "DEL" : $imgrank;
                        $imgrank = ($line['ban'] == 1) ? "BAN" : $imgrank;
                        ?>
                        <br>
                        <a href="index.php?p=profile&perso=<?= $line['id'] ?>" title="<?= $line['pseudo'] ?>"><img valign="center" src="pics/rank/Grade<?= $imgrank ?>.png" alt=""/> <?= $line['username'] ?></a><?php if ($line['vanish'] == 1) { ?> [V]<?php } ?>
        <?php
    }
    ?>
                </div>
            </li>
        </ul>
    </div>

    <?php
}
?>		