<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/modifier", name="profil_modifier")
     */
    public function modifier(Request $request,
                             EntityManagerInterface $entityManager,
                             ): Response {

        $participant = $this->getUser();
        $profilForm = $this->createForm(ProfilType::class, $participant);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid()) {

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié avec succès !');
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('profil/monProfil.html.twig', [
            'profilForm' => $profilForm->createView(),
        ]);
    }
}
