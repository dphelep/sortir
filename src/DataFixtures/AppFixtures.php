<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
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
    private $atbLieuRepo;
    private $atbParticipantRepo;
    private $atbEtatRepo;

    public function __construct(VilleRepository $instanceVilleRepository,
                                CampusRepository $instCampusRepo,
                                UserPasswordEncoderInterface $passwordEncoder,
                                LieuRepository $instLieuRepo,
                                ParticipantRepository $instParticipantRepo,
                                EtatRepository $instEtatRepo)
    {
        $this->attributVilleRepository = $instanceVilleRepository;
        $this->atbCampusRepo = $instCampusRepo;
        $this->passwordEncoder =$passwordEncoder;
        $this->atbLieuRepo = $instLieuRepo;
        $this->atbParticipantRepo = $instParticipantRepo;
        $this->atbEtatRepo = $instEtatRepo;
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
        $rolesu=['ROLE_USER'];
        $instCampus = $this->atbCampusRepo->findAll();
        $participants = [];
        for ($i = 0; $i < 20; $i++) {
            $participant = new Participant();
            $participant->setPseudo($generator->userName)
                ->setPrenom($generator->lastName)
                ->setNom($generator->firstName)
                ->setTelephone($generator->phoneNumber)
                ->setMail($generator->email)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $participant,'password'))
                ->setRoles($rolesu)
                ->setActif(true)
                ->setCampus($generator->randomElement($instCampus));
            $participants[] = $participant;
            $manager->persist($participant);
        }
        $rolesad=['ROLE_ADMIN'];
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
                ->setRoles( $rolesad)
                ->setActif(true)
                ->setCampus($generator->randomElement($instCampus));
            $manager->persist($participant);
        }
        $manager->flush();


        //Sortie

        $instEtat = $this->atbEtatRepo->findAll();
        $instLieu = $this->atbLieuRepo->findAll();
        $instParticipants = $this->atbParticipantRepo->findAll();
        $sortieNom = ['Sortie Bowling', 'Sortie Ciné', 'Promenade en campagne', 'Allons au parc', 'Allons voir Mickey'];
        $sortieInfo= ["Tu comprends, tu vois au passage qu'il n'y a rien de concret car là, j'ai un chien en ce moment à côté de moi et je le caresse, et ça, c'est très dur, et, et, et... c'est très facile en même temps. Mais ça, c'est uniquement lié au spirit. ",
            "Tu vois, je suis mon meilleur modèle car il faut se recréer... pour recréer... a better you et cela même si les gens ne le savent pas ! Donc on n'est jamais seul spirituellement ! ",
            "Même si on se ment, après il faut s'intégrer tout ça dans les environnements et en vérité, la vérité, il n'y a pas de vérité et cette officialité peut vraiment retarder ce qui devrait devenir... Et là, vraiment, j'essaie de tout coeur de donner la plus belle réponse de la terre ! "];
        for ($i = 0; $i <= 20; $i++) {
            $sortie = new Sortie();
            $dateDeb = ($generator->dateTimeBetween('+10days','+30days'));
            $dateFinInsc = ($dateDeb);
            $organiseSortie = $generator->randomElement($instParticipants);
            $lieuSortie =  $generator->randomElement($instLieu) ;
            $sortie->setNom($generator->randomElement($sortieNom))
                ->setInfosSortie($generator->randomElement($sortieInfo))
                ->SetOrganisateur($organiseSortie)
                ->setDuree($generator->numberBetween(60,380))
                ->setNbInscriptionsMax($generator->numberBetween(5,20))
                ->setDateHeureDebut($dateFinInsc)
                ->setDateLimiteInscription($dateFinInsc)
                ->setSiteOrganisateur($generator->randomElement($instCampus))
                ->setEtat($generator->randomElement($instEtat))
                ->setLieu($lieuSortie);

            $manager->persist($sortie);
        }

        $manager->flush();
    }


}