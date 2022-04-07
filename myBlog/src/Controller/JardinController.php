<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class JardinController extends AbstractController
{

    #[Route('/jardin/ajouter/{id}', name: 'ajouter_jardin')]
    public function ajouterJardin(EntityManagerInterface $em, Produit $produit, Request $request)
    {
        $user = $em->getRepository(User::class)->find(1);
        $panier = $user->getPanier();
        $em->persist($panier);

        $qte = $request->query->get('quantite', 1);

        $panierProduit = new PanierProduit();
        $panierProduit->panier = $panier;
        $panierProduit->produit = $produit;
        $panierProduit->quantite = $qte;
        $em->persist($panierProduit);

        $em->flush();

        $this->addFlash("success", $qte . " " . $produit->nom . " ajouté.e.s au panier");

        return $this->redirectToRoute('jardin');
    }


    #[Route('/jardin', name: 'jardin')]
    public function jardin(EntityManagerInterface $em)
    {
        /*
        $tomate = new Produit();
        $tomate->nom = "Tomates";
        $tomate->prix = "13";
        $em->persist($tomate);

        $banane = new Produit();
        $banane->nom = "Bananes";
        $banane->prix = "42";
        $em->persist($banane);

        $user = new User();
        $user->nom = "Dupond";
        $user->prenom = "Jean";
        $em->persist($user);

        $panier = new Panier();
        $panier->state = "panier";
        $panier->user = $user;
        $em->persist($panier);

        $panierProduit = new PanierProduit();
        $panierProduit->panier = $panier;
        $panierProduit->produit = $tomate;
        $panierProduit->quantite = 2;
        $em->persist($panierProduit);

        $panierProduit = new PanierProduit();
        $panierProduit->panier = $panier;
        $panierProduit->produit = $banane;
        $panierProduit->quantite = 1;
        $em->persist($panierProduit);

        $em->flush();
        */

        $user = $em->getRepository(User::class)->find(1);

        $produits = $em->getRepository(Produit::class)->findAll();

        return $this->render("jardin.html.twig", [
            'user' => $user,
            'produits' => $produits
        ]);


    }



}
