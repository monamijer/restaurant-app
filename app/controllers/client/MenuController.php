<?php

class MenuController extends Controller {
    public function index() {
        $platModel = new Plat();
        $categorieModel = new Categorie();
        $parametreModel = new Parametre();

        $plats = $platModel->filtrer([]);
        $categories = $categorieModel->allOrdered();
        $params = $parametreModel->getAll();

        $this->render('client/menu', [
            'plats' => $plats,
            'categories' => $categories,
            'params' => $params,
        ]);
    }

    // Endpoint AJAX appelé par les filtres
    public function filtrer() {
        header('Content-Type: application/json');

        $platModel = new Plat();
        $filtres = [
            'categorie_id' => $_GET['categorie_id'] ?? null,
            'vegetarien' => $_GET['vegetarien'] ?? null,
            'sans_gluten' => $_GET['sans_gluten'] ?? null,
            'epice' => $_GET['epice'] ?? null,
        ];

        $plats = $platModel->filtrer($filtres);
        echo json_encode($plats);
    }
}