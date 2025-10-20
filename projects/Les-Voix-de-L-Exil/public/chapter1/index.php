<!DOCTYPE html>
<html>
<?php
require_once __DIR__ . '/../../includes/globalHead.html'; ?>

<body class="background-chapter1">
    <?php require_once __DIR__ . '/header.html'; ?>
    <div id="narration-box" class="narration-hidden">
        <p id="narration-text"></p>
    </div>

    <div class="page-container">
        <img src="../images/Darius.PNG" alt="Darius" class="sprite sprite-left hidden" id="darius" />
        <img src="../images/AzhariShenReversed.png" alt="Azhari" class="sprite sprite-left invisible-init"
            id="azhari" />
        <img src="../images/LysandorDuCouteau.png" alt="Lysandor" class="sprite sprite-right invisible-init"
            id="lysandor" />
        <div class="dialogues"> <!-- Inclure les dialogues selon cette structure -->
            <div><strong>Azhari : </strong> Si on est vus en train de quitter la place haute du bastion on risque
                d’éveiller les soupçons non ?</div>
            <div><strong>Lysandor : </strong> Effectivement mais avec ma réputation on devrait pouvoir se justifier à
                n’importe qui ! Enfin tant que nous n’avons pas à faire à Darius </div>
            <div><strong>Darius : </strong> HALTE VOUS DEUX! Lysandor te voir dans la partie basse à cette heure est
                surprenant… J’ai presque l’impression de voir deux jeunes âmes qui fuient Noxus... …Pathétique. </div>
            <div><strong>Darius : </strong>Dites-moi, pourquoi ne devrais-je pas vous abattre sur-le-champ ?</div>
            <div><strong>Azhari : </strong>Nous ne fuyons pas, nous partons en mission. Une tâche confiée par un
                supérieur, hors des frontières.</div>
            <div><strong>Darius : </strong>Une mission, hein ? Et qui est ce fameux supérieur ?</div>

        </div>
        <div id="dialogueBox"></div>
        <form id="choice-form" method="post" action="../chapter1/answer.php">
            <button type="submit" name="good_answer" value="Rammus" class="choice-button">Rammus</button>
            <button type="submit" name="good_answer" value="Marcus" class="choice-button">Marcus</button>
            <button type="submit" name="good_answer" value="Ezreal" class="choice-button">Ezreal</button>
        </form>

        <button id="cta-button" class="cta-button-dialogue invisible-init">Suivant</button>

    </div>

    <?php require_once __DIR__ . '/../../includes/footer.html'; ?>
</body>

</html>