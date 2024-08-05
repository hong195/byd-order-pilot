<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UpdateRoll;

use App\Shared\Application\Command\CommandInterface;

readonly class UpdateRollCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int         $id           the ID of the item
     * @param string      $name         the name of the item
     * @param string      $quality      the quality of the item
     * @param string      $rollType     the roll type of the item
     * @param int         $length       the length of the item
     * @param int         $priority     The priority of the item. (default: 0)
     * @param string|null $qualityNotes The notes for the quality of the item. (default: null)
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $quality,
        public string $rollType,
        public int $length,
        public int $priority = 0,
        public ?string $qualityNotes = null
    ) {
    }
}
