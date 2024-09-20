<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO\Printer;

use App\Orders\Domain\Aggregate\Printer;

/**
 * Class RollData.
 *
 * Represents a printer data.
 */
final readonly class PrinterData
{
    /**
     * Constructor for creating an instance of the class.
     *
     * @param int    $id          the ID of the object
     * @param string $name        the name of the object
     * @param array  $filmTypes   (optional) The array of film types associated with the object
     * @param array  $laminations (optional) The array of laminations associated with the object
     */
    public function __construct(public int $id, public string $name, public array $filmTypes = [], public array $laminations = [])
    {
    }
}
