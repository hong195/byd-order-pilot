<?php

declare(strict_types=1);

namespace App\Users\Application\DTO;

use App\Users\Domain\Entity\User;

/**
 * Represents a User Data Transfer Object.
 */
readonly class UserDTO
{
    /**
     * Class Person.
     *
     * Represents a person with the specified properties.
     */
    public function __construct(public int $id, public string $name, public string $email)
    {
    }

    /**
     * Creates a new instance of the class from a User entity.
     *
     * @param User $user the User entity to create the instance from
     *
     * @return self the new instance of the class
     */
    public static function fromEntity(User $user): self
    {
        return new self(id: $user->getId(), name: $user->getName(), email: $user->getEmail());
    }

    /**
     * Transform an array of User entities to an array of transformed User entities.
     *
     * @param User[] $users An array of User entities
     *
     * @return self[] An array of transformed User entities
     */
    public static function fromUsersList(array $users): array
    {
        return array_map(fn (User $user) => self::fromEntity($user), $users);
    }
}
