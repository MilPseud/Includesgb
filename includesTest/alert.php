<?php

function alert() {
    global $db, $_POST, $_SESSION, $_GET;

    $id = $_SESSION['id'];
    $requser = $db->prepare('SELECT * FROM member_list WHERE id = ?');
    $requser->execute(array($id));
    $userinfo = $requser->fetch();

    $reqalt = $db->prepare('SELECT * FROM div_alert WHERE id = ?');
    $reqalt->execute(array($id));
    $altinfo = $reqalt->fetch();
    ?>

    <div class="alertrouge">
        <h2>
            Alpha !
        </h2>
        <p>GaaranStröm est dans sa période Alpha !<br>
            Le Serveur est dès à présent ouvert !<br>
            Vous pourrez vous connecter après que votre candidature aie été validée !</p>
        <img width="100%" src="http://status.mclive.eu/GaaranStröm/gaaranstrom.craft.gg/banner.png"></img>
    </div>

    <?php
    if ($_SESSION['connected']) {
        $countmp = $db->prepare('SELECT id FROM mp WHERE receiver = ? AND statut = 0');
        $countmp->execute(array($_SESSION['id']));
        $mpcount = $countmp->rowCount();
        if ($mpcount >= 1) {
            ?>
            <div class="alert">
                <span class="notif">
                    <?= $mpcount ?> nouveau<?php if ($mpcount >= 2) { ?>x<?php } ?> Messages !
                </span>
                <br>
                <br>
                <span>
                    Vous pouvez les lire dès maintenant !
                    <br>
                    <br>
                    <a href="index.php?p=mp&mode=read">
                        Cliquez ici !
                    </a>
                </span>
            </div>
            <?php
        }
    }
    ?>

    <?php
    if ($_SESSION['connected']) {
        if ($_SESSION['rank'] == 0) {
            if ($altinfo['Candid'] == 0) {
                ?>
                <div class="alert">
                    <h2>
                        La Candidature !
                    </h2>
                    <span>
                        Il est temps de faire votre Candidature ! Voici le lien !
                        <a href="index.php?p=candid&perso=<?= $_SESSION['id'] ?>">
                            Cliquez ici !
                        </a>
                    </span>
                </div>
                <?php
            } elseif ($altinfo['Candid'] == 1) {
                ?>
                <div class="alert">
                    <h2>
                        La Candidature !
                    </h2>
                    <p>
                        Votre Candidature a bien était reçue et est en cours de Validation !<br>
                        Merci de bien vouloir patienter !
                    </p>
                </div>
                <?php
            } elseif ($altinfo['Candid'] == 3) {
                ?>
                <div class="alert">
                    <h2>
                        La Candidature !
                    </h2>
                    <p>
                        Votre Candidature a été refusée... Mais vous pouvez toujours retenter !
                        <br>
                        <a href="index.php?p=candid&perso=<?= $_SESSION['id'] ?>">
                            Cliquez ici !
                        </a>
                    </p>
                </div>
                <?php
            }
        }
    }
    ?>

    <?php
    if ($_SESSION['connected']) {
        if ($_SESSION['rank'] >= 0) {
            if ($altinfo['Editperso'] == 0) {
                ?>
                <div class="alert">
                    <h2>
                        Votre personnage !
                    </h2>
                    <p>
                        Vous devez impérativement remplir votre fiche personnage !                        
                        <a href="index.php?p=editprofile&perso=<?= $_SESSION['id'] ?>">
                            Cliquez ici !
                        </a>
                    </p>
                </div>
                <?php
            }
        }
    }
    ?>

    <?php
    if ($_SESSION['connected']) {
        if (($_SESSION['rank'] >= 4 AND $_SESSION['pnj'] == 0) OR ( $_SESSION['digni'] != 0)) {
            if ($altinfo['staffteam'] == 0) {
                ?>
                <div class="alert">
                    <h2>
                        Votre présentation !
                    </h2>
                    <p>
                        En tant que Cadre fraichement gradé, vous pouvez avoir une présentation de vous même sur la Page du Staff !
                        <br>
                        <br>
                        <a href="index.php?p=staffedit&perso=<?= $_SESSION['id'] ?>">
                            Cliquez ici !
                        </a>
                    </p>
                </div>
                <?php
            }
        }
    }
    ?>

    <?php
}
?>