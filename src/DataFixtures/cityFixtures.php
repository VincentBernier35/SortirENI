<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class cityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1;$i<=15;$i++) {
            $city = new City();
            $city->setName($faker->city)->setZipCode($faker->postcode);
            $manager->persist($city);
            $this->addReference('city'.$i, $city);
        }

        $manager->flush();
    }
}
