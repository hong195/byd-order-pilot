<?php

declare(strict_types=1);

namespace App\Orders\Domain\DTO;

/**
 * The ProcessDTO class represents a data transfer object for processes in a Symfony application.
 */
final readonly class ProcessDTO
{
    /**
     * Constructor for a Symfony application.
     *
     * @param int    $productId                the identifier of the object
     * @param int    $rollId            the identifier of the roll
     * @param string $process           the process related to the object
     * @param bool   $isReprint         Whether the object is a reprint or not. Defaults to false.
     * @param bool   $isReadyForPacking Whether the object is ready for packing or not. Defaults to false.
     */
    public function __construct(public int $productId, public int $rollId, public string $process, public bool $isReprint = false, public bool $isReadyForPacking = false)
    {
    }
}
