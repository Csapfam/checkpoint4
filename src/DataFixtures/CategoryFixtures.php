<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture 
{
    public function load(ObjectManager $manager)
    {
        // Under 15 years
        $under15 = new Category();
        $under15->setName('Moins de 15 ans');

        // Under 17 years
        $under17 = new Category();
        $under17->setName('Moins de 17 ans');

         // Under 19 years
         $under19 = new Category();
         $under19->setName('Moins de 19 ans');

        $manager->persist($under15);
        $manager->persist($under17);
        $manager->persist($under19);

        $manager->flush();
    }

}

