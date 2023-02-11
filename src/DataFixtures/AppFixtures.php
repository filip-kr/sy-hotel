<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Contract\DataPersister\UserDataPersisterInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

final class AppFixtures extends Fixture
{
    /**
     * @param UserDataPersisterInterface $userDataPersister
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private UserDataPersisterInterface $userDataPersister,
        private EntityManagerInterface     $entityManager
    )
    {
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Admin
        $user = $this->userDataPersister->create();
        $user->setFirstName('Filip');
        $user->setLastName('Krnjaković');
        $user->setEmail('admin@syhotel.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->userDataPersister->getHashPassword($user, 'admin256'));
        $user->setIsVerified(true);
        $this->userDataPersister->save($user);
        $manager->persist($user);

        // User
        $user = $this->userDataPersister->create();
        $user->setFirstName('Tea');
        $user->setLastName('Vereš');
        $user->setEmail('user@syhotel.com');
        $user->setPassword($this->userDataPersister->getHashPassword($user, 'user256'));
        $user->setIsVerified(true);
        $this->userDataPersister->save($user);
        $manager->persist($user);

        $manager->flush();

        // External SQL
        $this->loadExternalSql($this->entityManager);
    }

    /**
     * @param $entityManager
     * @return void
     */
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
