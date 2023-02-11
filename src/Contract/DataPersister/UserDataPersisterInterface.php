<?php

namespace App\Contract\DataPersister;

use App\Entity\User;

interface UserDataPersisterInterface
{
    /**
     * @return User
     */
    public function create(): User;

    /**
     * @param User $user
     * @param string $password
     * @return string
     */
    public function getHashPassword(User $user, string $password): string;

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void;

    /**
     * @param User $user
     * @return void
     */
    public function remove(User $user): void;
}
