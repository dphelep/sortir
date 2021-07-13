<?php

namespace App\Controller;


use App\Entity\Participant;
use App\Entity\Sortie;

use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Form\AnnulerType;
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

        if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
            $sorties = $sortieRepository->findSorties($filtre);
        }

        return $this->render('sortie/liste.html.twig', [
            'sorties' => $sorties,
            'filtreForm' => $filtreForm->createView(),
        ]);
    }

    /**
     * @Route("/sortie/creer", name="sortie_creer")
     */
    public function creer(Request $request,
                          EntityManagerInterface $entityManager,
                          EtatRepository $etatRepository,
                          CampusRepository $campusRepository,
                          ParticipantRepository $participantRepository): Response
    {

        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $etat = $etatRepository->findOneBy(["libelle" => "Créée"]);

            $sortie->setEtat($etat);
            $sortie->setOrganisateur($this->getUser());
            $sortie->setSiteOrganisateur($this->getUser()->getCampus());

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a bien été ajoutée !');
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('sortie/creer.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie,
        ]);
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
        if ($annulerForm->isSubmitted() && $annulerForm->isValid()) {
            $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
            $sortie->setEtat($etat);

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

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier")
     */
    public function modifier(int $id, Request $request, EntityManagerInterface $entityManager, SortieRepository $sortieRepository)
    {

        $sortie = $sortieRepository->find($id);
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if (!$sortie) {
            throw $this->createNotFoundException("Oops ! La sortie n'existe pas !");
        }

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a été modifiée !');
            return $this->redirectToRoute('sortie_liste', ['id' => $sortie->getId()]);
        }
        return $this->render('sortie/modifier.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * Inscription a une Sortie
     * @Route("/sortie/inscription/{id}", name="sortie_inscription")
     */
    public function inscription(int $id,
                                EntityManagerInterface $entityManager,
                                SortieRepository $sortieRepository,
                                Participant $participant
                                ): Response
    {

        $sortie = $sortieRepository->find($id);
        $sortie->addParticipant($this->getUser());
        if (!$sortie) {
            throw $this->createNotFoundException("Oops ! La sortie n'existe pas !");
        }
        if (!$participant) {
            throw $this->createNotFoundException("Oops ! Le Participant n'existe pas !");
        }

        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'Vous etes bien inscrit !');
        return $this->redirectToRoute('sortie_liste');
    }

    /**
     * Inscription a une Sortie
     * @Route("/sortie/desinscription/{id}", name="sortie_desinscription")
     */
    public function desinscription(int $id,
                                   EntityManagerInterface $entityManager,
                                   SortieRepository $sortieRepository,
                                    Participant $participant,
                                    ): Response
    {

        $sortie = $sortieRepository->find($id);
        $sortie-> removeParticipant($this->getUser());

        if (!$sortie) {
            throw $this->createNotFoundException("Oops ! La sortie n'existe pas !");
        }
        if (!$participant) {
            throw $this->createNotFoundException("Oops ! Le Participant n'existe pas !");
        }


        $entityManager->persist($sortie);
        $entityManager->flush();

        $this->addFlash('success', 'Vous n\'etes plus inscrit a cette sortie !');
        return $this->redirectToRoute('sortie_liste');
    }
}
