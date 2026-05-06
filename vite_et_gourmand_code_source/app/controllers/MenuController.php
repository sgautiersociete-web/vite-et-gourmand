<?php
// app/controllers/MenuController.php
require_once APP_PATH . '/models/MenuModel.php';

class MenuController
{
    private MenuModel $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index(): void
    {
        $themes  = $this->menuModel->getThemes();
        $regimes = $this->menuModel->getRegimes();
        $menus   = $this->menuModel->getAll();
        view('menus/index', compact('menus', 'themes', 'regimes'));
    }

    public function detail(): void
    {
        $id   = (int)get('id');
        $menu = $this->menuModel->getById($id);
        if (!$menu) {
            http_response_code(404);
            view('errors/404');
            return;
        }
        $plats     = $this->menuModel->getPlats($id);
        $images    = $this->menuModel->getImages($id);
        view('menus/detail', compact('menu', 'plats', 'images'));
    }

    /**
     * Filtre AJAX - retourne du JSON
     */
    public function filter(): void
    {
        header('Content-Type: application/json');

        $filters = [
            'prix_max'   => post('prix_max')  ? (float)post('prix_max') : null,
            'prix_min'   => post('prix_min')  ? (float)post('prix_min') : null,
            'theme_id'   => post('theme_id')  ? (int)post('theme_id')   : null,
            'regime_id'  => post('regime_id') ? (int)post('regime_id')  : null,
            'nb_min'     => post('nb_min')    ? (int)post('nb_min')     : null,
        ];

        $menus = $this->menuModel->filter($filters);
        echo json_encode(['success' => true, 'menus' => $menus]);
    }
}
