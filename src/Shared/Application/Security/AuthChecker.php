<?php

declare(strict_types=1);

namespace App\Shared\Application\Security;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Вспомогательный класс для проверки прав доступа.
 */
readonly class AuthChecker
{
    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    /**
     * Checks if the current user has the given attribute(s) for the given subject.
     *
     * @param mixed $attribute the attribute(s) to check
     * @param mixed $subject   (optional) The subject to check against
     *
     * @return bool returns true if the current user has the given attribute(s) for the given subject, false otherwise
     */
    public function isGranted(mixed $attribute, mixed $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($attribute, $subject);
    }
}
