<?php
session_start();

$azhariAlive = $_SESSION['azhariAlive'] ?? false;
$nikas_offer = $_SESSION['nikas_offer'] ?? null;
// $azhariAlive = false;
// $nikas_offer = 'refuse'; 
if ($nikas_offer === null) {
    header('Location: index.php');
    exit;
}
if ($azhariAlive) {
    if ($nikas_offer === 'refuse') {
        $backgroundImage = 'images/viewPiltoverFocused.jpeg';
    } else {
        $backgroundImage = 'images/viewPiltoverInnovationPark.jpg';
    }
} else {
    if ($nikas_offer === 'accept') {
        $backgroundImage = 'images/viewZaunUpSide.jpg';
    } else {
        $backgroundImage = 'images/viewPiltoverMainStreet.jpg';
    }
}

?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Les Voix de l'Exil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital," rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />
    <script src="javascript/main.js"></script>
    <link rel="icon" type="image/x-icon" href="/images/icon.ico" sizes="16x16 32x32 48x48 64x64 128x128 256x256" />
</head>
<header>
    <h1>Les Voix de l'Exil</h1>
    <h2>Epilogue</h2>
    <div class="roboto"></div>
</header>

<body
    style="background-image: url('<?= htmlspecialchars($backgroundImage) ?>'); background-size: cover; background-position: center;">


    <section class="introduction">
        <div class="intro-box">
            <?php if ($azhariAlive): ?>
                <?php if ($nikas_offer === 'refuse'): ?>
                    <!-- Fin 1 -->
                    <p>
                        Le vent de Piltover souffle sur un homme seul. Azhari, porteur de mémoires et de blessures, a perdu plus
                        qu’un compagnon : un idéal.
                        Dans la cité dorée, il s’efforce de faire vivre la voix d’un exilé tombé trop tôt.
                    </p>
                    <p class="quote">« Même seul, je continuerai à écrire ce que nous avons commencé. »</p>
                    <p class="author">— Azhari</p>
                <?php else: ?>
                    <!-- Fin 2 -->
                    <p>
                        Après trois mois de ténèbres à Zaun, Azhari et Lysandor posent enfin le pied à Piltover. Ensemble.
                        Leurs cicatrices ne les quittent pas,
                        mais un lien les unit désormais : celui de ceux qui ont choisi de croire, encore.
                    </p>
                    <p class="quote">« Nous n’avons pas tourné le dos à Noxus. Nous avons choisi un chemin que l’ambition seule
                        ne pouvait offrir : un avenir. »</p>
                    <p class="author">— Lysandor</p>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($nikas_offer === 'accept'): ?>
                    <!-- Fin 3 -->
                    <p>
                        La poussière de Zaun recouvre les souvenirs. Nika et Lysandor y construisent un foyer discret, loin des
                        promesses de grandeur.
                        Dans l’ombre, ils réparent ce qui peut encore l’être.
                    </p>
                    <p class="quote">« Certains cherchent la lumière. D’autres deviennent la flamme dans la nuit. »</p>
                    <p class="author">— Nika</p>
                <?php else: ?>
                    <!-- Fin 4 -->
                    <p>
                        Piltover s’élève, fière et lumineuse. Lysandor y marche seul, le cœur lesté d’adieux. Ni Noxus, ni Zaun…
                        mais peut-être une troisième voie.
                        Une ville où ses erreurs ne seront pas oubliées, mais dépassées.
                    </p>
                    <p class="quote">« Ce n’est pas la cité qui me changera. C’est ce que j’y ferai. »</p>
                    <p class="author">— Lysandor</p>
                <?php endif; ?>
            <?php endif; ?>

            <a href="index.php" class="cta-button">
                Recommencer l’histoire
            </a>
        </div>
    </section>
</body>

<?php require_once __DIR__ . '/../includes/footer.html'; ?>