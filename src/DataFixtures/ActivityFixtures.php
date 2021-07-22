<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Football
        $football = new Activity();
        $football->setName('Football');
        $football->setDescription('Pour devenir le nouveau M\'Bappé');

        // Rugby
        $rugby = new Activity();
        $rugby->setName('Rugby');
        $rugby->setDescription('Avec l\'aide de Sébastien Chabal');

        // Basket
        $basket = new Activity();
        $basket->setName('Basket');
        $basket->setDescription('Des dunks à gogo !');

        $manager->persist($football);
        $manager->persist($rugby);
        $manager->persist($basket);

        $manager->flush();
    }
}

