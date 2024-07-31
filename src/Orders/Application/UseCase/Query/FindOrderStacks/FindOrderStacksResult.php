<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindOrderStacks;

use App\Orders\Application\DTO\OrderStackData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindOrderStacksResult
{
	/**
	 * Constructor method for the class.
	 *
	 * @param OrderStackData[] $items An array of order stacks.
	 */
    public function __construct(public array $items)
    {
    }
}
