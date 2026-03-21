<?php
// menu.php — inclus dans le <body> de chaque page
// Affiche la navbar en fonction du rôle de l'utilisateur connecté

// Rôle courant (null si visiteur non connecté)
$role = $_SESSION['role'] ?? null;

// Page active pour surligner le lien courant
$pageCourante = basename($_SERVER['PHP_SELF']);
?>

<nav>
    <a href="accueil.php" class="nav-brand">📰 Projet News</a>

    <ul>
        <!-- ── Liens accessibles à tous (visiteurs inclus) ── -->
        <li>
            <a href="accueil.php"
               class="<?= $pageCourante === 'accueil.php' ? 'actif' : '' ?>">
                Accueil
            </a>
        </li>

        <!-- ── Liens réservés aux éditeurs et admins ── -->
        <?php if ($role === 'editeur' || $role === 'admin'): ?>
            <li>
                <a href="articles/ajouter.php"
                   class="<?= $pageCourante === 'ajouter.php' ? 'actif' : '' ?>">
                    Nouvel article
                </a>
            </li>
            <li>
                <a href="categories/liste.php"
                   class="<?= $pageCourante === 'liste.php' ? 'actif' : '' ?>">
                    Catégories
                </a>
            </li>
        <?php endif; ?>

        <!-- ── Liens réservés aux admins uniquement ── -->
        <?php if ($role === 'admin'): ?>
            <li>
                <a href="utilisateurs/liste.php"
                   class="<?= $pageCourante === 'liste.php' ? 'actif' : '' ?>">
                    Utilisateurs
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <div class="nav-user">
        <?php if ($role): ?>
            Bonjour, <span><?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></span>
            &nbsp;·&nbsp;
            <a href="deconnexion.php" style="color:#e24b4a; font-size:0.85rem;">Déconnexion</a>
        <?php else: ?>
            <a href="connexion.php" style="color:#ccc; font-size:0.85rem;">Connexion</a>
        <?php endif; ?>
    </div>
</nav>
