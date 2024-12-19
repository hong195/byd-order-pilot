<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Security;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Security\UserFetcherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\Assert;

readonly class UserFetcher implements UserFetcherInterface
{
    public function __construct(private Security $security)
    {
    }

    public function requiredUserId(): string
    {
        return $this->requiredUser()->getId();
    }

    public function requiredUser(): AuthUserInterface
    {
        /** @var AuthUserInterface|null $user */
        $user = $this->security->getUser();

        if (is_null($user)) {
            throw new AccessDeniedException('Access Denied.');
        }

        Assert::isInstanceOf($user, AuthUserInterface::class, sprintf('Invalid user type %s', \get_class($user)));

        return $user;
    }

    public function nullableUserId(): ?string
    {
        return $this->nullableUser()?->getId();
    }

    public function nullableUser(): ?AuthUserInterface
    {
        /** @var AuthUserInterface|null $user */
        $user = $this->security->getUser();

        return $user;
    }
}
