<?php

namespace App\Controller;

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
    public function liste(SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAll();

        return $this->render('sortie/liste.html.twig', [
            'sorties' => $sorties,
        ]);
    }

    public function filtrer(Request $request,
                            EntityManagerInterface $entityManager,): Response {


    }

    public function creer() {

    }
}
