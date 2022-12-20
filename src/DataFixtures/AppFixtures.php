<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataPersister\UserDataPersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppFixtures extends Fixture
{
    public function __construct(
        UserDataPersister $userDataPersister,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ) 
    {
        $this->userDataPersister = $userDataPersister;
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        // Admin
        $user = $this->userDataPersister->create();
        $user->setFirstName('Filip')
            ->setLastName('Krnjaković')
            ->setEmail('admin@syhotel.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->userDataPersister->getHashPassword($user, 'admin256'))
            ->setIsVerified(true);
        $this->userDataPersister->save($user);
        $manager->persist($user);

        // User
        $user = $this->userDataPersister->create();
        $user->setFirstName('Tea')
            ->setLastName('Vereš')
            ->setEmail('user@syhotel.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->userDataPersister->getHashPassword($user, 'user'))
            ->setIsVerified(true);
        $this->userDataPersister->save($user);
        $manager->persist($user);

        $manager->flush();

        // External SQL
        $this->loadExternalSql($this->entityManager);
    }

    private function loadExternalSql($entityManager): void
    {
        $files = [
            'src/DataFixtures/sql/rooms.sql',
            'src/DataFixtures/sql/guests.sql',
            'src/DataFixtures/sql/reservations.sql',
            'src/DataFixtures/sql/overnight_stays.sql',
            'src/DataFixtures/sql/reservation_update.sql'
        ];

        foreach ($files as $file) {
            $sql = file_get_contents($file);
            $entityManager->getConnection()->executeQuery($sql);
            $entityManager->flush();
        }
    }
}
