<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

final readonly class OrderFilter
{
    public function __construct(public ?int $perPage = null, public ?int $page = null)
    {
    }
}
