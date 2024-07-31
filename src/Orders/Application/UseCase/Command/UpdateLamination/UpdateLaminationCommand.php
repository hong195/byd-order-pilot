<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UpdateLamination;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command for updating the lamination of an item.
 *
 * This command is used to update the lamination details of an item in the application.
 */
readonly class UpdateLaminationCommand implements CommandInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param int         $id             the identifier of the instance
     * @param string      $name           the name of the instance
     * @param string      $quality        the quality of the instance
     * @param string      $laminationType the lamination type of the instance
     * @param int         $length         the length of the instance
     * @param int         $priority       The priority of the instance. Default value is 0.
     * @param string|null $qualityNotes   The quality notes of the instance. Default value is null.
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $quality,
        public string $laminationType,
        public int $length,
        public int $priority = 0,
        public ?string $qualityNotes = null
    ) {
    }
}
