<?php
// app/models/AvisModel.php
class AvisModel
{
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getValides(): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.nom, u.prenom
             FROM avis a
             JOIN utilisateur u ON u.utilisateur_id = a.utilisateur_id
             WHERE a.statut = 'valide'
             ORDER BY a.created_at DESC LIMIT 9"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEnAttente(): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.nom, u.prenom, m.titre AS menu_titre
             FROM avis a
             JOIN utilisateur u ON u.utilisateur_id = a.utilisateur_id
             JOIN commande c ON c.commande_id = a.commande_id
             JOIN menu m ON m.menu_id = c.menu_id
             WHERE a.statut = 'en_attente'
             ORDER BY a.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function moderate(int $id, string $statut): void
    {
        $stmt = $this->db->prepare("UPDATE avis SET statut=:s WHERE avis_id=:id");
        $stmt->execute(['s' => $statut, 'id' => $id]);
    }

    public function create(int $commandeId, int $userId, int $note, string $commentaire): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO avis (commande_id, utilisateur_id, note, commentaire) VALUES (:cid, :uid, :note, :comment)"
        );
        $stmt->execute(['cid' => $commandeId, 'uid' => $userId, 'note' => $note, 'comment' => $commentaire]);
    }
}
