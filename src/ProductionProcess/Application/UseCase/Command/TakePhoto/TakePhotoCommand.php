<?php

declare(strict_types=1);

/**
 * Represents a command to manually add a photo to a specific product.
 */

namespace App\ProductionProcess\Application\UseCase\Command\TakePhoto;

use App\Shared\Application\Command\CommandInterface;

/**
 * Represents a command to manually add an order.
 */
readonly class TakePhotoCommand implements CommandInterface
{
    /**
     * Initializes a new instance of the class.
     *
     * @param int $productId the ID of the product
     * @param int $photoId   stored photo ID
     */
    public function __construct(public int $productId, public int $photoId)
    {
    }
}
