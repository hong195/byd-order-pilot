<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\MergeRolls;

use App\Shared\Application\Command\CommandInterface;

final readonly class MergeRollsCommand implements CommandInterface
{
    /**
     * Constructor for the class with an array of rollIds.
     *
     * @param int[] $rollIds Array of roll IDs
     */
    public function __construct(public array $rollIds)
    {
    }
}
