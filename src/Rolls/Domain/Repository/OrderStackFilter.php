<?php

declare(strict_types=1);

namespace App\Rolls\Domain\Repository;


readonly class OrderStackFilter
{
    public function __construct(
        public ?string $rollType = null,
        public ?string $laminationType = null,
    ) {
    }
}
