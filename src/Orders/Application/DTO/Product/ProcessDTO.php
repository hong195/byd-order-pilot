<?php

declare(strict_types=1);

namespace App\Orders\Domain\DTO;

/**
 * The ProcessDTO class represents a data transfer object for processes in a Symfony application.
 */
final readonly class ProcessDTO
{
    /**
     * Class constructor.
     *
     * @param int         $productId         the product ID
     * @param string      $process           the process name
     * @param int|null    $rollId            the roll ID (optional, default is null)
     * @param bool        $isReprint         flag indicating if the item is a reprint (default is false)
     * @param bool        $isReadyForPacking flag indicating if the item is ready for packing (default is false)
     * @param string|null $photo             the photo of process printed product
     */
    public function __construct(public string $productId, public string $process, public ?string $rollId = null, public bool $isReprint = false, public bool $isReadyForPacking = false, public ?string $photo = null)
    {
    }
}
