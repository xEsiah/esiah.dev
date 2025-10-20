<?php
session_start();

// Bloque l'accès si la variable de session n'existe pas
if (!isset($_SESSION['good_answer'])) {
    header('Location: ../index.php');
    exit;
}

$good_answer = $_SESSION['good_answer'];
?>

<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . '/../../includes/globalHead.html'; ?>

<body class="background-chapter2">
    <?php require_once __DIR__ . '/header.html'; ?>
    <div id="narration-box" class="narration-hidden">
        <p id="narration-text"></p>
    </div>

    <div class="page-container">
        <img src="../images/Contrebandier.png" alt="Contrebandier" class="sprite sprite-left invisible-init hidden"
            id="contrebandier" />
        <img src="../images/AzhariShenReversed.png" alt="Azhari" class="sprite sprite-left invisible-init"
            id="azhari" />
        <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-right invisible-init"
            id="lysandor" />

        <div class="dialogues">
            <div><strong>Azhari : </strong> C’est donc ça, franchir l’Empire… Ce n’est pas une victoire. C’est un
                arrachement.</div>
            <div><strong>Lysandor : </strong> On ne quitte jamais vraiment Noxus. On s’arrache à elle comme on
                s’arracherait un bout d’âme infectée.</div>
            <div><strong>Contrebandier : </strong> Vous avez fait vite. Ou alors, vous avez fui avec l’urgence de ceux
                qui n’ont plus rien à perdre.</div>
            <div><strong>Lysandor : </strong> Qui es-tu ? Et comment sais-tu qui nous sommes ?</div>
            <div><strong>Contrebandier : </strong> Je suis un passeur. Rien de plus. Je sais ce que je dois savoir. Et
                je suis là pour vous proposer une chose simple : un passage sûr, discret… à condition de m’écouter.
            </div>
            <div><strong>Azhari : </strong> Et pourquoi t’aiderait-on à prix inconnu ?</div>
            <div><strong>Contrebandier : </strong> Parce que si vous refusez, je repars. Et mon prochain arrêt s’appelle
                Quartiers Noirs. Là où un certain Grand Général aime qu’on lui rapporte ce que ses chiens n’ont pas su
                attraper.</div>
            <div><strong>Lysandor : </strong> Swain...</div>
            <div><strong>Contrebandier : </strong> Je vous propose un marché. Vous m’offrez un peu d’or, ou quelque
                chose de plus... symbolique. En échange, je vous accompagne au-delà des Montagnes de Varjus et j’oublie
                que vous êtes passés. Puis vous continuerez par bateau de Rokrund à Zaun, par vos propres moyens.</div>
            <div><strong>Azhari : </strong> Et si on refuse ?</div>
            <div><strong>Contrebandier : </strong> Alors le nom de Lysandor, deviendra une rumeur. Puis un ordre. Et
                ensuite… une cible.</div>
            <div><strong>Lysandor : </strong> On doit choisir entre payer le prix ou la traque.</div>
        </div>

        <div id="dialogueBox"></div>
        <button id="cta-button" class="cta-button-dialogue invisible-init">Suivant</button>

        <form id="choice-form" method="post" action="../chapter3/index.php">
            <input type="hidden" name="good_answer" value="<?php echo htmlspecialchars($good_answer); ?>">
            <button type="submit" name="paid" value="true" class="choice-button">Payer</button>
            <button type="submit" name="paid" value="false" class="choice-button">Refuser</button>
        </form>
    </div>

    <?php require_once __DIR__ . '/../../includes/footer.html'; ?>
    <script>
        const goodAnswer = <?php echo json_encode($good_answer === 'true'); ?>;
    </script>
</body>

</html>