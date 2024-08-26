<?php

declare(strict_types=1);

namespace App\Orders\Application\DTO;

/**
 * OrderData class represents order data.
 */
final readonly class ProductData
{
    /**
     * Constructor for MyClass.
     *
     * @param int         $id             the ID of the object
     * @param string      $filmType       the type of film
     * @param string|null $laminationType The type of lamination. Optional, defaults to null.
     */
    public function __construct(public int $id, public string $filmType, public ?string $laminationType = null)
    {
    }
}
