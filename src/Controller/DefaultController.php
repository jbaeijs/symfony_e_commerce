<?php

// Controller/DefaultController.php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController{
    public function index(SessionInterface $session){
        $taillePanier = sizeof($session->get("panier", []));
        return $this->render('Default/index.html.twig', ["taillePanier" => $taillePanier]);
    }

    public function contact(SessionInterface $session){
        $taillePanier = sizeof($session->get("panier", []));
        return $this->render('Default/contact.html.twig', ["taillePanier" => $taillePanier]);
    }
}