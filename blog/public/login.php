<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!$username || !$password) {
        $errors[] = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM authors WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'is_admin' => !empty($user['is_Administration']),
            ];
            header('Location: ' . rtrim(BASE_URL, '/') . '/admin/', true, 303);
            exit;
        } else {
            $errors[] = "Identifiants incorrects.";
        }
    }
}
?>

<?php require_once __DIR__ . '/../includes/header.php'; ?>

<h2>Connexion</h2>

<?php foreach ($errors as $error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endforeach; ?>

<form method="POST">
    <label>Nom d'utilisateur :</label><br>
    <input type="text" name="username" required><br><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>