<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Cache;

use App\Shared\Domain\Repository\PaginationResult;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserFilter;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CachedUserRepository implements UserRepositoryInterface
{
    private const TTL = 600;

    public function __construct(private readonly UserRepositoryInterface $userRepository, private readonly CacheInterface $cache)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function add(User $user): void
    {
        $this->cache->get("user_{$user->getId()}", function (ItemInterface $item) use ($user) {
            $item->expiresAfter(self::TTL);

            $this->userRepository->add($user);

			return $user;
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findById(string $id): ?User
    {
        return $this->cache->get("user_{$id}", function (ItemInterface $item) use ($id) {
            $item->expiresAfter(self::TTL);

            return $this->userRepository->findById($id);
        });
    }

    public function save(User $user): void
    {
        $this->cache->get("user_{$user->getId()}", function (ItemInterface $item) use ($user) {
            $item->expiresAfter(self::TTL);

            $this->userRepository->add($user);
        });
    }

    public function findByEmail(string $email): ?User
    {
        return $this->cache->get("user_{$email}", function (ItemInterface $item) use ($email) {
            $item->expiresAfter(self::TTL);

            return $this->userRepository->findByEmail($email);
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function remove(User $user): void
    {
        $this->cache->delete("user_{$user->getId()}");

        $this->userRepository->remove($user);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findByFilter(UserFilter $userFilter): PaginationResult
    {
        $cacheFilterKey = $this->getFilterKey($userFilter);

        return $this->cache->get($cacheFilterKey, function (ItemInterface $item) use ($userFilter) {
            $item->expiresAfter(self::TTL);

            return $this->userRepository->findByFilter($userFilter);
        });
    }

    private function getFilterKey(UserFilter $userFilter): string
    {
        $key = 'users_filters';

        if ($userFilter->name) {
            $key .= $userFilter->name;
        }

        if ($userFilter->email) {
            $key .= $userFilter->email;
        }

        if ($userFilter->ids) {
            $key .= implode(',', $userFilter->ids);
        }

        if ($userFilter->pager) {
            $key .= $userFilter->pager->page;
            $key .= $userFilter->pager->getLimit();
            $key .= $userFilter->pager->getOffset();
        }

        return $key;
    }
}
