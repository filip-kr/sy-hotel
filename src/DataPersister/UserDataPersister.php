<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function create(): User
    {
        return new User();
    }

    public function getHashPassword($user, $password) {
        return $this->passwordHasher->hashPassword(
            $user,
            $password
        );
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}