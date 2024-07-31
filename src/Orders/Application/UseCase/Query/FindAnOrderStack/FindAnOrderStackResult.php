<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindAnOrderStack;

use App\Orders\Application\DTO\OrderStackData;

/**
 * Represents the result of finding a roll.
 */
final readonly class FindAnOrderStackResult
{
	/**
	 * Constructor.
	 *
	 * @param OrderStackData $orderStackData The OrderStackData object.
	 */
    public function __construct(public OrderStackData $orderStackData)
    {
    }
}
