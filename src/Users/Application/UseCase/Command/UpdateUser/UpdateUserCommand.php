<?php

namespace App\Users\Application\UseCase\Command\UpdateUser;

use App\Shared\Application\Command\CommandInterface;

readonly class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public string $email,
        public string $name,
        public ?string $password
    ) {
    }
}
