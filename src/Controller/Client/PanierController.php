<?php
namespace App\Controller\Client;

use App\Entity\Produit;
use http\Env\Response;
use Monolog\Registry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;

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


    /**
     * @Route("/panier", name="panier.index)
     */
    public function index()
    {
        return $this->redirectToRoute('panier.show');
    }

    /**
     * @Route("/panier/show", name="panier.show")
     */
    public function show(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $ligne_panier=$doctrine->getRepository(Panier::class)->findBy(['user' => $this->getUser()]);
        $produits=$doctrine->getRepository(Produit::class)->findBy();
        $prixTotal = $doctrine->getRepository(Panier::class)->findPrixTotal($this->getUser()->getId());
        return new Response($this->render('frontOff/showPanierUser.html.twig',['produits'=>$produits, 'lignes_panier'=>$ligne_panier,'prixTotal' =>$prixTotal] ));
    }

    /**
     * @Route("/panier/add", name="panier.add")
     */
    public function add(Request $request, Environment $twig, RegistryInterface $doctrine)
    {
        $produit_select=$doctrine->getRepository(Produit::class)->find($request->get('produit_id'));
        // dump($request->get('produit_id')); dump($produit_select);

        $ligne_panier = $doctrine->getRepository(Panier::class)->findOneBy(['produit' => $produit_select, 'user' => $this->getUser()]);
        // dump($ligne_panier);
        if($ligne_panier)
        {
            $quantite = $ligne_panier->getQuantite();
            $ligne_panier->setQuantite($quantite+1);
        } else {
            $ligne_panier = new Panier();
            $ligne_panier->setUser($this->getUser());
            $ligne_panier->setDateAchat(new \DateTime());
            $ligne_panier->setQuantite(1);
            $ligne_panier->setProduit($produit_select);
        }

        $doctrine->getManager()->persist($ligne_panier);
        $produit_select->setStock($produit_select->getStock()-1);
        $doctrine->getManager()->persist($produit_select);
        $doctrine->getManager()->flush();

        return $this->redirectToRoute('panier.show');

    }


}