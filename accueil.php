<?php
session_start();
require 'connexion_db.php';

$articles_par_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $articles_par_page;

$total = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$total_pages = ceil($total / $articles_par_page);

$stmt = $pdo->prepare("
    SELECT articles.*, categories.nom AS categorie,
           utilisateurs.nom AS auteur
    FROM articles
    JOIN categories ON articles.id_categorie = categories.id
    JOIN utilisateurs ON articles.id_auteur = utilisateurs.id
    ORDER BY date_publication DESC
    LIMIT :limite OFFSET :offset
");
$stmt->bindValue(':limite', $articles_par_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ESPACTU</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php require 'menu.php'; ?>

<div class="hero">
    <h1>Toute l'actualité en temps réel</h1>
    <p>Articles vérifiés · Mis à jour chaque jour</p>
    <div class="cats">
        <a href="accueil.php" class="cat <?= !isset($_GET['cat']) ? 'active' : '' ?>">Tous</a>
        <a href="accueil.php?cat=Technologie" class="cat">Technologie</a>
        <a href="accueil.php?cat=Sport" class="cat">Sport</a>
        <a href="accueil.php?cat=Politique" class="cat">Politique</a>
        <a href="accueil.php?cat=Education" class="cat">Éducation</a>
        <a href="accueil.php?cat=Culture" class="cat">Culture</a>
    </div>
</div>

<div class="main">
    <?php foreach ($articles as $i => $article): ?>
        <div class="list-card" onclick="location.href='articles/detail.php?id=<?= $article['id'] ?>'">
            <div class="list-num"><?= str_pad($i + 1 + $offset, 2, '0', STR_PAD_LEFT) ?></div>
            <div class="list-body">
                <p class="list-title"><?= htmlspecialchars($article['titre']) ?></p>
                <div class="list-meta">
                    <span class="badge"><?= htmlspecialchars($article['categorie']) ?></span>
                    <span>Par <?= htmlspecialchars($article['auteur']) ?> · <?= $article['date_publication'] ?></span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="page-btn">← Précédent</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <span class="page-info">Page <?= $page ?> sur <?= $total_pages ?></span>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="page-btn">Suivant →</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>