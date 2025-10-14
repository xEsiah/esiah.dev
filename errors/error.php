<?php
// /errors/error.php
// Usage via .htaccess : /errors/error.php?c=404

$code = isset($_GET['c']) ? (int) $_GET['c'] : 500;

// 🔤 Détection de langue basique (fallback = fr)
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
$isEn = strtolower($lang) === 'fr';

// --- Libellés FR ---
$labelsFR = [
    400 => ["Requête invalide", "La requête est invalide ou incomplète."],
    401 => ["Non autorisé", "Authentification requise pour accéder à cette ressource."],
    403 => ["Accès interdit", "Vous n'avez pas les droits pour accéder à cette ressource."],
    404 => ["Page introuvable", "La page que vous cherchez est introuvable ou a été déplacée."],
    500 => ["Erreur serveur", "Une erreur interne est survenue. On s'en occupe !"],
];

// --- Libellés EN ---
$labelsEN = [
    400 => ["Bad Request", "The request was invalid or incomplete."],
    401 => ["Unauthorized", "Authentication is required to access this resource."],
    403 => ["Forbidden", "You don’t have permission to access this resource."],
    404 => ["Page Not Found", "The page you’re looking for could not be found or has been moved."],
    500 => ["Server Error", "An internal server error occurred. We're on it!"],
];

$labels = $isEn ? $labelsEN : $labelsFR;
if (!array_key_exists($code, $labels))
    $code = 500;

http_response_code($code);
list($title, $desc) = $labels[$code];

// --- Liens utiles ---
$baseHome = "/";
$contactUrl = "/contact/";
$projects = "/projects/";
?>
<!DOCTYPE html>
<html lang="<?= $isEn ? 'en' : 'fr' ?>">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= $code ?> — <?= htmlspecialchars($title) ?> | Esiah's Universe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex" />

    <link rel="icon" href="/manag_access/image.php?file=icon.jpg" />
    <link rel="stylesheet" href="/manag_access/style.php?file=global.css" />
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet" />

    <style>
        .error-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: .75rem;
        }

        .error-code {
            font-family: "Audiowide", sans-serif;
            font-size: clamp(3rem, 12vw, 8rem);
            color: var(--clr-accent);
            margin: 0;
            text-shadow: 0 6px 24px rgba(0, 0, 0, .35);
        }

        .error-title {
            font-size: clamp(1.5rem, 4vw, 2.25rem);
            margin: 0;
        }

        .error-desc {
            max-width: 60ch;
            margin-inline: auto;
            color: var(--clr-muted);
        }

        .error-actions {
            display: flex;
            gap: .8rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: .5rem;
        }

        .error-details {
            margin-top: var(--space-4);
            opacity: .9;
            font-size: .95rem;
            color: color-mix(in oklab, var(--clr-text) 75%, var(--clr-muted));
        }

        .error-details code {
            padding: .15rem .4rem;
            border: 1px solid var(--clr-border);
            background: color-mix(in oklab, var(--clr-surface) 85%, transparent);
            border-radius: 8px;
            font-family: ui-monospace, Menlo, Consolas, monospace;
        }
    </style>
</head>

<body>
    <main>
        <section class="panel hero container" data-bg="0">
            <div class="error-hero">
                <p class="error-code"><?= $code ?></p>
                <h1 class="error-title"><?= htmlspecialchars($title) ?></h1>
                <p class="error-desc"><?= htmlspecialchars($desc) ?></p>

                <div class="error-actions">
                    <a class="btn" href="<?= htmlspecialchars($baseHome) ?>">
                        <?= $isEn ? '← Back to Home' : '← Retour à l’accueil' ?>
                    </a>
                    <a class="btn outline" href="<?= htmlspecialchars($projects) ?>">
                        <?= $isEn ? 'Browse Projects' : 'Voir les projets' ?>
                    </a>
                    <a class="btn outline" href="<?= htmlspecialchars($contactUrl) ?>">
                        <?= $isEn ? 'Contact me' : 'Me contacter' ?>
                    </a>
                </div>

                <?php if ($code === 404): ?>
                    <div class="error-details">
                        <p><?= $isEn ? 'Requested URL:' : 'URL demandée :' ?>
                            <code><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') ?></code>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>

</html>