<?php

declare(strict_types=1);

namespace App\Orders\Domain\Aggregate;

/**
 * Customer class represents a customer in the application.
 */
readonly class Customer
{
    /**
     * Class Constructor.
     */
    public function __construct(public string $name, public ?string $notes = null)
    {
    }
}
