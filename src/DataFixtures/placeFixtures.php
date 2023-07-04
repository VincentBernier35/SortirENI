<?php

namespace App\DataFixtures;

use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class placeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1;$i<=6;$i++) {
            $place = new Place();
            $place->setName($faker->company)
                ->setStreet($faker->streetAddress)
                ->setLatitude($faker->latitude)
                ->setLongitude($faker->longitude)
                ->setCity($this->getReference('city'.mt_rand(1,15)));
            $manager->persist($place);
            $this->addReference('place'.$i, $place);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [cityFixtures::class];
    }
}
