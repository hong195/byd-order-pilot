<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Query\FindUsers;

use App\Users\Application\DTO\UserDTO;

/**
 * Class FindUsersQueryResult.
 */
readonly class FindUsersQueryResult
{
    /**
     * Constructor for the class.
     *
     * @param UserDTO[] $items the array containing the items
     * @param int       $total the total number of items
     */
    public function __construct(public array $items, public int $total)
    {
    }
}
