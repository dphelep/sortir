<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Services\UploadPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/modifier", name="profil_modifier")
     */
    public function modifier(Request $request,
                             EntityManagerInterface $entityManager,
                             UploadPicture $uploadPicture): Response
    {

        $participant = $this->getUser();
        $profilForm = $this->createForm(ProfilType::class, $participant);

        $profilForm->handleRequest($request);

        if($profilForm->isSubmitted() && $profilForm->isValid()) {

            $image = $profilForm->get('photo')->getData();
            $newFilename = $uploadPicture->save($participant->getNom(), $image, $this->getParameter('upload_photos-profil_dir'));
            $participant->setPhoto($newFilename);

            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Profil modifié avec succès !');
            return $this->redirectToRoute('sortie_liste');
        }

        return $this->render('profil/monProfil.html.twig', [
            'profilForm' => $profilForm->createView(),
        ]);
    }

    /**
     * @Route("/profil/detail/{id}", name="profil_detail")
     */

    public function detail($id, ParticipantRepository $participantRepository ,SortieRepository $sortieRepository): Response
    {
        $participants = $participantRepository->find($id);
        $sortie = $sortieRepository->findAll();
        if (!$participants) {
            throw $this->createNotFoundException("Oops ! Je ne connais pas cette personne !");
        }

        return $this->render('profil/detail.html.twig', [
            'participants' => $participants,
            'sortie' => $sortie
        ]);
    }
}
