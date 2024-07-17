<?php

declare(strict_types=1);

namespace App\Users\Domain\Repository;

use App\Users\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function findById(int $id): ?User;

    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    public function remove(User $user): void;
}
