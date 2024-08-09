<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Shared\Application\Command\CommandBusInterface;
use App\Users\Application\UseCase\Command\CreateUser\CreateUserCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/api/users', name: 'create_user', methods: ['POST'])]
readonly class CreateUserAction
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $command = new CreateUserCommand(
            name: $request->get('name'),
            email: $request->get('email'),
            password: $request->get('password')
        );

        $userId = $this->commandBus->execute($command);

        return new JsonResponse([
            'id' => $userId,
        ]);
    }
}
