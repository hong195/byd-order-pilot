<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddProduct;

use App\Shared\Application\Command\CommandInterface;

/**
 * Class AddProductCommand.
 *
 * @implements CommandInterface
 */
readonly class AddProductCommand implements CommandInterface
{
    /**
     * Class constructor.
     *
     * @param int         $orderId        the order ID
     * @param string      $filmType       the type of film
     * @param string|null $laminationType the type of lamination (optional, defaults to null)
     */
    public function __construct(public int $orderId, public string $filmType, public ?string $laminationType = null)
    {
    }
}
