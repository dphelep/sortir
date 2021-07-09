<?php

namespace App\Controller;

use App\Class\Filtre;
use App\Form\FiltreType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/accueil", name="sortie_liste")
     */
    public function liste(Request $request,
                          SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAll();
        $filtre = new Filtre();
        $filtreForm = $this->createForm(FiltreType::class, $filtre);
        $filtreForm->handleRequest($request);

        return $this->render('sortie/liste.html.twig', [
            'sorties' => $sorties,
            'filtreForm' => $filtreForm->createView(),
        ]);
    }

    public function creer() {

    }

    /**
     * @Route("/detail/{id}", name="sortie_detail")
     */

    public function detail($id, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException("Oops ! La sortie n'existe pas !");
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie
        ]);
    }
}
