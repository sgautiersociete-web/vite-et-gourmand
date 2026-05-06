-- ============================================================
-- VITE & GOURMAND - Schéma base de données MySQL
-- ============================================================
-- Fichier : database/schema.sql
-- SGBD    : MySQL 8.0+
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP DATABASE IF EXISTS vite_gourmand;
CREATE DATABASE vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vite_gourmand;

-- ============================================================
-- TABLE : role
-- ============================================================
CREATE TABLE role (
    role_id   INT          NOT NULL AUTO_INCREMENT,
    libelle   VARCHAR(50)  NOT NULL,
    PRIMARY KEY (role_id),
    UNIQUE KEY uq_role_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : utilisateur
-- ============================================================
CREATE TABLE utilisateur (
    utilisateur_id  INT           NOT NULL AUTO_INCREMENT,
    role_id         INT           NOT NULL DEFAULT 3,   -- 3 = utilisateur
    email           VARCHAR(100)  NOT NULL,
    password_hash   VARCHAR(255)  NOT NULL,
    nom             VARCHAR(50)   NOT NULL,
    prenom          VARCHAR(50)   NOT NULL,
    gsm             VARCHAR(20)   NOT NULL,
    adresse         VARCHAR(150)  NOT NULL,
    ville           VARCHAR(80)   NOT NULL,
    code_postal     VARCHAR(10)   NOT NULL,
    pays            VARCHAR(50)   NOT NULL DEFAULT 'France',
    actif           TINYINT(1)    NOT NULL DEFAULT 1,
    reset_token     VARCHAR(255)  NULL,
    reset_token_exp DATETIME      NULL,
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (utilisateur_id),
    UNIQUE KEY uq_email (email),
    CONSTRAINT fk_utilisateur_role FOREIGN KEY (role_id) REFERENCES role (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : regime
-- ============================================================
CREATE TABLE regime (
    regime_id  INT          NOT NULL AUTO_INCREMENT,
    libelle    VARCHAR(50)  NOT NULL,   -- classique, végétarien, vegan, sans gluten, halal
    PRIMARY KEY (regime_id),
    UNIQUE KEY uq_regime_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : theme
-- ============================================================
CREATE TABLE theme (
    theme_id  INT          NOT NULL AUTO_INCREMENT,
    libelle   VARCHAR(50)  NOT NULL,   -- Noël, Pâques, classique, événement
    PRIMARY KEY (theme_id),
    UNIQUE KEY uq_theme_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : allergene
-- ============================================================
CREATE TABLE allergene (
    allergene_id  INT          NOT NULL AUTO_INCREMENT,
    libelle       VARCHAR(80)  NOT NULL,   -- gluten, lactose, œufs, arachides, etc.
    PRIMARY KEY (allergene_id),
    UNIQUE KEY uq_allergene_libelle (libelle)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : menu
-- ============================================================
CREATE TABLE menu (
    menu_id              INT           NOT NULL AUTO_INCREMENT,
    theme_id             INT           NOT NULL,
    regime_id            INT           NOT NULL,
    titre                VARCHAR(100)  NOT NULL,
    description          TEXT          NOT NULL,
    nb_personnes_min     INT           NOT NULL DEFAULT 2,
    prix                 DECIMAL(8,2)  NOT NULL,
    conditions           TEXT          NULL,      -- délai commande, stockage, etc.
    quantite_restante    INT           NOT NULL DEFAULT 0,
    actif                TINYINT(1)    NOT NULL DEFAULT 1,
    created_at           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (menu_id),
    CONSTRAINT fk_menu_theme  FOREIGN KEY (theme_id)  REFERENCES theme  (theme_id),
    CONSTRAINT fk_menu_regime FOREIGN KEY (regime_id) REFERENCES regime (regime_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : image_menu  (galerie d'images par menu)
-- ============================================================
CREATE TABLE image_menu (
    image_id   INT          NOT NULL AUTO_INCREMENT,
    menu_id    INT          NOT NULL,
    chemin     VARCHAR(255) NOT NULL,
    alt_text   VARCHAR(150) NULL,
    ordre      INT          NOT NULL DEFAULT 0,
    PRIMARY KEY (image_id),
    CONSTRAINT fk_image_menu FOREIGN KEY (menu_id) REFERENCES menu (menu_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : plat
-- ============================================================
CREATE TABLE plat (
    plat_id     INT           NOT NULL AUTO_INCREMENT,
    nom         VARCHAR(100)  NOT NULL,
    description TEXT          NULL,
    type_plat   ENUM('entree','plat','dessert') NOT NULL,
    photo       VARCHAR(255)  NULL,
    PRIMARY KEY (plat_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : plat_allergene  (allergènes d'un plat)
-- ============================================================
CREATE TABLE plat_allergene (
    plat_id      INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    CONSTRAINT fk_pa_plat      FOREIGN KEY (plat_id)      REFERENCES plat      (plat_id) ON DELETE CASCADE,
    CONSTRAINT fk_pa_allergene FOREIGN KEY (allergene_id) REFERENCES allergene  (allergene_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : menu_plat  (plats proposés dans un menu - N-N)
-- ============================================================
CREATE TABLE menu_plat (
    menu_id  INT NOT NULL,
    plat_id  INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    CONSTRAINT fk_mp_menu FOREIGN KEY (menu_id) REFERENCES menu (menu_id) ON DELETE CASCADE,
    CONSTRAINT fk_mp_plat FOREIGN KEY (plat_id) REFERENCES plat (plat_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : horaire
-- ============================================================
CREATE TABLE horaire (
    horaire_id       INT          NOT NULL AUTO_INCREMENT,
    jour             TINYINT      NOT NULL, -- 1=lundi ... 7=dimanche
    heure_ouverture  VARCHAR(5)   NULL,     -- ex: 09:00
    heure_fermeture  VARCHAR(5)   NULL,
    ferme            TINYINT(1)   NOT NULL DEFAULT 0,
    PRIMARY KEY (horaire_id),
    UNIQUE KEY uq_horaire_jour (jour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : commande
-- ============================================================
CREATE TABLE commande (
    commande_id       INT           NOT NULL AUTO_INCREMENT,
    utilisateur_id    INT           NOT NULL,
    menu_id           INT           NOT NULL,
    numero_commande   VARCHAR(20)   NOT NULL,        -- ex: VG-20240506-001
    nom_client        VARCHAR(50)   NOT NULL,
    prenom_client     VARCHAR(50)   NOT NULL,
    email_client      VARCHAR(100)  NOT NULL,
    gsm_client        VARCHAR(20)   NOT NULL,
    adresse_livraison VARCHAR(150)  NOT NULL,
    ville_livraison   VARCHAR(80)   NOT NULL,
    date_prestation   DATE          NOT NULL,
    heure_livraison   TIME          NOT NULL,
    nb_personnes      INT           NOT NULL,
    prix_menu         DECIMAL(8,2)  NOT NULL,
    prix_livraison    DECIMAL(8,2)  NOT NULL DEFAULT 0.00,
    reduction         DECIMAL(8,2)  NOT NULL DEFAULT 0.00,
    prix_total        DECIMAL(8,2)  NOT NULL,
    statut            ENUM(
                        'en_attente',
                        'accepte',
                        'en_preparation',
                        'en_livraison',
                        'livre',
                        'attente_materiel',
                        'terminee',
                        'annulee'
                      ) NOT NULL DEFAULT 'en_attente',
    motif_annulation  TEXT          NULL,
    mode_contact      VARCHAR(50)   NULL,             -- gsm / mail
    materiel_prete    TINYINT(1)    NOT NULL DEFAULT 0,
    created_at        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (commande_id),
    UNIQUE KEY uq_numero_commande (numero_commande),
    CONSTRAINT fk_cmd_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id),
    CONSTRAINT fk_cmd_menu        FOREIGN KEY (menu_id)        REFERENCES menu         (menu_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : commande_historique  (suivi des états)
-- ============================================================
CREATE TABLE commande_historique (
    historique_id  INT          NOT NULL AUTO_INCREMENT,
    commande_id    INT          NOT NULL,
    statut         VARCHAR(50)  NOT NULL,
    commentaire    TEXT         NULL,
    created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (historique_id),
    CONSTRAINT fk_histo_commande FOREIGN KEY (commande_id) REFERENCES commande (commande_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : avis
-- ============================================================
CREATE TABLE avis (
    avis_id         INT          NOT NULL AUTO_INCREMENT,
    commande_id     INT          NOT NULL,
    utilisateur_id  INT          NOT NULL,
    note            TINYINT      NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire     TEXT         NOT NULL,
    statut          ENUM('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
    created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (avis_id),
    UNIQUE KEY uq_avis_commande (commande_id),
    CONSTRAINT fk_avis_commande    FOREIGN KEY (commande_id)    REFERENCES commande    (commande_id),
    CONSTRAINT fk_avis_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
