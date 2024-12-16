<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Command\DeleteUser;

use App\Shared\Application\Command\CommandInterface;

readonly class DeleteUserCommand implements CommandInterface
{
    public function __construct(public string $id)
    {
    }
}
