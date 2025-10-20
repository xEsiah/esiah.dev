<?php
session_start();

// Vérifie que les données POST existent sinon redirige vers chapter2
if (!isset($_POST['good_answer']) || !isset($_POST['paid'])) {
    header('Location: ../chapter2/index.php');
    exit;
}

// Convertit les valeurs string en booléens
$good_answer = filter_var($_POST['good_answer'], FILTER_VALIDATE_BOOLEAN);
$paid = filter_var($_POST['paid'], FILTER_VALIDATE_BOOLEAN);

// Calcule azhariAlive : true seulement si good_answer ET paid sont true
$azhariAlive = $good_answer && $paid;

// Stocke en session si besoin
$_SESSION['good_answer'] = $good_answer;
$_SESSION['paid'] = $paid;
$_SESSION['azhariAlive'] = $azhariAlive;

?>
<!DOCTYPE html>
<html lang="fr">
<?php
echo '<script>const azhariAlive = ' . json_encode($azhariAlive) . ';</script>';
require_once __DIR__ . '/../../includes/globalHead.html'; ?>

<body class="background-chapter3">
    <?php require_once __DIR__ . '/header.html'; ?>
    <div id="narration-box" class="narration-hidden">
        <p id="narration-text"></p>
    </div>

    <div class="page-container">
        <?php if ($azhariAlive): ?>
            <img src="../images/AzhariShen.png" alt="Azhari" class="sprite sprite-right invisible-init" id="azhari" />
            <img src="../images/LysandorDuCouteauReversed.png" alt="Lysandor" class="sprite sprite-left invisible-init"
                id="lysandor" />
            <img src="../images/Nika.png" alt="Nika" class="sprite sprite-left invisible-init hidden" id="nika" />
        <?php else: ?>
            <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-right invisible-init"
                id="lysandor" />
            <img src="../images/Teeva.png" alt="Teeva" class="sprite sprite-right invisible-init hidden" id="teeva" />
        <?php endif; ?>
        <div class="dialogues">
            <?php if ($azhariAlive): ?>
                <div><strong>Azhari : </strong> Ce n’est pas une ville. C’est une décharge.</div>
                <div><strong>Lysandor : </strong> Mais c’est la seule route vers Piltover. Et on aura besoin d’un guide. On
                    est des étrangers ici… et ça se voit.</div>
                <div><strong>Nika : </strong> Deux types paumés, qui évitent les regards et marchent droit dans le
                    territoire de Silco ? Soit vous êtes suicidaires, soit vous êtes très, très intéressants.</div>
                <div><strong>Azhari : </strong> On veut monter. Rejoindre Piltover !</div>
                <div><strong>Nika : </strong> Personne ne monte à Piltover sans payer un tribut… en métal, en informations…
                    ou en loyauté. Mais j’ai mes raccourcis. Et mes dettes. Si vous m’aidez, je vous guide.</div>
            <?php else: ?>
                <div><strong>Lysandor : </strong> Azhari… Tu disais qu’on forge notre propre destin. Moi, je me suis forgé
                    un enfer sans toi...</div>
                <div><strong>Teeva : </strong> Eh mon garçon! T’as une tête de fuyard toi. Tu veux monter là-haut n'est-ce
                    pas ?</div>
                <div><strong>Teeva : </strong> Il te faudra un guide. Et un nom propre.</div>
                <div><strong>Lysandor : </strong> Oui s'il vous plaît je paierai... En services s'il le faut...</div>
                <div><strong>Teeva : </strong> Alors suis-moi. Et oublie qui tu étais.</div>
            <?php endif; ?>
        </div>

        <div id="dialogueBox"></div>
        <button id="cta-button" class="cta-button-dialogue invisible-init">Suivant</button>

        <?php if ($azhariAlive): ?>
            <form id="choice-form" method="post" action="../chapter3/answer.php" style="display:none;">
                <input type="hidden" name="azhariAlive" value="true">
                <button type="submit" name="nikas_offer" value="accept" class="choice-button">Accepter le marché</button>
                <button type="submit" name="nikas_offer" value="refuse" class="choice-button">Refuser le marché</button>
            </form>
        <?php endif; ?>
    </div>
    <?php require_once __DIR__ . '/../../includes/footer.html'; ?>
    <script>
        const goodAnswer = <?php echo json_encode($good_answer === 'true'); ?>;
    </script>
</body>

</html>