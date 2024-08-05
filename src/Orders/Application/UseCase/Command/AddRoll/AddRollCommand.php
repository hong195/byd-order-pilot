<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddRoll;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class AddRollCommand
 * Implements CommandInterface.
 *
 * Represents a command to add a roll.
 */
readonly class AddRollCommand implements CommandInterface
{
    /**
     * @param string      $name         the name of the object
     * @param int         $length       the length of the object
     * @param string      $quality      the quality of the object
     * @param string      $rollType     the rollType of the object
     * @param string|null $qualityNotes the optional quality notes of the object
     * @param int|null    $priority     the optional priority of the object
     */
    public function __construct(public string $name, public int $length, public string $quality, public string $rollType, public ?string $qualityNotes = null, public ?int $priority = null)
    {
    }
}
