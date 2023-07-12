<?php

namespace App\DataFixtures;

use App\Entity\Commnet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class commentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i=1;$i<=16;$i++) {
            $comment = new Comment();
            $comment->setContent($faker->paragraph)
                ->setEvent($this->getEvent('event'.mt_rand(1,15)));
            $manager->persist($comment);
            $this->addReference('comment'.$i, $comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [cityFixtures::class];
    }
}
