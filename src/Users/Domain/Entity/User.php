<?php

declare(strict_types=1);

namespace App\Users\Domain\Entity;

use App\Shared\Domain\Security\AuthUserInterface;
use App\Shared\Domain\Security\Role;
use App\Users\Domain\Service\UserPasswordHasherInterface;
use Webmozart\Assert\Assert;

class User implements AuthUserInterface
{
    /**
     * @phpstan-ignore-next-line
     */
    private ?int $id;

    private string $name;
    private string $email;
    private ?string $password = null;

    /**
     * @var string[]
     */
    private array $roles = [];

    public function __construct(string $name, string $email, string $role)
    {
        $this->email = $email;
        $this->name = $name;
        $this->addRole($role);

        if (Role::ROLE_USER !== $role) {
            $this->addRole(Role::ROLE_USER);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function setPassword(
        ?string $password,
        UserPasswordHasherInterface $passwordHasher,
    ): void {
        if (is_null($password)) {
            $this->password = null;
        }

        $this->password = $passwordHasher->hash($this, $password);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): void
    {
        Assert::inArray($role, Role::ROLES, 'Неверная роль');
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function eraseCredentials(): void
    {
    }
}
