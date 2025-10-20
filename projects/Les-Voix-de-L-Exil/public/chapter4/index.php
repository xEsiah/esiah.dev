<?php
session_start();

// Met à jour les variables à partir de POST ou session
if (isset($_POST['azhariAlive'])) {
    $azhariAlive = filter_var($_POST['azhariAlive'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $_SESSION['azhariAlive'] = $azhariAlive;
} elseif (isset($_SESSION['azhariAlive'])) {
    $azhariAlive = $_SESSION['azhariAlive'];
} else {
    $azhariAlive = false;
}

if (isset($_POST['nikas_offer']) && in_array($_POST['nikas_offer'], ['accept', 'refuse'], true)) {
    $nikas_offer = $_POST['nikas_offer'];
    $_SESSION['nikas_offer'] = $nikas_offer;
} elseif (isset($_SESSION['nikas_offer'])) {
    $nikas_offer = $_SESSION['nikas_offer'];
} else {
    // Pas de valeur valide -> redirection
    header('Location: ../chapter3/index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/../../includes/globalHead.html'; ?>

<body class="background-chapter4">
    <?php require_once __DIR__ . '/header.html'; ?>
    <div id="narration-box" class="narration-hidden">
        <div id="narration-text"></div>
    </div>
    <div class="page-container">
        <?php if ($azhariAlive === true): ?>
            <?php if ($nikas_offer === 'refuse'): ?>
                <!-- Fin 1 : Lysandor meurt, Azhari seul à Piltover -->
                <img src="../images/Nika.png" alt="Nika" class="sprite sprite-left" id="nika" />
                <img src="../images/AzhariShen.png" alt="Azhari" class="sprite sprite-right" id="azhari" />
            <?php elseif ($nikas_offer === 'accept'): ?>
                <!-- Fin 2 : Les 2 restent 3 mois à Zaun puis partent à Piltover -->
                <img src="../images/AzhariShen.png" alt="Azhari" class="sprite sprite-right-right" id="azhari" />
                <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-right" id="lysandor" />
                <img src="../images/Nika.png" alt="Nika" class="sprite sprite-left" id="nika" />
            <?php endif; ?>
        <?php else: ?>
            <?php if ($nikas_offer === 'accept'): ?>
                <!-- Fin 3 : Rester à Zaun avec Nika -->
                <img src="../images/LysandorDuCouteauReversed.png" alt="Lysandor" class="sprite sprite-left" id="lysandor" />
                <img src="../images/Teeva.png" alt="Teeva" class="sprite sprite-right-right invisible-init" id="teeva" />
                <img src="../images/Nika.png" alt="Nika" class="sprite sprite-right invisible-init" id="nika" />
            <?php elseif ($nikas_offer === 'refuse'): ?>
                <!-- Fin 4 : Aller à Piltover -->
                <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-left" id="lysandor" />
                <img src="../images/Teeva.png" alt="Teeva" class="sprite sprite-right invisible-init" id="teeva" />
                <img src="../images/Nika.png" alt="Nika" class="sprite sprite-right-right invisible-init" id="nika" />
            <?php endif; ?>
        <?php endif; ?>
        <div class="dialogues">
            <?php if ($azhariAlive): ?>
                <?php if ($nikas_offer === 'refuse'): ?>
                    <!-- Fin 1 : Lysandor meurt, Azhari seul -->
                    <div><strong>Nika :</strong> Voilà où vos choix vous ont mené. Piltover t’attend… seul.</div>
                    <div><strong>Azhari :</strong> Lysandor, je suis là. Mais sans toi, la lumière de cette cité me paraît bien
                        terne.</div>
                    <div><strong>Azhari :</strong> Ce n’est pas le futur qu’on s’était promis en fuyant Noxus...</div>
                <?php elseif ($nikas_offer === 'accept'): ?>
                    <!-- Fin 2 : Azhari + Lysandor + Nika, 3 persos -->
                    <div><strong>Lysandor :</strong> Trois mois de survie à Zaun... mais enfin, voici Piltover.</div>
                    <div><strong>Nika :</strong> Cette cité est peut-être votre chance. Ou votre nouveau défi, à bientôt
                        peut-être.</div>
                    <div><strong>Azhari :</strong> Ensemble, on saura y tracer notre chemin.</div>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($nikas_offer === 'accept'): ?>
                    <!-- Fin 3 : Lysandor + Nika -->
                    <div><strong>Lysandor :</strong> Piltover est belle… mais je préfère bâtir ma vie ici, dans l’ombre de Zaun.
                    </div>
                    <div><strong>Nika :</strong> Tu trouveras plus qu’un abri ici. Tu trouveras une famille.</div>
                <?php elseif ($nikas_offer === 'refuse'): ?>
                    <!-- Fin 4 : Lysandor + Teeva -->
                    <div><strong>Lysandor :</strong> Piltover… peut-être qu'ici, je trouverai le sens à tout ça.</div>
                    <div><strong>Teeva :</strong> Garde ton cap, mais reste méfiant. Ici, même la lumière peut aveugler.</div>
                    <div><strong>Nika :</strong> J’aurais voulu que tu restes. Mais je comprends… adieu, Lysandor.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div id="dialogueBox"></div>
        <button id="cta-button" class="cta-button-dialogue invisible-init">Suivant</button>
    </div>
    <script>
        const nikasOffer = <?= json_encode($nikas_offer) ?>;
        const azhariAlive = <?= json_encode($azhariAlive) ?>;
    </script>
    <?php require_once __DIR__ . '/../../includes/footer.html'; ?>
</body>

</html>