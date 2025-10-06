<?php
http_response_code(503);
require_once __DIR__ . '/../config/config.php';
$BASE = rtrim(BASE_URL, '/');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Esiah's Corner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="<?= $BASE ?>/images/icon.ico" />
    <link rel="stylesheet" href="<?= $BASE ?>/css/index.css" />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet" />
</head>

<body>
    <header>
        <div>
            <h1>Esiah's Corner</h1>
            <p>Le forum de l'élitisme</p>
        </div>
    </header>

    <div class="margin_body_sauf_headerfooter">
        <h2 style="font-size: 3rem;">Service temporairement indisponible</h2>
        <p style="font-size: 1.5rem;">Base de données inaccessible. Merci de revenir plus tard.</p>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Esiah's Corner — Tous droits réservés.</p>
        <p><a href="<?= $BASE ?>/mentions">Mentions légales</a></p>
    </footer>
</body>

</html>