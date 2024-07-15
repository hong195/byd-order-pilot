<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Users\Application\UseCase\Command\UpdateUser\UpdateUserCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/users/{id}', name: 'update_user', requirements: ['id' => '[0-9]+'], methods: ['PUT'])]
class UpdateUser implements CommandInterface
{
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(int $id, Request $request): JsonResponse
    {
        $updateCommand = new UpdateUserCommand(
			userId: $id,
			email: $request->get('email'),
			name: $request->get('name'),
			password: $request->get('password')
		);

        $this->commandBus->execute($updateCommand);

        return new JsonResponse([
			'message' => 'User updated successfully.'
		], Response::HTTP_OK);
    }
}
