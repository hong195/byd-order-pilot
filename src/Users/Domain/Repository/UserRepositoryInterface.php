<?php

declare(strict_types=1);

namespace App\Users\Domain\Repository;

use App\Shared\Domain\Repository\PaginationResult;
use App\Users\Domain\Entity\User;

/**
 * Interface UserRepositoryInterface.
 *
 * This interface represents a repository for managing User entities.
 */
interface UserRepositoryInterface
{
    /**
     * Add a new user.
     *
     * @param User $user the User entity object to be added
     */
    public function add(User $user): void;

    /**
     * Find a user by ID.
     *
     * @param string $id the ID of the user
     *
     * @return User|null the User entity object if found, null otherwise
     */
    public function findById(string $id): ?User;

    /**
     * Save a user to the database.
     *
     * @param User $user the user to be saved
     */
    public function save(User $user): void;

    /**
     * Find a user by email.
     *
     * @param string $email the email of the user to be found
     *
     * @return User|null the user with the specified email, or null if not found
     */
    public function findByEmail(string $email): ?User;

    /**
     * Remove a user from the database.
     *
     * @param User $user the user to be removed
     */
    public function remove(User $user): void;

    /**
     * Find users paginated based on the provided UserFilter object.
     *
     * @param UserFilter $userFilter the UserFilter object containing filter parameters
     *
     * @return PaginationResult the paginated result of User entities based on the filter
     */
    public function findByFilter(UserFilter $userFilter): PaginationResult;
}
