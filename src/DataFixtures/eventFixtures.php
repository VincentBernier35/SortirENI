<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class eventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // dates en cours de création
        for ($i=1;$i<=5;$i++) {
            $event = new Event();
            $dateCreated = $faker->dateTimeBetween('-2 months', '-1 months');
            $dateEnd = $faker->dateTimeBetween('-1 months', 'now');
            $event->setName($faker->realText(50))
                ->setStartTime(\DateTimeImmutable::createFromMutable($dateCreated))
                ->setDeadLine(\DateTimeImmutable::createFromInterface($dateEnd))
                ->setDuration(mt_rand(30,230))
                ->setPlaceMax(mt_rand(3,15))
                ->setInfo($faker->realText(200))
                ->setPromoter($this->getReference('participant'.mt_rand(1,30)))
                ->setState($this->getReference('state0'))
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setPlace($this->getReference('place'.mt_rand(1,6)))
                ->setImage('event'.mt_rand(1,6).'.jpg');

            for ($j=1;$j<=$event->getPlaceMax()-1;$j++) {
                $event->addUsersEvent($this->getReference('participant'.mt_rand(1,30)));
            }
            $manager->persist($event);
            $this->addReference('event'.$i, $event);
        }

        // dates en cours d'inscription
        for ($i=1;$i<=5;$i++) {
            $event = new Event();
            $dateCreated = $faker->dateTimeBetween('-1 months', 'now');
            $dateEnd = $faker->dateTimeBetween('now', '+1 months');
            $event->setName($faker->realText(50))
                ->setStartTime(\DateTimeImmutable::createFromMutable($dateCreated))
                ->setDeadLine(\DateTimeImmutable::createFromInterface($dateEnd))
                ->setDuration(mt_rand(30,230))
                ->setPlaceMax(mt_rand(3,15))
                ->setInfo($faker->realText(200))
                ->setPromoter($this->getReference('participant'.mt_rand(1,30)))
                ->setState($this->getReference('state1'))
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setPlace($this->getReference('place'.mt_rand(1,6)))
                ->setImage('event'.mt_rand(1,6).'.jpg');
            for ($j=1;$j<=$event->getPlaceMax()-1;$j++) {
                $event->addUsersEvent($this->getReference('participant'.mt_rand(1,30)));
            }
            $manager->persist($event);
        }

        // activité cloturée
        for ($i=1;$i<=5;$i++) {
            $event = new Event();
            $dateCreated = $faker->dateTimeBetween('-1 months', '-10 days');
            $dateEnd = $faker->dateTimeBetween('-2 days', '-1 days');
            $event->setName($faker->realText(50))
                ->setStartTime(\DateTimeImmutable::createFromMutable($dateCreated))
                ->setDeadLine(\DateTimeImmutable::createFromInterface($dateEnd))
                ->setDuration(mt_rand(30,230))
                ->setPlaceMax(mt_rand(3,15))
                ->setInfo($faker->realText(200))
                ->setPromoter($this->getReference('participant'.mt_rand(1,30)))
                ->setState($this->getReference('state2'))
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setPlace($this->getReference('place'.mt_rand(1,6)))
                ->setImage('event'.mt_rand(1,6).'.jpg');
            for ($j=1;$j<=$event->getPlaceMax()-1;$j++) {
                $event->addUsersEvent($this->getReference('participant'.mt_rand(1,30)));
            }
            $manager->persist($event);
        }

        // activité en cours
        for ($i=1;$i<=3;$i++) {
            $event = new Event();
            $dateCreated = $faker->dateTimeBetween('-1 months', '-10 days');
            $dateEnd = $faker->dateTimeBetween('-2 days', '-1 days');
            $event->setName($faker->realText(50))
                ->setStartTime(\DateTimeImmutable::createFromMutable($dateCreated))
                ->setDeadLine(\DateTimeImmutable::createFromInterface($dateEnd))
                ->setDuration(mt_rand(30,230))
                ->setPlaceMax(mt_rand(3,15))
                ->setInfo($faker->realText(200))
                ->setPromoter($this->getReference('participant'.mt_rand(1,30)))
                ->setState($this->getReference('state3'))
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setPlace($this->getReference('place'.mt_rand(1,6)))
                ->setImage('event'.mt_rand(1,6).'.jpg');
            for ($j=1;$j<=$event->getPlaceMax()-1;$j++) {
                $event->addUsersEvent($this->getReference('participant'.mt_rand(1,30)));
            }
            $manager->persist($event);
        }

        // activité en terminée
        for ($i=1;$i<=3;$i++) {
            $event = new Event();
            $dateCreated = $faker->dateTimeBetween('-1 months', '-10 days');
            $dateEnd = $faker->dateTimeBetween('-2 days', '-1 days');
            $event->setName($faker->realText(50))
                ->setStartTime(\DateTimeImmutable::createFromMutable($dateCreated))
                ->setDeadLine(\DateTimeImmutable::createFromInterface($dateEnd))
                ->setDuration(mt_rand(30,230))
                ->setPlaceMax(mt_rand(3,15))
                ->setInfo($faker->realText(200))
                ->setPromoter($this->getReference('participant'.mt_rand(1,30)))
                ->setState($this->getReference('state4'))
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setPlace($this->getReference('place'.mt_rand(1,6)))
                ->setImage('event'.mt_rand(1,6).'.jpg');
            for ($j=1;$j<=$event->getPlaceMax()-1;$j++) {
                $event->addUsersEvent($this->getReference('participant'.mt_rand(1,30)));
            }
            $manager->persist($event);
        }

        // activité annulée
        for ($i=1;$i<=3;$i++) {
            $event = new Event();
            $dateCreated = $faker->dateTimeBetween('-1 months', '-10 days');
            $dateEnd = $faker->dateTimeBetween('-2 days', '-1 days');
            $event->setName($faker->realText(50))
                ->setStartTime(\DateTimeImmutable::createFromMutable($dateCreated))
                ->setDeadLine(\DateTimeImmutable::createFromInterface($dateEnd))
                ->setDuration(mt_rand(30,230))
                ->setPlaceMax(mt_rand(3,15))
                ->setInfo($faker->realText(200))
                ->setPromoter($this->getReference('participant'.mt_rand(1,30)))
                ->setState($this->getReference('state5'))
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setPlace($this->getReference('place'.mt_rand(1,6)))
                ->setImage('event'.mt_rand(1,6).'.jpg');
            for ($j=1;$j<=$event->getPlaceMax()-1;$j++) {
                $event->addUsersEvent($this->getReference('participant'.mt_rand(1,30)));
            }
            $manager->persist($event);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [siteFixtures::class,participantFixtures::class];
    }
}
