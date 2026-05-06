-- ============================================================
-- VITE & GOURMAND - Données de test (fixtures)
-- ============================================================
USE vite_gourmand;

-- Rôles
INSERT INTO role (libelle) VALUES ('administrateur'), ('employe'), ('utilisateur');

-- Utilisateurs
-- admin@viteetgourmand.fr / Admin@2024! (bcrypt cost 12)
INSERT INTO utilisateur (role_id, email, password_hash, nom, prenom, gsm, adresse, ville, code_postal) VALUES
(1, 'admin@viteetgourmand.fr', '$2y$12$exampleHashForAdminPlaceholder000000000000000000000000000', 'Dupont', 'José', '0600000001', '1 rue des Traiteurs', 'Bordeaux', '33000'),
(2, 'employe1@viteetgourmand.fr', '$2y$12$exampleHashForEmployeePlaceholder00000000000000000000000', 'Martin', 'Julie', '0600000002', '2 allée des Saveurs', 'Bordeaux', '33000'),
(3, 'client@test.fr', '$2y$12$exampleHashForClientPlaceholder000000000000000000000000000', 'Bernard', 'Marc', '0611223344', '10 cours du Médoc', 'Bordeaux', '33000');

-- Régimes
INSERT INTO regime (libelle) VALUES ('classique'), ('végétarien'), ('vegan'), ('sans gluten'), ('halal');

-- Thèmes
INSERT INTO theme (libelle) VALUES ('Noël'), ('Pâques'), ('classique'), ('événement'), ('anniversaire');

-- Allergènes (14 allergènes réglementaires EU)
INSERT INTO allergene (libelle) VALUES
('Gluten'), ('Crustacés'), ('Œufs'), ('Poissons'), ('Arachides'),
('Soja'), ('Lait / Lactose'), ('Fruits à coque'), ('Céleri'),
('Moutarde'), ('Graines de sésame'), ('Sulfites'), ('Lupin'), ('Mollusques');

-- Menus
INSERT INTO menu (theme_id, regime_id, titre, description, nb_personnes_min, prix, conditions, quantite_restante) VALUES
(1, 1, 'Menu de Noël Traditionnel', 'Un repas festif pour célébrer Noël avec des produits du terroir bordelais. Foie gras, dinde aux marrons et bûche de Noël maison.', 4, 89.00, 'Commander au moins 7 jours avant la prestation. Conservation au réfrigérateur 24h max.', 10),
(3, 1, 'Menu Classique Gastronomique', 'Notre menu signature pour toutes les occasions. Entrée raffinée, plat de saison et dessert maison.', 2, 45.00, 'Commander 3 jours avant la prestation.', 20),
(4, 2, 'Menu Végétarien Festif', 'Un menu 100% végétarien élaboré par Julie pour ravir vos convives. Fraîcheur et gourmandise garanties.', 2, 38.00, 'Commander 3 jours avant la prestation. Produits frais de saison.', 15),
(2, 1, 'Menu Pâques Ensoleillé', 'Célébrez Pâques avec notre menu printanier : agneau de lait, légumes de saison et dessert chocolaté.', 4, 72.00, 'Commander 5 jours avant. Disponible uniquement en mars-avril.', 8);

-- Images menus
INSERT INTO image_menu (menu_id, chemin, alt_text, ordre) VALUES
(1, 'images/menus/noel1.jpg', 'Foie gras de canard sur toast', 1),
(1, 'images/menus/noel2.jpg', 'Dinde rôtie aux marrons', 2),
(2, 'images/menus/classique1.jpg', 'Entrée feuilletée au saumon', 1),
(3, 'images/menus/vegetarien1.jpg', 'Velouté de légumes colorés', 1),
(4, 'images/menus/paques1.jpg', 'Gigot d agneau de lait', 1);

-- Plats
INSERT INTO plat (nom, description, type_plat) VALUES
('Foie gras de canard mi-cuit', 'Foie gras maison sur toast grillé, chutney de figues', 'entree'),
('Velouté de potimarron', 'Velouté onctueux au potimarron, crème fraîche et noisettes', 'entree'),
('Salade de chèvre chaud', 'Salade fraîche, toasts de chèvre, miel et noix', 'entree'),
('Dinde farcie aux marrons', 'Dinde de Bresse rôtie, farce aux marrons et herbes', 'plat'),
('Gigot d agneau de lait', 'Gigot d agneau de lait rôti, flageolets verts et jus de cuisson', 'plat'),
('Risotto aux champignons des bois', 'Risotto crémeux, mélange de champignons sauvages, parmesan', 'plat'),
('Bûche de Noël chocolat', 'Bûche maison chocolat-praliné, décor festif', 'dessert'),
('Mousse au chocolat noir 70%', 'Mousse légère au chocolat noir, éclats de caramel', 'dessert'),
('Tarte aux fraises maison', 'Tarte sablée, crème pâtissière, fraises fraîches', 'dessert');

-- Allergènes des plats
INSERT INTO plat_allergene (plat_id, allergene_id) VALUES
(1, 1),(1, 12), -- Foie gras : gluten, sulfites
(2, 7),         -- Velouté : lait
(3, 3),(3, 8),  -- Salade chèvre : œufs, fruits à coque
(4, 1),(4, 8),  -- Dinde : gluten, fruits à coque
(6, 7),(6, 3),  -- Risotto : lait, œufs
(7, 1),(7, 3),(7, 7),(7, 8), -- Bûche : gluten, œufs, lait, fruits à coque
(8, 3),(8, 7),  -- Mousse : œufs, lait
(9, 1),(9, 3),(9, 7); -- Tarte : gluten, œufs, lait

-- Menus <-> Plats
INSERT INTO menu_plat (menu_id, plat_id) VALUES
(1,1),(1,4),(1,7),  -- Noël : foie gras, dinde, bûche
(2,2),(2,6),(2,8),  -- Classique : velouté, risotto, mousse
(3,2),(3,6),(3,9),  -- Végétarien : velouté, risotto, tarte
(4,3),(4,5),(4,9);  -- Pâques : salade chèvre, agneau, tarte

-- Horaires
INSERT INTO horaire (jour, heure_ouverture, heure_fermeture, ferme) VALUES
(1, '09:00', '18:00', 0), -- Lundi
(2, '09:00', '18:00', 0), -- Mardi
(3, '09:00', '18:00', 0), -- Mercredi
(4, '09:00', '18:00', 0), -- Jeudi
(5, '09:00', '19:00', 0), -- Vendredi
(6, '10:00', '17:00', 0), -- Samedi
(7, NULL,    NULL,    1); -- Dimanche (fermé)

-- Commande de test
INSERT INTO commande (utilisateur_id, menu_id, numero_commande, nom_client, prenom_client, email_client, gsm_client, adresse_livraison, ville_livraison, date_prestation, heure_livraison, nb_personnes, prix_menu, prix_livraison, reduction, prix_total, statut) VALUES
(3, 1, 'VG-20240506-001', 'Bernard', 'Marc', 'client@test.fr', '0611223344', '10 cours du Médoc', 'Bordeaux', '2024-12-24', '12:00', 6, 89.00, 0.00, 8.90, 80.10, 'accepte');

INSERT INTO commande_historique (commande_id, statut, commentaire) VALUES
(1, 'en_attente', 'Commande reçue'),
(1, 'accepte', 'Commande validée par Julie');

-- Avis de test
INSERT INTO avis (commande_id, utilisateur_id, note, commentaire, statut) VALUES
(1, 3, 5, 'Excellent repas de Noël ! Tout était parfait, le foie gras était délicieux.', 'valide');
