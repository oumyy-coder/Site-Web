-- Création de la base
CREATE DATABASE projet_news;
USE projet_news;

-- =========================
-- TABLE UTILISATEURS
-- =========================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    login VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editeur') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 

-- =========================
-- TABLE CATEGORIES
-- =========================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) UNIQUE NOT NULL
);

-- =========================
-- TABLE ARTICLES
-- =========================
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    contenu TEXT NOT NULL,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    categorie_id INT,
    auteur_id INT,

    FOREIGN KEY (categorie_id) REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    FOREIGN KEY (auteur_id) REFERENCES utilisateurs(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);