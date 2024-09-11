<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Repository;

use App\Shared\Domain\Repository\PaginationResult;
use App\Users\Domain\Entity\User;
use App\Users\Domain\Repository\UserFilter;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /**
     * Constructs a new instance of the UserRepository.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Adds a user to the database.
     *
     * @param User $user The user object to be added
     */
    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds a user by their ID.
     *
     * @param int $id The ID of the user
     *
     * @return User|null The found user object or null if not found
     */
    public function findById(int $id): ?User
    {
        return $this->find($id);
    }

    /**
     * Finds a user entity by email.
     *
     * @param string $email The email of the user entity to be found
     *
     * @return User|null Returns a user entity if found, null otherwise
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Saves a user entity to the database.
     *
     * @param User $user The user entity to be saved
     */
    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes a user entity from the database.
     *
     * @param User $user The user entity to be removed
     */
    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Finds users by filter and returns the result as a paginated list.
     *
     * @return PaginationResult the paginated result
     *
     * @throws \Exception
     */
    public function findByFilter(UserFilter $userFilter): PaginationResult
    {
        $qb = $this->createQueryBuilder('u');

        if ($userFilter->name) {
            $qb->andWhere($qb->expr()->like('u.name', ':name'))
                ->setParameter('name', '%'.$userFilter->name.'%');
        }

        if ($userFilter->email) {
            $qb->andWhere($qb->expr()->like('u.email', ':email'))
                ->setParameter('email', '%'.$userFilter->email.'%');
        }

        if ($userFilter->pager) {
            $qb->setMaxResults($userFilter->pager->getLimit());
            $qb->setFirstResult(max(0, $userFilter->pager->getOffset()));
        }

        if ($userFilter->ids) {
            $qb->andWhere($qb->expr()->in('u.id', $userFilter->ids));
        }

        $paginator = new Paginator($qb->getQuery());

        return new PaginationResult(
            iterator_to_array($paginator->getIterator()),
            $paginator->count()
        );
    }
}
