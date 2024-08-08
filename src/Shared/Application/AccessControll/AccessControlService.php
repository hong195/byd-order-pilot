<?php

declare(strict_types=1);

namespace App\Shared\Application\AccessControll;

use App\Shared\Application\Security\AuthChecker;
use App\Shared\Domain\Security\Role;

/**
 * AccessControlService constructor.
 *
 * @param AuthChecker $authChecker
 */
class AccessControlService
{
    public function __construct(
        private AuthChecker $authChecker
    ) {
    }

    /**
     * Checks if the current user is granted to add/update/delete a resource.
     *
     * @return bool returns true if the current user is an admin, false otherwise
     */
    public function isGranted(): bool
    {
        return $this->authChecker->isGranted(Role::ROLE_ADMIN)
            || $this->authChecker->isGranted(Role::ROLE_USER);
    }
}
