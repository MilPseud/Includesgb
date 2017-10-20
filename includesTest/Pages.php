<?php

function Pages() {
    global $db, $_GET;
    $page = (isset($_GET['p'])) ? $_GET['p'] : '';

    $reqreload = $db->prepare("SELECT * FROM member_list WHERE id = ?");
    $reqreload->execute(array($_SESSION['id']));

    if ($_SESSION['connected'] == true) {
        $reloadinfo = $reqreload->fetch();
        $_SESSION['username'] = $reloadinfo['username'];
        $_SESSION['mail'] = $reloadinfo['mail'];
        $_SESSION['color'] = $reloadinfo['color'];
        $_SESSION['rank'] = $reloadinfo['rank'];
        $_SESSION['nom'] = $reloadinfo['nom'];
        $_SESSION['title'] = $reloadinfo['title'];
        $_SESSION['pseudo'] = $reloadinfo['pseudo'];
        $_SESSION['pnj'] = $reloadinfo['pnj'];
        $_SESSION['digni'] = $reloadinfo['digni'];
        $_SESSION['actif'] = $reloadinfo['actif'];
        $_SESSION['ban'] = $reloadinfo['ban'];
        $_SESSION['desert'] = $reloadinfo['desert'];
        $_SESSION['connected'] = true;
        $_SESSION['userinfo'] = true;
    }

    $Ban = $db->prepare("SELECT id, username, rank, ban, desert FROM member_list WHERE id = ?");
    $Ban->execute(array($_SESSION['id']));
    $Ban2 = $Ban->fetch();

    $Timer = $db->prepare("UPDATE member_list SET last_action = NOW() WHERE id = ?");
    $Timer->execute(array($_SESSION['id']));


    if ($Ban2['ban'] == 1) {
        header('Location: index.php?p=deconnect');
    }

    if ($Ban2['desert'] == 1) {
        header('Location: index.php?p=deconnect');
    }
    ?>
    <div id="largeur">
        <table>
            <tbody>
            <header>
                <tr>
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="index.php">
                                            <img src="pics/gslog2.png" alt=""/>
                                        </a>
                                    </td>
                                    <td>
    <?php
    $allnews = $db->query('SELECT * FROM news ORDER BY id DESC LIMIT 0, 1');
    while ($news = $allnews->fetch()) {
        ?>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <? echo $news['news']; ?>
                                                    </tr>
                                                </tbody>
                                            </table>
        <?php
    }
    ?>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="https://minecraft.net/fr/">
                                                <img src="pics/minecraftlogo.png" alt="" target=_blank title="Site officiel de Minecraft">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr></header>
            <tr>
                <td>
    <?php include ('includesTest/nav.php');
    nav(); ?>
                </td>
                <td>
                    <?php include ('includesTest/alert.php');
                    alert(); ?>
                    <div class="main">
                    <?php
                    switch ($page) {
                        default : {
                                include('includesTest/home.php');
                                break;
                            }
                        case '': {
                                include('includesTest/home.php');
                                home();
                                break;
                            }
                        case 'members': {
                                include('includesTest/members.php');
                                members();
                                break;
                            }
                        case 'connect': {
                                include('includesTest/connect.php');
                                connect();
                                break;
                            }
                        case 'deconnect': {
                                include('includesTest/deconnect.php');
                                deconnect();
                                break;
                            }
                        case 'register': {
                                include('includesTest/register.php');
                                register();
                                break;
                            }
                        case 'rules': {
                                include('includesTest/rules.php');
                                rules();
                                break;
                            }
                        case 'staff': {
                                include('includesTest/staffteam.php');
                                staff();
                                break;
                            }
                        case 'staffedit': {
                                include('includesTest/staffteamedit.php');
                                staffedit();
                                break;
                            }
                        case 'history': {
                                include('includesTest/history.php');
                                history();
                                break;
                            }
                        case 'convo': {
                                include('includesTest/convo.php');
                                convo();
                                break;
                            }
                        case 'atlas': {
                                include('includesTest/atlas.php');
                                atlas();
                                break;
                            }
                        case 'news': {
                                include('includesTest/news.php');
                                news();
                                break;
                            }
                        case 'mp': {
                                include('includesTest/mp.php');
                                mp();
                                break;
                            }
                        case 'chatbox': {
                                include('includesTest/chatboxfull.php');
                                chatbox();
                                break;
                            }
                        case 'chatboxAjax': {
                                include('includesTest/chatboxAjax.php');
                                chatboxAjax();
                                break;
                            }
                        case 'profile': {
                                include('includesTest/profile.php');
                                profile();
                                break;
                            }
                        case 'editprofile': {
                                include('includesTest/editprofile.php');
                                editprofile();
                                break;
                            }
                        case 'editprofilemj': {
                                include('includesTest/editprofilemj.php');
                                editprofilemj();
                                break;
                            }
                        case 'race': {
                                include('includesTest/race.php');
                                race();
                                break;
                            }
                        case 'forum': {
                                include('includesTest/forum.php');
                                forum();
                                break;
                            }
                        case 'forumg': {
                                include('includesTest/forum.php');
                                forum();
                                break;
                            }
                        case 'grades': {
                                include('includesTest/grades.php');
                                grades();
                                break;
                            }
                        case 'alert': {
                                include('includesTest/alert.php');
                                alert();
                                break;
                            }
                        case 'candid': {
                                include('includesTest/candid.php');
                                candid();
                                break;
                            }
                        case 'candidmj': {
                                include('includesTest/candidmj.php');
                                candidmj();
                                break;
                            }
                        case 'faq': {
                                include('includesTest/faq.php');
                                faq();
                                break;
                            }
                        case 'sucesskey': {
                                include('includesTest/key.php');
                                sucesskey();
                                break;
                            }
                        case 'achievement': {
                                include('includesTest/achievement.php');
                                achievement();
                                break;
                            }
                        case 'achievementmj': {
                                include('includesTest/achievementmj.php');
                                achievementmj();
                                break;
                            }
                        case 'keylist': {
                                include('includesTest/keylist.php');
                                keylist();
                                break;
                            }
                        case 'lexique': {
                                include('includesTest/lexique.php');
                                lexique();
                                break;
                            }
                        case 'testpage': {
                                include('includesTest/testpage.php');
                                testpage();
                                break;
                            }
                        case 'guilds': {
                                include('includesTest/guilds.php');
                                guilds();
                                break;
                            }
                        case 'competence': {
                                include('includesTest/competence.php');
                                competence();
                                break;
                            }
                        case 'competencelist': {
                                include('includesTest/competencelist.php');
                                competencelist();
                                break;
                            }
                        case 'sondage': {
                                include('includesTest/sondage.php');
                                sondage();
                                break;
                            }
                        case 'enca': {
                                include('includesTest/enca.php');
                                enca();
                                break;
                            }
                        case 'avis': {
                                include('includesTest/avis.php');
                                avis();
                                break;
                            }
                        case 'quest': {
                                include('includesTest/quest.php');
                                quest();
                                break;
                            }
                        case 'questlist': {
                                include('includesTest/questlist.php');
                                questlist();
                                break;
                            }
                        case 'questcrea': {
                                include('includesTest/questcrea.php');
                                questcrea();
                                break;
                            }
                        case 'support': {
                                include('includesTest/support.php');
                                support();
                                break;
                            }
                        case 'delaccount': {
                                include('includesTestdelete.php');
                                delaccount();
                                break;
                            }
                        case 'karma': {
                                include('includesTest/karma.php');
                                karma();
                                break;
                            }
                        case 'sorts': {
                                include('includesTest/sorts.php');
                                sorts();
                                break;
                            }
                        case 'aptitudes': {
                                include('includesTest/aptitudes.php');
                                aptitudes();
                                break;
                            }
                        case 'events': {
                                include('includesTest/events.php');
                                events();
                                break;
                            }
                        case 'calendrier': {
                                include('includesTest/calendrier.php');
                                calendrier();
                                break;
                            }
                        case 'morts': {
                                include('includesTest/morts.php');
                                morts();
                                break;
                            }
                        case '400': {
                                include('includesTest/400.php');
                                break;
                            }
                        case '401': {
                                include('includesTest/401.php');
                                break;
                            }
                        case '403': {
                                include('includesTest/403.php');
                                break;
                            }
                        case '404': {
                                include('includesTest/404.php');
                                break;
                            }
                        case '500': {
                                include('includesTest/500.php');
                                break;
                            }
                    }
                    ?>
                    </div>
    <?php
    if (function_exists('chatbox')) {
        
    } else {
        ?>
                        <div class="main">
        <?php
        include ('includesTest'
                . '/chatbox.php');
        chatbox();
        ?>
                        </div>
        <?php
    }
    ?>
                </td>
            </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td>
                        <footer>
                            GaaranStröm est un serveur à destination des joueurs de Minecraft. Le contenu de ce serveur est une fiction - 2016
                            <br>L'ensemble du Code de GaaranStröm appartient à Elenyah et Nikho - 2017
                            <br>Tout droits réservés à Brumen - 2017
                        </footer>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
?>