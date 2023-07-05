<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class participantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // admin
        $participant = new User();
        $password = $this->userPasswordHasher->hashPassword($participant,'123456');
        $participant->setFirstName('admin')
            ->setLastName('admin')
            ->setPhoneNumber($faker->phoneNumber)
            ->setAdmin(true)
            ->setEmail('admin@admin.fr')
            ->setSite($this->getReference('site'.mt_rand(1,6)))
            ->setActive(true)
            ->setPseudo($faker->name)
            ->setPassword($password)
            ->setImage('test'.mt_rand(1,6).'.jpg');
        $manager->persist($participant);

        // participants actif
        for ($i=1;$i<=30;$i++) {
            $participant = new User();
            $password = $this->userPasswordHasher->hashPassword($participant,'123456');
            $participant->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhoneNumber($faker->phoneNumber)
                ->setAdmin(false)
                ->setEmail($faker->email)
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setActive(true)
                ->setPseudo($faker->name)
                ->setPassword($password)
                ->setImage('test'.mt_rand(1,6).'.jpg');
            $manager->persist($participant);
            $this->addReference('participant'.$i, $participant);
        }

        // participants inactif
        for ($i=1;$i<=5;$i++) {
            $participant = new User();
            $password = $this->userPasswordHasher->hashPassword($participant,'123456');
            $participant->setFirstName($faker->firstName.' inactif')
                ->setLastName($faker->lastName)
                ->setPhoneNumber($faker->phoneNumber)
                ->setAdmin(false)
                ->setEmail($faker->email)
                ->setSite($this->getReference('site'.mt_rand(1,6)))
                ->setActive(false)
                ->setPseudo($faker->name)
                ->setPassword($password)
                ->setImage('test'.mt_rand(1,6).'.jpg');
            $manager->persist($participant);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [siteFixtures::class];
    }
}
