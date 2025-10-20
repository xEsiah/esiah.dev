<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . '/../../includes/globalHead.html';
?>

<body class="background-chapter0">
    <?php require_once __DIR__ . '/header.html'; ?>
    <div id="narration-box" class="narration-hidden">
        <p id="narration-text"></p>
    </div>

    <div class="page-container">
        <img src="../images/AzhariShenReversed.png" alt="Azhari" class="sprite sprite-left invisible-init"
            id="azhari" />
        <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-right invisible-init"
            id="lysandor" />
        <div class="dialogues"> <!-- Inclure les dialogues selon cette structure -->
            <div><strong>Azhari : </strong> Cette ville... ce tombeau... Ils nous forgent à coups de chaînes et
                d’illusions. Depuis que j'ai foulé les pierres de cette cité, je ne me souviens plus de ce que c'est
                que de sourire.</div>
            <div><strong>Lysandor : </strong> Ici, on ne vit pas. On survit. Et si tu n'es
                pas assez fort... tu n'es plus rien.</div>
            <div><strong>Azhari : </strong> Nous sommes des ombres ici, des armes qu'ils dressent... Moi, fils d'érudit,
                ne servant qu'à améliorer leur technologie. Toi, héritier d'une lignée condamnée à ne connaître que la
                guerre...</div>
            <div><strong>Lysandor : </strong> Et pourtant, un autre chemin existe, Azhari... Un lieu où l'esprit
                triomphe
                du glaive. Piltover.</div>
            <div><strong>Azhari : </strong>Alors fuyons. Laissons derrière nous cette prison de pierre. Devenons ce que
                nous choisissons d'être.</div>
            <div><strong>Azhari : </strong>Es-tu prêt à renoncer à tout ? Même à ton nom ?</div>
            <div><strong>Lysandor : </strong>Je suis prêt à naître de nouveau.</div>
        </div>

        <button id="cta-button" class="cta-button-dialogue invisible-init" onclick="nextDialogue()">Suivant</button>

        <div id="dialogueBox"></div>

    </div>
    <?php require_once __DIR__ . '/../../includes/footer.html'; ?>
</body>

</html>