<?php
// app/models/CommandeModel.php
class CommandeModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO commande
             (utilisateur_id, menu_id, numero_commande, nom_client, prenom_client,
              email_client, gsm_client, adresse_livraison, ville_livraison,
              date_prestation, heure_livraison, nb_personnes,
              prix_menu, prix_livraison, reduction, prix_total)
             VALUES
             (:utilisateur_id, :menu_id, :numero_commande, :nom_client, :prenom_client,
              :email_client, :gsm_client, :adresse_livraison, :ville_livraison,
              :date_prestation, :heure_livraison, :nb_personnes,
              :prix_menu, :prix_livraison, :reduction, :prix_total)'
        );
        $stmt->execute($data);
        $id = (int)$this->db->lastInsertId();

        $this->addHistorique($id, 'en_attente', 'Commande reçue');
        $this->decrementStock($data['menu_id']);

        return $id;
    }

    private function decrementStock(int $menuId): void
    {
        $this->db->prepare(
            'UPDATE menu SET quantite_restante = GREATEST(0, quantite_restante - 1) WHERE menu_id = :id'
        )->execute(['id' => $menuId]);
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, m.titre AS menu_titre
             FROM commande c
             JOIN menu m ON m.menu_id = c.menu_id
             WHERE c.utilisateur_id = :id
             ORDER BY c.created_at DESC'
        );
        $stmt->execute(['id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT c.*, m.titre AS menu_titre
             FROM commande c
             JOIN menu m ON m.menu_id = c.menu_id
             WHERE c.commande_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getAll(array $filters = []): array
    {
        $where  = ['1=1'];
        $params = [];

        if (!empty($filters['statut'])) {
            $where[]           = 'c.statut = :statut';
            $params['statut'] = $filters['statut'];
        }
        if (!empty($filters['email'])) {
            $where[]          = 'c.email_client LIKE :email';
            $params['email'] = '%' . $filters['email'] . '%';
        }

        $sql = 'SELECT c.*, m.titre AS menu_titre, u.nom, u.prenom
                FROM commande c
                JOIN menu m ON m.menu_id = c.menu_id
                JOIN utilisateur u ON u.utilisateur_id = c.utilisateur_id
                WHERE ' . implode(' AND ', $where) . '
                ORDER BY c.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatut(int $id, string $statut, ?string $motif = null, ?string $modeContact = null): void
    {
        $stmt = $this->db->prepare(
            'UPDATE commande SET statut=:statut, motif_annulation=:motif, mode_contact=:mode
             WHERE commande_id=:id'
        );
        $stmt->execute(['statut' => $statut, 'motif' => $motif, 'mode' => $modeContact, 'id' => $id]);
        $this->addHistorique($id, $statut, $motif);
    }

    public function addHistorique(int $commandeId, string $statut, ?string $commentaire = null): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO commande_historique (commande_id, statut, commentaire) VALUES (:id, :statut, :comment)'
        );
        $stmt->execute(['id' => $commandeId, 'statut' => $statut, 'comment' => $commentaire]);
    }

    public function getHistorique(int $commandeId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM commande_historique WHERE commande_id = :id ORDER BY created_at ASC'
        );
        $stmt->execute(['id' => $commandeId]);
        return $stmt->fetchAll();
    }

    public function cancel(int $id, int $userId): bool
    {
        // L'utilisateur ne peut annuler que si statut = en_attente
        $commande = $this->getById($id);
        if (!$commande || $commande['utilisateur_id'] != $userId || $commande['statut'] !== 'en_attente') {
            return false;
        }
        $this->updateStatut($id, 'annulee', 'Annulation par le client');
        return true;
    }

    public function countByMenu(): array
    {
        $stmt = $this->db->query(
            "SELECT m.titre, COUNT(c.commande_id) AS nb_commandes
             FROM commande c
             JOIN menu m ON m.menu_id = c.menu_id
             WHERE c.statut != 'annulee'
             GROUP BY c.menu_id
             ORDER BY nb_commandes DESC"
        );
        return $stmt->fetchAll();
    }

    public function caByMenu(array $filters = []): array
    {
        $where  = ["c.statut NOT IN ('annulee','en_attente')"];
        $params = [];

        if (!empty($filters['menu_id'])) {
            $where[]           = 'c.menu_id = :menu_id';
            $params['menu_id'] = $filters['menu_id'];
        }
        if (!empty($filters['date_from'])) {
            $where[]              = 'c.date_prestation >= :date_from';
            $params['date_from'] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[]            = 'c.date_prestation <= :date_to';
            $params['date_to'] = $filters['date_to'];
        }

        $sql = 'SELECT m.titre, SUM(c.prix_total) AS ca_total, COUNT(c.commande_id) AS nb
                FROM commande c
                JOIN menu m ON m.menu_id = c.menu_id
                WHERE ' . implode(' AND ', $where) . '
                GROUP BY c.menu_id ORDER BY ca_total DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
