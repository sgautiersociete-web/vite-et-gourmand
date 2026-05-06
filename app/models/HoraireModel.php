<?php
// app/models/HoraireModel.php
class HoraireModel
{
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function getAll(): array
    {
        return $this->db->query('SELECT * FROM horaire ORDER BY jour')->fetchAll();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE horaire SET heure_ouverture=:h_open, heure_fermeture=:h_close, ferme=:ferme WHERE horaire_id=:id'
        );
        $stmt->execute(array_merge($data, ['id' => $id]));
    }
}
