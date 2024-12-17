<?php

namespace App\Users\Application\UseCase\Command\UpdateUser;

use App\Shared\Application\Command\CommandInterface;

readonly class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $name,
        public ?string $password,
    ) {
    }
}
