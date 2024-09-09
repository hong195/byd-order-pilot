<?php

declare(strict_types=1);

namespace App\Orders\Domain\Service\Order\Extra;

use App\Orders\Domain\Aggregate\Extra;
use App\Orders\Domain\Repository\ExtraRepositoryInterface;

/**
 * Class constructor.
 *
 * @param ExtraRepositoryInterface $extraRepository instance of ExtraRepositoryInterface
 */
final readonly class ExtraMaker
{
    /**
     * Class constructor.
     *
     * @param ExtraRepositoryInterface $extraRepository instance of ExtraRepositoryInterface
     */
    public function __construct(private ExtraRepositoryInterface $extraRepository)
    {
    }

    /**
     * Creates a new Extra object and adds it to the ExtraRepository.
     *
     * @param string $name        the name of the extra
     * @param string $orderNumber the order number associated with the extra
     *
     * @return Extra the newly created Extra object
     */
    public function make(string $name, string $orderNumber, int $count = 0): Extra
    {
        $extra = new Extra(name: $name, orderNumber: $orderNumber, count: $count);

        $this->extraRepository->add($extra);

        return $extra;
    }
}
