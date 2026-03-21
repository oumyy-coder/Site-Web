<?php
session_start();

// Si déjà connecté, rediriger vers l'accueil
if (isset($_SESSION['user_id'])) {
    header('Location: accueil.php');
    exit;
}

require_once __DIR__ . '/db.php';

$erreur = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = trim($_POST['login'] ?? '');
    $mdp   = $_POST['mot_de_passe'] ?? '';

    // Validation PHP — obligatoire pour la sécurité
    if (empty($login) || empty($mdp)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $pdo  = getConnexion();
        $stmt = $pdo->prepare(
            'SELECT id, nom, prenom, login, mot_de_passe, role
             FROM utilisateurs
             WHERE login = ?
             LIMIT 1'
        );
        $stmt->execute([$login]);
        $utilisateur = $stmt->fetch();

        if ($utilisateur && password_verify($mdp, $utilisateur['mot_de_passe'])) {
            // Connexion réussie — on régénère l'ID de session (protection fixation)
            session_regenerate_id(true);

            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['nom']     = $utilisateur['nom'];
            $_SESSION['prenom']  = $utilisateur['prenom'];
            $_SESSION['login']   = $utilisateur['login'];
            $_SESSION['role']    = $utilisateur['role'];

            header('Location: accueil.php');
            exit;
        } else {
            $erreur = 'Login ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Projet News</title>
    <?php require_once __DIR__ . '/entete.php'; ?>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>
<?php require_once __DIR__ . '/menu.php'; ?>

<div class="connexion-container">
    <h1>Connexion</h1>

    <?php if ($erreur): ?>
        <div class="alerte-erreur">
            <?= htmlspecialchars($erreur) ?>
        </div>
    <?php endif; ?>

    <form id="form-connexion" method="POST" action="connexion.php" novalidate>

        <div class="form-group">
            <label for="login">Login</label>
            <input
                type="text"
                id="login"
                name="login"
                value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                autocomplete="username"
                placeholder="Votre identifiant"
            >
            <span class="msg-erreur-js" id="err-login">Le login est obligatoire.</span>
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input
                type="password"
                id="mot_de_passe"
                name="mot_de_passe"
                autocomplete="current-password"
                placeholder="Votre mot de passe"
            >
            <span class="msg-erreur-js" id="err-mdp">Le mot de passe est obligatoire.</span>
        </div>

        <button type="submit" class="btn-connexion">Se connecter</button>
    </form>
</div>

<script src="connexion.js"></script>

</body>
</html>
