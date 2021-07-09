<?php

namespace App\Controller;

use App\Form\AnnulerType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
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
     * @Route("/sortie/detail/{id}", name="sortie_detail")
     */

    public function detail($id, SortieRepository $sortieRepository, ParticipantRepository $participantRepository): Response
    {
        $sortie = $sortieRepository->find($id);
        $participants = $participantRepository->findAll();
        if (!$sortie) {
            throw $this->createNotFoundException("Oops ! La sortie n'existe pas !");
        }

        return $this->render('sortie/detail.html.twig', [
            'sortie' => $sortie,
            'participants' => $participants
        ]);
    }
    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler")
     */
    public function annuler($id,
                            EtatRepository $etatRepository,
                            SortieRepository $sortieRepository,
                            EntityManagerInterface $entityManager,
                            Request $request): Response
    {

        $sortie = $sortieRepository->find($id);
        $annulerForm = $this->createForm(AnnulerType::class, $sortie);
        $annulerForm->handleRequest($request);

        if (!$sortie) {
            throw $this->createNotFoundException("Oops ! La sortie n'existe pas !");
        }
        if ($annulerForm->isSubmitted() && $annulerForm->isValid()){
            $etat = $etatRepository->findOneBy(['libelle'=>'Annulée']);
            $sortie -> setEtat($etat);

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'La sortie a été annulée !');

            return $this->redirectToRoute('sortie_liste', ['id' => $sortie->getId()]);
        }
        return $this->render('sortie/annuler.html.twig', [
            'annulerForm' => $annulerForm->createView(),
            'sortie' => $sortie
        ]);
    }
}
