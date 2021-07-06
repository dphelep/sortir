<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil/modifier/{id}', name: 'profil_modifier')]
    public function modifier(int $id,
                             ParticipantRepository $participantRepository,
                             EntityManagerInterface $entityManager,
                             Request $request) {

        $participant = $participantRepository->find($id);
        $profilForm = $this->createForm(ProfilType::class, $participant);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié avec succès !');
            return $this->redirectToRoute('sortie_list', [
                'id' => $participant->getId(),
            ]);
        }

        return $this->render('profil/monProfil.html.twig', [
            'profilForm' => $profilForm->createView(),
        ]);
    }
}
