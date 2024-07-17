<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Command\UpdateUser;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Users\Domain\Service\UserPasswordHasherInterface;
use App\Users\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if (is_null($user)) {
            throw new NotFoundHttpException('User not found');
        }

        $user->setEmail($command->email);
        $user->setName($command->name);

        if (!empty($command->password)) {
            $user->setPassword($command->password, $this->passwordHasher);
        }

        $this->userRepository->save($user);
    }
}
