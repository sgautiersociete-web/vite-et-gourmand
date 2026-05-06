<?php
// app/controllers/HomeController.php
require_once APP_PATH . '/models/AvisModel.php';

class HomeController
{
    public function index(): void
    {
        view('home/index', []);
    }
}
