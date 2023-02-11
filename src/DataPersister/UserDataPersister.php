<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Contract\DataPersister\UserDataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserDataPersister implements UserDataPersisterInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private EntityManagerInterface      $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function create(): User
    {
        return new User();
    }

    /**
     * @inheritDoc
     */
    public function getHashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            $password
        );
    }

    /**
     * @inheritDoc
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
