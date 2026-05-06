<?php
// app/models/MenuModel.php
class MenuModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(bool $onlyActif = true): array
    {
        $where = $onlyActif ? 'WHERE m.actif = 1' : '';
        $stmt = $this->db->prepare(
            "SELECT m.*, t.libelle AS theme, r.libelle AS regime
             FROM menu m
             JOIN theme t ON t.theme_id = m.theme_id
             JOIN regime r ON r.regime_id = m.regime_id
             $where
             ORDER BY m.titre"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT m.*, t.libelle AS theme, r.libelle AS regime
             FROM menu m
             JOIN theme t ON t.theme_id = m.theme_id
             JOIN regime r ON r.regime_id = m.regime_id
             WHERE m.menu_id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getPlats(int $menuId): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, GROUP_CONCAT(a.libelle ORDER BY a.libelle SEPARATOR ', ') AS allergenes
             FROM plat p
             JOIN menu_plat mp ON mp.plat_id = p.plat_id
             LEFT JOIN plat_allergene pa ON pa.plat_id = p.plat_id
             LEFT JOIN allergene a ON a.allergene_id = pa.allergene_id
             WHERE mp.menu_id = :menu_id
             GROUP BY p.plat_id
             ORDER BY FIELD(p.type_plat,'entree','plat','dessert')"
        );
        $stmt->execute(['menu_id' => $menuId]);
        return $stmt->fetchAll();
    }

    public function getImages(int $menuId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM image_menu WHERE menu_id = :id ORDER BY ordre'
        );
        $stmt->execute(['id' => $menuId]);
        return $stmt->fetchAll();
    }

    public function filter(array $f): array
    {
        $where  = ['m.actif = 1'];
        $params = [];

        if ($f['prix_min'] !== null) {
            $where[]         = 'm.prix >= :prix_min';
            $params['prix_min'] = $f['prix_min'];
        }
        if ($f['prix_max'] !== null) {
            $where[]         = 'm.prix <= :prix_max';
            $params['prix_max'] = $f['prix_max'];
        }
        if ($f['theme_id'] !== null) {
            $where[]          = 'm.theme_id = :theme_id';
            $params['theme_id'] = $f['theme_id'];
        }
        if ($f['regime_id'] !== null) {
            $where[]           = 'm.regime_id = :regime_id';
            $params['regime_id'] = $f['regime_id'];
        }
        if ($f['nb_min'] !== null) {
            $where[]        = 'm.nb_personnes_min <= :nb_min';
            $params['nb_min'] = $f['nb_min'];
        }

        $sql = 'SELECT m.*, t.libelle AS theme, r.libelle AS regime
                FROM menu m
                JOIN theme t ON t.theme_id = m.theme_id
                JOIN regime r ON r.regime_id = m.regime_id
                WHERE ' . implode(' AND ', $where) . ' ORDER BY m.prix';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getThemes(): array
    {
        return $this->db->query('SELECT * FROM theme ORDER BY libelle')->fetchAll();
    }

    public function getRegimes(): array
    {
        return $this->db->query('SELECT * FROM regime ORDER BY libelle')->fetchAll();
    }

    public function save(array $data): int
    {
        if (!empty($data['menu_id'])) {
            $stmt = $this->db->prepare(
                'UPDATE menu SET theme_id=:theme_id, regime_id=:regime_id, titre=:titre,
                 description=:description, nb_personnes_min=:nb_personnes_min, prix=:prix,
                 conditions=:conditions, quantite_restante=:quantite_restante, actif=:actif
                 WHERE menu_id=:menu_id'
            );
            $stmt->execute($data);
            return $data['menu_id'];
        }
        unset($data['menu_id']);
        $stmt = $this->db->prepare(
            'INSERT INTO menu (theme_id, regime_id, titre, description, nb_personnes_min, prix, conditions, quantite_restante)
             VALUES (:theme_id, :regime_id, :titre, :description, :nb_personnes_min, :prix, :conditions, :quantite_restante)'
        );
        $stmt->execute($data);
        return (int)$this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE menu SET actif=0 WHERE menu_id=:id');
        $stmt->execute(['id' => $id]);
    }
}
