<?php
session_start();

if (
    !isset($_POST['nikas_offer']) ||
    (!in_array($_POST['nikas_offer'], ['accept', 'refuse'], true) && $_POST['nikas_offer'] !== "")
) {
    header('Location: ../index.php');
    exit;
}

$good_answer = $_SESSION['good_answer'];
$nikas_offer = $_POST['nikas_offer'];
$_SESSION['nikas_offer'] = $nikas_offer;

if (isset($_POST['azhariAlive'])) {
    $azhariAlive_raw = $_POST['azhariAlive'];
    $azhariAlive = ($azhariAlive_raw === 'true' || $azhariAlive_raw === '1');
    $_SESSION['azhariAlive'] = $azhariAlive;
} elseif (isset($_SESSION['azhariAlive'])) {
    $azhariAlive = $_SESSION['azhariAlive'];
} else {
    $azhariAlive = false;
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../includes/globalHead.html'; ?>

<body class="background-chapter3">
    <?php require_once __DIR__ . '/header.html'; ?>
    <div id="narration-box" class="narration-hidden">
        <p id="narration-text"></p>
    </div>
    <div class="page-container">
        <?php if ($azhariAlive): ?>
            <img src="../images/AzhariShen.png" alt="Azhari" class="sprite sprite-right-right invisible-init" id="azhari" />
            <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-right invisible-init"
                id="lysandor" />
            <img src="../images/Nika.png" alt="Nika" class="sprite sprite-left invisible-init" id="nika" />
        <?php else: ?>
            <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-left invisible-init"
                id="lysandor" />
            <img src="../images/Teeva.png" alt="Teeva" class="sprite sprite-right invisible-init" id="teeva" />
            <img src="../images/Nika.png" alt="Nika" class="sprite sprite-right invisible-init hidden" id="nika" />
        <?php endif; ?>
        <div class="dialogues">
            <?php
            if ($azhariAlive) {
                if ($nikas_offer === 'accept') { ?>
                    <div><strong>Nika : </strong> Vraiment, vous avez accepté mon marché?! Vous allez en suer!</div>
                    <div><strong>Lysandor : </strong> Tant qu'on rejoint bientôt Piltover ça nous va !</div>
                    <div><strong>Azhari : </strong> J'ai hâte d'atteindre cette nouvelle vie !</div>
                <?php } else { ?>
                    <div><strong>Azhari : </strong> Je ne sais pas si on peut lui faire confiance...</div>
                    <div><strong>Lysandor : </strong> On peut essayer de se débrouiller par nous-mêmes tu as
                        raison !</div>
                    <div><strong>Nika : </strong> Vraiment vous refusez mon aide?! Vous faites une grave erreur.</div>
                <?php }
            } else { ?>
                <div><strong>Lysandor :</strong> Piltover... si proche, et pourtant, je suis toujours là. Six mois à Zaun,
                    et je ne sais plus si je grimpe ou si je coule.</div>
                <div><strong>Teeva :</strong> Zaun change ceux qui y survivent. Elle teste, elle brise, mais parfois… elle
                    révèle.</div>
                <div><strong>Lysandor :</strong> L’écho d’Azhari me suit. Comme un murmure entre les conduits. Il aurait su
                    quoi faire...</div>
                <div><strong>Teeva :</strong> Il t’a laissé ses rêves, mais la route est désormais la tienne. Ne reste pas
                    figé dans ses pas.</div>
                <div><strong>Lysandor :</strong> Piltover nous fascinait. La cité des idées. Mais aujourd’hui, c’est Zaun
                    qui me confronte à la réalité.</div>
                <div><strong>Teeva :</strong> Là-haut, la lumière est forte. Ici, l’ombre t’enseigne. Où veux-tu ouvrir les
                    yeux ?</div>
                <div><strong>Lysandor :</strong> Les yeux de Nika ont croisé les miens l’autre jour. Un regard, et toutes
                    mes certitudes vacilles. Quelque chose d’indicible…</div>
                <div><strong>Teeva :</strong> Ce genre de rencontres ne sont jamais anodines. Peut-être Zaun t’envoie un
                    message?</div>
                <div><strong>Lysandor :</strong> J’y ai vu une faille dans ma douleur. Une promesse. Ou une fuite ? Je n’en
                    sais rien.</div>
                <div><strong>Teeva :</strong> Piltover offre un avenir construit. Zaun, un avenir à construire. Le confort
                    ou le combat.</div>
                <div><strong>Lysandor :</strong> Ce choix me ronge. Rester avec ce qui commence ici… ou monter là-haut, avec
                    ses fantômes.</div>
                <div><strong>Teeva :</strong> N’oublie pas : ce n’est pas la ville qui donne du sens, mais ceux qui y
                    marchent avec toi.</div>
                <div><strong>Nika :</strong> Si tu choisis l’ombre, je marcherai à tes côtés. Mais si ton cœur monte vers
                    Piltover… je le comprendrai.</div>
                <div><strong>Lysandor :</strong> Nika… j’ignorais que tu étais encore là.</div>
                <div><strong>Nika :</strong> J’ai toujours été là. Parfois, il faut juste bien ouvrir les yeux, même dans
                    les sombres profondeurs.</div>
            <?php } ?>
        </div>

        <div id="dialogueBox"></div>
        <button id="cta-button" class="cta-button-dialogue invisible-init">Suivant</button>
        <?php if (!$azhariAlive): ?>
            <form id="choice-form" method="post" action="../chapter4/index.php">
                <input type="hidden" name="azhariAlive" value="false">
                <button type="submit" name="nikas_offer" value="accept" class="choice-button">Rester à Zaun avec
                    Nika</button>
                <button type="submit" name="nikas_offer" value="refuse" class="choice-button">Partir à Piltover sans
                    Azhari</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        const nikasOffer = <?= json_encode($nikas_offer) ?>;
        const azhariAlive = <?= json_encode($azhariAlive) ?>;
        const goodAnswer = <?php echo json_encode($good_answer === 'true'); ?>;
    </script>

    <?php require_once __DIR__ . '/../../includes/footer.html'; ?>
</body>

</html>