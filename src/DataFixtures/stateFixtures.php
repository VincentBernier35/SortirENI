<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class stateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=0;$i<=5;$i++) {
            $state = new State();
            $state->setReference($i);
            $manager->persist($state);
            $this->addReference('state'.$i, $state);
        }

        $manager->flush();
    }
}
