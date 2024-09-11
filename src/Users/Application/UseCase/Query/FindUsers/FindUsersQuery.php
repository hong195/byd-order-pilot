<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Query\FindUsers;

use App\Shared\Application\Query\Query;
use App\Shared\Domain\Repository\Pager;

/**
 * Represents a query to find users based on email and role.
 */
readonly class FindUsersQuery extends Query
{
    /**
     * Constructs a new instance of the class.
     *
     * @param ?Pager      $pager the pager object
     * @param string|null $email the email string (optional)
     * @param string|null $name  the name string (optional)
     */
    public function __construct(public ?Pager $pager = null, public ?string $email = null, public ?string $name = null, public array $ids = [])
    {
    }
}
