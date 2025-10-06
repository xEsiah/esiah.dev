<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Esiah's Corner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= rtrim(BASE_URL, '/') ?>/images/icon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/css/index.css">

</head>

<body>
    <header>
        <div>
            <h1>Esiah's Corner</h1>
            <p>Le forum de l'élitisme</p>
        </div>

        <nav>
            <a href="/" class="btn-home" title="Retour à Esiah.dev">to esiah.dev</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/">Accueil</a>

            <?php if (!empty($_SESSION['user'])): ?>
                <a href="<?= rtrim(BASE_URL, '/') ?>/admin/article_creation">Créer un post</a>
                <a href="<?= rtrim(BASE_URL, '/') ?>/admin">Administration</a>
                <a href="<?= rtrim(BASE_URL, '/') ?>/logout">Déconnexion</a>
            <?php else: ?>
                <a href="<?= rtrim(BASE_URL, '/') ?>/request_access">Faire une demande d'adhésion</a>
                <a href="<?= rtrim(BASE_URL, '/') ?>/login">Se connecter</a>
            <?php endif; ?>
        </nav>

    </header>

    <div class="margin_body_sauf_headerfooter">