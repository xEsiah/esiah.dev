<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

$errors = [];

// Traitement du formulaire (AVANT tout HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    $motif = trim($_POST['motivation'] ?? '');

    if ($first === '' || $last === '' || $user === '' || $pass === '' || $motif === '') {
        $errors[] = "Tous les champs doivent être remplis.";
    } else {
        // Vérifie unicité du username (authors + access_requests)
        $check = $pdo->prepare("
            SELECT 1 FROM authors WHERE username = ?
            UNION ALL
            SELECT 1 FROM access_requests WHERE username = ?
            LIMIT 1
        ");
        $check->execute([$user, $user]);
        if ($check->fetchColumn()) {
            $errors[] = "Ce nom d'utilisateur est déjà utilisé.";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO access_requests (first_name, last_name, username, password, motivation)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$first, $last, $user, password_hash($pass, PASSWORD_BCRYPT), $motif]);

        // (Optionnel) message flash à afficher sur /blog/
        $_SESSION['flash'] = "Votre demande a bien été envoyée. Un administrateur l'examinera bientôt.";

        // Redirection vers /blog/ (URL propre)
        header('Location: ' . rtrim(BASE_URL, '/') . '/', true, 303);
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1>Faire une demande d'adhésion</h1>

<form class="request-container" action="<?= rtrim(BASE_URL, '/') ?>/request_access" method="POST">
    <label for="first_name">Prénom:</label>
    <input type="text" id="first_name" name="first_name" required>

    <label for="last_name">Nom:</label>
    <input type="text" id="last_name" name="last_name" required>

    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>

    <label for="motivation">Motivation pour rejoindre les auteurs:</label>
    <textarea id="motivation" name="motivation" rows="4" required></textarea>

    <input type="submit" value="Envoyer la demande">
</form>

<a class="centrer_retour_index" href="/blog/">← Retour à l'accueil</a>
<?php include '../includes/footer.php'; ?>