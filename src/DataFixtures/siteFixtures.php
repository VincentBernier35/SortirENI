<?php

namespace App\DataFixtures;

use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class siteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=1;$i<=6;$i++) {
            $site = new Site();
            $site->setName($faker->city);
            $manager->persist($site);
            $this->addReference('site'.$i, $site);
        }

        $site = new Site();
        $site->setName('TEST');
        $manager->persist($site);

        $manager->flush();
    }
}
