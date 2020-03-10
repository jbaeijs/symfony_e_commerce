<?php

// Controller/DefaultController.php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\PanierService;
use App\Service\BoutiqueService;

class PanierController extends AbstractController{
    public function index(PanierService $panierService){
        $panier = $panierService->getContenu();
        $total = $panierService->getTotal();
        return $this->render('Panier/index.html.twig', ["panier" => $panier, "total" => $total]);
    }

    public function ajouter(PanierService $panierService, $idProduit, $quantite){
        return $this->redirectToRoute('panier_index');
    }

    public function enlever(PanierService $panierService, $idProduit, $quantite){
        return $this->redirectToRoute('panier_index');
    }

    public function supprimer(PanierService $panierService, $idProduit){
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier_index');
    }

    public function vider(PanierService $panierService){
        $panierService->vider();
        return $this->redirectToRoute('panier_index');
    }
}