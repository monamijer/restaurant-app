<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Parametre.php';

class HomeController extends Controller {
    public function index() {
        $parametreModel = new Parametre();
        $params = $parametreModel->getAll();

        $this->render('client/home', ['params' => $params]);
    }
}