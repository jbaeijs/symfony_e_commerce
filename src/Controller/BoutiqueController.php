<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BoutiqueController extends AbstractController {
    public function index(BoutiqueService $boutique, SessionInterface $session) {
        $taillePanier = sizeof($session->get("panier", []));
        $categories = $boutique->findAllCategories();
        return $this->render('Boutique/boutique.html.twig', ["categories" => $categories, "taillePanier" => $taillePanier]);
    }

    public function rayon(BoutiqueService $boutique, $idCategorie, SessionInterface $session){
        $taillePanier = sizeof($session->get("panier", []));
        $produits = $boutique->findProduitsByCategorie($idCategorie);
        return $this->render('Boutique/rayon.html.twig', ["produits" => $produits, "taillePanier" => $taillePanier]);
    }
}