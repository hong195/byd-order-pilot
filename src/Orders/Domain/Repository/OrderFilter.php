<?php

declare(strict_types=1);

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\ValueObject\Status;

final readonly class OrderFilter
{
	public function __construct(public ?int $rollId = null, public ?Status $status = null)
	{
	}
}
