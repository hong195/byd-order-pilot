<?php

declare(strict_types=1);

namespace App\Users\Domain\Repository;

use App\Shared\Domain\Repository\Pager;

/**
 * UserFilter class represents a filter for querying users.
 */
final readonly class UserFilter
{
    /**
     * Constructor for the class.
     *
     * @param Pager|null  $pager An optional Pager object. Defaults to null.
     * @param string|null $name  An optional string representing a name. Defaults to null.
     * @param string|null $email An optional string representing an email. Defaults to null.
     */
    public function __construct(public ?Pager $pager = null, public ?string $name = null, public ?string $email = null)
    {
    }
}
