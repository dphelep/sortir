<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{


    //création de fausse données avec fzaninotto/faker
    public function load(ObjectManager $manager)
    {
       $generator = Factory::create('fr_FR');

        // Ville : création de 20 fausse données
       for ($i = 0; $i <= 20; $i++) {


           $ville = new Ville();
           $ville-> setNom($generator->city)
               ->setCodePostal($generator->numberBetween(11111,99999));

           $manager->persist($ville);

       }
        //Campus : 4 campus ENI
        $campusnom = ['Saint-Herblain', 'Chartres-de-Bretagne', 'Quimper','Niort'];
       $campusnoms = [];
        foreach ($campusnom as $nom) {
            $campus = new Campus();
            $campus->setNom($nom);


            $manager->persist($campus);
            $campusnoms[] = $campusnom;

        }
        /**  //Lieu
        for ($i = 0; $i <= 20; $i++) {


        $lieu = new Lieu();
        $lieu->setNom($generator->word)
        ->setRue($generator->address)
        ->setLatitude($generator->latitude)
        ->setLongitude($generator->longitude);

        $manager->persist($lieu);*/
        }

        $manager->flush();
    }
}
