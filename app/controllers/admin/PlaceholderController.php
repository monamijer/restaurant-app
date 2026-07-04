<?php

class PlaceholderController extends Controller {
    public function commandes() {
        $this->requireRole('ADMIN');
        $this->render('admin/en-construction', ['page' => 'commandes', 'titrePage' => 'Commandes', 'nom' => 'Gestion des commandes']);
    }

    public function stocks() {
        $this->requireRole('ADMIN');
        $this->render('admin/en-construction', ['page' => 'stocks', 'titrePage' => 'Stocks', 'nom' => 'Gestion des stocks']);
    }

    public function clients() {
        $this->requireRole('ADMIN');
        $this->render('admin/en-construction', ['page' => 'clients', 'titrePage' => 'Clients', 'nom' => 'Gestion des clients (CRM)']);
    }

    public function avis() {
        $this->requireRole('ADMIN');
        $this->render('admin/en-construction', ['page' => 'avis', 'titrePage' => 'Avis', 'nom' => 'Modération des avis']);
    }

    public function employes() {
        $this->requireRole('ADMIN');
        $this->render('admin/en-construction', ['page' => 'employes', 'titrePage' => 'Employés', 'nom' => 'Gestion des employés']);
    }
}