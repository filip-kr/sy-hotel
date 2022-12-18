<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataPersister\UserDataPersister;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppFixtures extends Fixture
{
    public function __construct(
        UserDataPersister $userDataPersister,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->userDataPersister = $userDataPersister;
        $this->passwordHasher = $passwordHasher;
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
    }
}
