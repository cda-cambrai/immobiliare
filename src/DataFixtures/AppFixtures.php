<?php

namespace App\DataFixtures;

use App\Entity\RealEstate;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;

    /**
     * Dans une classe d'un projet Symfony, on peut récupèrer n'importe quel service
     * via le constructeur
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // On crée une instance de Faker pour générer les données aléatoires
        $faker = Factory::create('fr_FR');

        // On crée des catégories avant de créer des annonces
        $typeNames = ['Maison', 'Appartement', 'Villa', 'Garage', 'Studio'];
        foreach ($typeNames as $key => $typeName) {
            $type = new Type(); // use App\Entity\Type;
            $type->setName($typeName);
            $this->addReference('type-'.$key, $type); // ['type-0' => $type, 'type-1' => $type];
            $manager->persist($type);
        }

        for ($i = 1; $i <= 100; $i++) {
            $realEstate = new RealEstate();
            $type = $this->getReference('type-'.rand(0, count($typeNames) - 1)); // On prend une catégorie aléatoire
            $title = ucfirst( $type->getName() ).' '; // Appartement ou Maison
            $rooms = $faker->numberBetween(1, 5);
            $title .= RealEstate::SIZES[$rooms]; // T2, T3, T4
            // Appartement Studio (avec jardin ou avec balcon)
            // Maison T4 (en centre-ville ou en campagne)
            $realEstate->setTitle($title);
            $realEstate->setSlug($this->slugger->slug($title)->lower());
            $realEstate->setDescription($faker->text(2000));
            $realEstate->setSurface($faker->numberBetween(10, 400));
            $realEstate->setPrice($faker->numberBetween(34875, 584725));
            $realEstate->setRooms($rooms);
            $realEstate->setType($type);
            $realEstate->setSold($faker->boolean(10)); // 10% de chances d'avoir true
            $realEstate->setImage($faker->randomElement([
                'default.png', 'fixtures/1.jpg', 'fixtures/2.jpg', 'fixtures/3.jpg', 'fixtures/4.jpg', 'fixtures/5.png'
            ]));
            $manager->persist($realEstate);
        }

        $manager->flush();
    }
}
