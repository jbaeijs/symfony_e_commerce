<?php
// src/Service/PanierService.php
namespace App\Service;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\BoutiqueService;

// Service pour manipuler le panier et le stocker en session
class PanierService {
    
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session du panier
    private $session; // Le service Session
    private $boutique; // Le service Boutique
    private $panier; // Tableau associatif idProduit => quantite
    // donc $this->panier[$i] = quantite du produit dont l'id = $i
    // constructeur du service

    public function __construct(SessionInterface $session, BoutiqueService $boutique) {
        // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->session = $session;

        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get(self::PANIER_SESSION, []);
    }

    // getContenu renvoie le contenu du panier
    // tableau d'éléments [ "produit" => un produit, "quantite" => quantite ]
    public function getContenu() {
        $articles = array();
        foreach ($this->panier as $article) {
            $produit = $this->boutique->findProduitById($article["idProduit"]);
            $temp_array = [
                "produit" => $produit,
                "quantite" => $article["quantite"],
                "prix_totale_unique" => ($produit["prix"] * $article["quantite"]),
            ];
            array_push($articles, $temp_array, );
        }
        return $articles;
    }

    // getTotal renvoie le montant total du panier
    public function getTotal() {
        $sommeTotale = 0.0;
        foreach ($this->panier as $article) {
            $currentProduct = $this->boutique->findProduitById($article["idProduit"]);
            $sommeTotale += ($currentProduct["prix"] * $article["quantite"]);
        }
        return $sommeTotale;
    }

    // getNbProduits renvoie le nombre de produits dans le panier
    public function getNbProduits() {
        $sommeTotale = 0;
        foreach ($this->panier as $article) {
            $sommeTotale += $article["quantite"];
        }
        return $sommeTotale;
    }

    // ajouterProduit ajoute au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite = 1) {
        $newProduct = TRUE;
        for ($i=0; $i < sizeof($this->panier); $i++) {
            if ($this->panier[$i]["idProduit"] == $idProduit){
                $this->panier[$i]["quantite"] += $quantite;
                $newProduct = FALSE;
            }
        }
        if ($newProduct){
            $temp_array = [
                    "idProduit" => $idProduit,
                    "quantite" => $quantite
            ];
            array_push($this->panier, $temp_array);
        }
        $this->updateSession();
    }
   
    // enleverProduit enlève du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1) {
        for ($i=0; $i < sizeof($this->panier); $i++) {
            if ($this->panier[$i]["idProduit"] == $idProduit)
                if ($this->panier[$i]["quantite"] == 0){
                    $this->supprimerProduit($idProduit);
                } else {
                    $this->panier[$i]["quantite"] -= $quantite;
                }
            }
        $this->updateSession();
    }
  
    // supprimerProduit supprime complètement le produit $idProduit du panier
    public function supprimerProduit(int $idProduit) {
        for ($i=0; $i < sizeof($this->panier); $i++) {
            if ($this->panier[$i]["idProduit"] == $idProduit)
                unset($this->panier[$i]);
        }
        $this->updateSession();
    }
   
    // vider vide complètement le panier
    public function vider() {
        $this->panier = [];
        $this->updateSession();
    }

    public function updateSession(){
        $this->session->set(self::PANIER_SESSION, $this->panier);
    }
}