<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $attributVilleRepository;
    private $atbCampusRepo;
    private $passwordEncoder;

    public function __construct(VilleRepository $instanceVilleRepository,
                                CampusRepository $instCampusRepo,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->attributVilleRepository = $instanceVilleRepository;
        $this->atbCampusRepo = $instCampusRepo;
        $this->passwordEncoder =$passwordEncoder;
    }


    //création de fausse données avec fzaninotto/faker
    public function load(ObjectManager $manager)
    {
        // Apel du Faker/Factory pour créer de fausses données en FR
        $generator = Factory::create('fr_FR');

        // Ville : création de 20 fausse données
        for ($i = 0; $i <= 20; $i++) {


            $ville = new Ville();
            $ville->setNom($generator->city)
                ->setCodePostal($generator->numberBetween(11111, 99999));
            $manager->persist($ville);
        }

        $manager->flush();

        //Campus : 4 campus ENI
        $campusnom = ['Saint-Herblain', 'Chartres-de-Bretagne', 'Quimper', 'Niort'];
        $campusnoms = [];
        foreach ($campusnom as $nom) {
            $campus = new Campus();
            $campus->setNom($nom);


            $manager->persist($campus);
            $campusnoms[] = $campusnom;

        }
        //Etat : 6 Etats
        $etatlibelle = ['En création', 'Créée', 'Ouverte', 'Clôturée',
            'Activité en cours', 'Passée', 'Annulée', 'Historisée'];
        $etatlibelles = [];
        foreach ($etatlibelle as $libelle) {
            $etat = new Etat();
            $etat->setLibelle($libelle);


            $manager->persist($etat);
            $etatlibelles[] = $libelle;

        }


        //Lieu

        $instancesVilles = $this->attributVilleRepository->findAll();

        for ($i = 0; $i <= 20; $i++) {


            $lieu = new Lieu();
            $lieu->setNom($generator->word)
                ->setRue($generator->address)
                ->setLatitude($generator->latitude)
                ->setLongitude($generator->longitude)
                ->setVille($generator->randomElement($instancesVilles));
            $manager->persist($lieu);
        }

        $manager->flush();

        //participant

        $instCampus = $this->atbCampusRepo->findAll();
        $mail = ['davidp@eni.fr','davidh@eni.fr','laurad@eni.fr'];
        $pseudos = ['davidp', 'davidh', 'laurad'];
        $passwords = ['mdp','mdp','mdp'];


        for ($i = 0; $i < count($pseudos); $i++) {
            $participant = new Participant();
            $participant->setPseudo($pseudos[$i])
                ->setNom($generator->lastName)
                ->setPrenom($generator->firstName)
                ->setTelephone($generator->phoneNumber)
                ->setMail($mail[$i])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $participant,
                    $passwords[$i]
                ))
                ->setRoles([])
                ->setActif(true)
                ->setCampus($generator->randomElement($instCampus));
            $participants[] = $participant;
            $manager->persist($participant);
        }
        $manager->flush();
        //Sortie


    }
}
