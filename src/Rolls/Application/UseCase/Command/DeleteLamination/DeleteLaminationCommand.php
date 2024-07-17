<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\DeleteLamination;

use App\Shared\Application\Command\CommandInterface;

readonly class DeleteLaminationCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ) {
    }
}
