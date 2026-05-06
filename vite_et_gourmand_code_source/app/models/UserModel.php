<?php
// app/models/UserModel.php
class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, r.libelle AS role_libelle
             FROM utilisateur u
             JOIN role r ON r.role_id = u.role_id
             WHERE u.email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, r.libelle AS role_libelle
             FROM utilisateur u
             JOIN role r ON r.role_id = u.role_id
             WHERE u.utilisateur_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO utilisateur (role_id, email, password_hash, nom, prenom, gsm, adresse, ville, code_postal)
             VALUES (3, :email, :password_hash, :nom, :prenom, :gsm, :adresse, :ville, :code_postal)'
        );
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE utilisateur SET nom=:nom, prenom=:prenom, gsm=:gsm, adresse=:adresse, ville=:ville, code_postal=:code_postal
             WHERE utilisateur_id=:id'
        );
        $data['id'] = $id;
        $stmt->execute($data);
    }

    public function setResetToken(int $id, string $token, string $expires): void
    {
        $stmt = $this->db->prepare(
            'UPDATE utilisateur SET reset_token=:token, reset_token_exp=:exp WHERE utilisateur_id=:id'
        );
        $stmt->execute(['token' => hash('sha256', $token), 'exp' => $expires, 'id' => $id]);
    }

    public function findByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM utilisateur
             WHERE reset_token=:token AND reset_token_exp > NOW() LIMIT 1'
        );
        $stmt->execute(['token' => hash('sha256', $token)]);
        return $stmt->fetch() ?: null;
    }

    public function updatePassword(int $id, string $hash): void
    {
        $stmt = $this->db->prepare(
            'UPDATE utilisateur SET password_hash=:hash, reset_token=NULL, reset_token_exp=NULL WHERE utilisateur_id=:id'
        );
        $stmt->execute(['hash' => $hash, 'id' => $id]);
    }

    public function createEmploye(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO utilisateur (role_id, email, password_hash, nom, prenom, gsm, adresse, ville, code_postal)
             VALUES (2, :email, :password_hash, :nom, :prenom, :gsm, :adresse, :ville, :code_postal)'
        );
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function toggleActif(int $id, bool $actif): void
    {
        $stmt = $this->db->prepare('UPDATE utilisateur SET actif=:actif WHERE utilisateur_id=:id');
        $stmt->execute(['actif' => (int)$actif, 'id' => $id]);
    }

    public function getAllEmployes(): array
    {
        $stmt = $this->db->prepare(
            "SELECT u.*, r.libelle AS role_libelle
             FROM utilisateur u JOIN role r ON r.role_id = u.role_id
             WHERE r.libelle = 'employe' ORDER BY u.nom"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
