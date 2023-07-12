<?php

namespace App\Service;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class CsvImportCommand
{
    private EntityManagerInterface $entityManager;
    private SiteRepository $siteRepository;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        private readonly string     $dataDirectory,
        EntityManagerInterface      $entityManager,
        SiteRepository              $siteRepository,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $this->entityManager = $entityManager;
        $this->siteRepository = $siteRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function uploadCSV(UploadedFile $file): void
    {
        $fileName = 'users.csv';

        try {
            $file->move($this->dataDirectory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }

    private function parseCSV(): array
    {
        $file = $this->dataDirectory . '/users.csv';
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
        $normalizer = [new ObjectNormalizer()];
        $encoders = [new CsvEncoder()];
        $serializer = new Serializer($normalizer, $encoders);

        /**@var string $fileString */
        $fileString = file_get_contents($file);

        return $serializer->decode($fileString, $fileExtension);
    }

    public function createUsersFromCSV(): void
    {
        $check = $this->parseCSV()[0];
        if (array_key_exists('pseudo', $check)
            and array_key_exists('firstname', $check)
            and array_key_exists('lastname', $check)
            and array_key_exists('phone', $check)
            and array_key_exists('email', $check)
            and array_key_exists('password', $check)
            and array_key_exists('site', $check)) {

            foreach ($this->parseCSV() as $row) {
                $user = new User();
                /**@var Site $site */
                $site = $this->siteRepository->findOneBy(['name' => $row['site']]);
                $user->setPseudo($row['pseudo'])
                    ->setFirstName($row['firstname'])
                    ->setLastName($row['lastname'])
                    ->setPhoneNumber($row['phone'])
                    ->setEmail($row['email'])
                    ->setAdmin(false)
                    ->setActive(true)
                    ->setPassword($this->userPasswordHasher->hashPassword($user, $row['password']))
                    ->setSite($site);

                $this->entityManager->persist($user);
            }

            $this->entityManager->flush();
        } else {

        }
    }
}