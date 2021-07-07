<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    #[Route('/profil/modifier', name: 'profil_modifier')]
    public function modifier(int $id,
                             UserPasswordEncoderInterface $encoder,
                             EntityManagerInterface $entityManager,
                             Request $request): Response {

        $participant = $this->getUser();
        $profilForm = $this->createForm(ProfilType::class, $participant);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid()) {
            $hashed = $encoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($hashed);

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié avec succès !');
            return $this->redirectToRoute('accueil');
        }

        return $this->render('profil/monProfil.html.twig', [
            'profilForm' => $profilForm->createView(),
        ]);
    }
}
