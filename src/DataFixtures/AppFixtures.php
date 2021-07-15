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
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use DateTime;
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
                                EtatRepository $instEtatRepo,
                                )
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
        $etatlibelle = ['En création', 'Ouverte',
            'Activité en cours','Passée','Annulée','Historisée'];
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
                ->setRue($generator->streetAddress)
                ->setLatitude($generator->latitude)
                ->setLongitude($generator->longitude)
                ->setVille($generator->randomElement($instancesVilles));
            $manager->persist($lieu);
        }

        $manager->flush();

        //participant
        $avatar="photodefaut.png";
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
                ->setPhoto($avatar)
                ->setCampus($generator->randomElement($instCampus));
            $participants[] = $participant;
            $manager->persist($participant);
        }
        $rolesad=['ROLE_ADMIN'];
        $mail = ['davidp@eni.fr','davidh@eni.fr','laurad@eni.fr'];
        $pseudos = ['davidp', 'davidh', 'laurad'];
        $passwords = ['mdp','mdp','mdp'];
        $firstName = ['David','David','Laura'];
        $lastName = ['Phelep','Huard','Dufaut'];

        for ($i = 0; $i < count($pseudos); $i++) {
            $participant = new Participant();
            $participant->setPseudo($pseudos[$i])
                ->setNom($lastName[$i])
                ->setPrenom($firstName[$i])
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


        $etat = $this->atbEtatRepo->findAll();
        $instLieu = $this->atbLieuRepo->findAll();
        $instParticipants = $this->atbParticipantRepo->findAll();
        $sortieNom = ['Sortie Bowling', 'Sortie Ciné', 'Promenade en campagne', 'Allons au parc', 'Allons voir Mickey'];
        $sortieInfo= ["Tu comprends, tu vois au passage qu'il n'y a rien de concret car là, j'ai un chien en ce moment à côté de moi et je le caresse, et ça, c'est très dur, et, et, et... c'est très facile en même temps. Mais ça, c'est uniquement lié au spirit. ",
            "Tu vois, je suis mon meilleur modèle car il faut se recréer... pour recréer... a better you et cela même si les gens ne le savent pas ! Donc on n'est jamais seul spirituellement ! ",
            "Même si on se ment, après il faut s'intégrer tout ça dans les environnements et en vérité, la vérité, il n'y a pas de vérité et cette officialité peut vraiment retarder ce qui devrait devenir... Et là, vraiment, j'essaie de tout coeur de donner la plus belle réponse de la terre ! "];
        for ($i = 0; $i <= 25; $i++) {
            $sortie = new Sortie();
            $organiseSortie = $generator->randomElement($instParticipants);
            $lieuSortie =  $generator->randomElement($instLieu) ;
            $sortie->setNom($generator->randomElement($sortieNom))
                ->setInfosSortie($generator->randomElement($sortieInfo))
                ->SetOrganisateur($organiseSortie)
                ->setDuree($generator->numberBetween(60,380))
                ->setNbInscriptionsMax($generator->numberBetween(10,30))
                ->setSiteOrganisateur($generator->randomElement($instCampus))
                ->setEtat($generator->randomElement($etat))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants))
                ->addParticipant($generator->randomElement($participants));
            $manager->persist($sortie);




            $date1 = $generator->dateTimeBetween('-10days', '+40days');
            $date2 = $generator->dateTimeBetween('-10days', '+40days');

            if ($date1 > $date2) {
                $sortie->setDateHeureDebut($date1)
                ->setDateLimiteInscription($date2);
            } else {
                $sortie->setDateHeureDebut($date2)
                ->setDateLimiteInscription($date1);
                $manager->persist($sortie);
            }


//            $etat1 = $this->atbEtatRepo->find(1);
//            $etat2 = $this->atbEtatRepo->find(2);
//            $etat3 = $this->atbEtatRepo->find(3);
//            $etat4 = $this->atbEtatRepo->find(4);
//            $etat5 = $this->atbEtatRepo->find(5);
//            $etat6 = $this->atbEtatRepo->find(6);
//            $datedujour=new \DateTime('now');
//            $dateHDeb = $sortie->getDateHeureDebut();
//            $dateLimite = $sortie->getDateLimiteInscription();
//                if ($dateHDeb < $datedujour){
//                    $sortie->setEtat($etat1);
//                }if  ($dateHDeb == $datedujour){
//                    $sortie->setEtat($etat3);
//                }
          $manager->persist($sortie);
        }

        $manager->flush();
    }

}