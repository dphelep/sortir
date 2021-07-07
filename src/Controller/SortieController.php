<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/accueil', name: 'sortie_liste')]
    public function index(): Response
    {
        return $this->render('sortie/liste.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }
}
