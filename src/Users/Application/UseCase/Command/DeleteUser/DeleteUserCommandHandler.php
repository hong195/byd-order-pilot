<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Command\DeleteUser;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Users\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(DeleteUserCommand $createUserCommand): void
    {
        $user = $this->userRepository->findById($createUserCommand->id);

        if (is_null($user)) {
            throw new NotFoundHttpException('User not found');
        }

        $this->userRepository->remove($user);
    }
}
