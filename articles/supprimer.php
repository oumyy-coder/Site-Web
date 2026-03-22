<?php
session_start();
require '../connexion_db.php';

if (!isset($_SESSION['role']) || 
    !in_array($_SESSION['role'], ['editeur', 'admin'])) {
    header('Location: ../connexion.php');
    exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: liste.php');
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute([':id' => $id]);
$article = $stmt->fetch();

if (!$article) {
    header('Location: liste.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: liste.php?succes=supprime');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer un article</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="form.css">
</head>
<body>

<?php require '../menu.php'; ?>

<div class="form-container">
    <div class="form-header">
        <h1>Supprimer un article</h1>
        <a href="liste.php" class="btn-retour">← Retour</a>
    </div>

    <div class="alert alert-erreur">
        Êtes-vous sûr de vouloir supprimer cet article ?
    </div>

    <div class="article-apercu">
        <p class="apercu-titre"><?= htmlspecialchars($article['titre']) ?></p>
        <p class="apercu-desc"><?= htmlspecialchars($article['description']) ?></p>
        <p class="apercu-date">Publié le <?= date('d M Y', strtotime($article['date_publication'])) ?></p>
    </div>

    <form method="POST" action="">
        <div class="form-actions">
            <button type="submit" class="btn-danger">Oui, supprimer</button>
            <a href="liste.php" class="btn-annuler">Annuler</a>
        </div>
    </form>
</div>

</body>
</html>