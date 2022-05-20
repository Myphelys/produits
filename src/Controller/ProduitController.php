<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route('/produit', name: 'produit_list')]
    public function list(ProduitRepository $produitRepository): Response
    {
        // récupère les produits publiés, du plus récent au plus ancien
        $produits = $produitRepository->findAll();
        return $this->render('produit/list.html.twig', [
            "produits" => $produits
        ]);
    }

    #[Route('/produit/detail/{id}', name: 'produit_detail')]
    public function detail(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository,int $id): Response
    {
        $produit = $produitRepository->find($id);
        $produitForm = $this->createForm(ProduitType::class, $produit);

        $produitForm->handleRequest($request);

        if ($produitForm->isSubmitted() && $produitForm->isValid()) {

            //hydrate toutes les propriétés
            $produit->setLibelle($produitForm->get('libelle')->getData());
            $produit->setReference($produitForm->get('reference')->getData());
            $produit->setPrix($produitForm->get('prix')->getData());
            $produit->setStock($produitForm->get('stock')->getData());
            $produit->setDateAjout(new \DateTime());

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Votre produit a été mis à jour avec succès ;)');
            return $this->redirectToRoute('produit_list');

        }

        return $this->render('produit/detail.html.twig', [
            "produit" => $produit,
            'produitForm' => $produitForm->createView()
        ]);
    }

    #[Route('/produit/ajout', name: 'produit_ajout')]
    public function produit_ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $produit->setDateAjout(new \DateTime());
        $produitForm = $this->createForm(ProduitType::class, $produit);

        $produitForm->handleRequest($request);

        if ($produitForm->isSubmitted() && $produitForm->isValid()) {

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Votre produit a été ajouté avec succès ;)');
            return $this->redirectToRoute('produit_list',
                ['id' => $produit->getId()]);
        }

        return $this->render('produit/ajout.html.twig', [
            'produitForm' => $produitForm->createView()
        ]);
    }

    #[Route('produit/supprimer/{id}', name: 'produit_supprimer')]
    public function supprimer(ProduitRepository $produitRepository, EntityManagerInterface $entityManager, int $id)
    {
        $produit = $produitRepository->find($id);
        $entityManager->remove($produit);
        $entityManager->flush();

        if (!$produitRepository->find($id)) {
            $this->addFlash('success', 'Votre produit a été supprimé avec succès ;)');
        }
        return $this->redirectToRoute('produit_list',
            ['id' => $produit->getId()]);
    }
}
