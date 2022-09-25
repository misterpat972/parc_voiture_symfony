<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Voiture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $marque1 = new Marque();
        $marque1->setLibelle('Peugeot');
        // persist permet de dire à doctrine de sauvegarder l'objet en base de données
        $manager->persist($marque1);       

        $marque2 = new Marque();
        $marque2->setLibelle('Toyota');
        // persist permet de dire à doctrine de sauvegarder l'objet en base de données
        $manager->persist($marque2);
        
        $modele1 = new Modele();
        $modele1->setLibelle('208');
        $modele1->setPrixMoyen(16000);
        $modele1->setImage('modele1.jpg');
        $modele1->setMarque($marque1);
        $manager->persist($modele1);

        $modele2 = new Modele();
        $modele2->setLibelle('Yaris');
        $modele2->setPrixMoyen(21000);
        $modele2->setImage('modele2.jpg');
        $modele2->setMarque($marque2);
        $manager->persist($modele2);

        $modele3 = new Modele();
        $modele3->setLibelle('Aygo');
        $modele3->setPrixMoyen(13000);
        $modele3->setImage('modele3.jpg');
        $modele3->setMarque($marque2);
        $manager->persist($modele3);

        $modele4 = new Modele();
        $modele4->setLibelle('3008');
        $modele4->setPrixMoyen(15000);
        $modele4->setImage('modele4.jpg');
        $modele4->setMarque($marque1);
        $manager->persist($modele4);

        $modele5 = new Modele();
        $modele5->setLibelle('prius');
        $modele5->setPrixMoyen(35000);
        $modele5->setImage('modele5.jpg');
        $modele5->setMarque($marque2);
        $manager->persist($modele5);

        $modele6 = new Modele();
        $modele6->setLibelle('308');
        $modele6->setPrixMoyen(34000);
        $modele6->setImage('modele6.jpg');
        $modele6->setMarque($marque1);
        $manager->persist($modele6);

        // on met tout les modèles dans un tableau
        $modeles = [$modele1, $modele2, $modele3, $modele4, $modele5, $modele6];
        // on va utiliser le bundle faker pour générer des données aléatoires
        $faker = Factory::create('fr_FR');
        foreach($modeles as $modele){
            // on va générer des voitures aléatoires pour chaque modèle
            $random = rand(3, 5);
            for($i = 1; $i <= $random; $i++){
                // on crée une nouvelle voiture
                $voiture = new Voiture();
                // on va utiliser les regex de faker pour générer des immatriculations aléatoires
                $voiture->setImmatriculation($faker->bothify("??####??"));
                // ici on va générer un nombre aléatoire de porte entre 3 et 5
                $voiture->setNbPortes($faker->randomElement([3, 5]));
                // ici on va générer une année aléatoire entre 2010 et 2021
                $voiture->setAnnee($faker->numberBetween($min = 2010, $max = 2021));
                // on va générer le modèle
                $voiture->setModele($modele); 
                // on va persister en base de données la voiture   
                $manager->persist($voiture);
            }
        }
        // flush permet d'envoyer les requêtes SQL
        $manager->flush();       
    }
}
