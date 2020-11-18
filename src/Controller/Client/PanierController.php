<?php
namespace App\Controller\Client;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierController extends AbstractController
{
    /**
     * @Route("/client", name="client_panier_index", methods={"GET"})
     * @Route("/client/panierProduits/show", name="client_panier_showProduits", methods={"GET"})
     */
    public function showPanierProduits(Request $request)
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findBy([], ['typeProduit' => 'ASC', 'stock' => 'ASC']);
        return $this->render('client/boutique/panier_produit.html.twig', ['produits' => $produits, 'monPanier' => NULL]);
    }

}