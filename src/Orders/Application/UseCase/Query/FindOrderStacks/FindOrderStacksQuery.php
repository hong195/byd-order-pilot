<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindOrderStacks;

use App\Shared\Application\Query\Query;

/**
 * FindOrderStackQuery is a query class used to find an order stack by its ID.
 */
final readonly class FindOrderStacksQuery extends Query
{

	/**
	 * Class constructor.
	 */
	public function __construct()
    {
    }
}
