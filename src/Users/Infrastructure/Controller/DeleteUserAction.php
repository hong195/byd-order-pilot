<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Shared\Application\Command\CommandBusInterface;
use App\Users\Application\UseCase\Command\DeleteUser\DeleteUserCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Deletes a user.
 */
#[AsController]
#[Route('/api/users/{id}', name: 'delete_user', requirements: ['id' => '^\w+$'], methods: ['DELETE'])]
readonly class DeleteUserAction
{
    /**
     * Class ExampleClass.
     **/
    public function __construct(private CommandBusInterface $commandBus)
    {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        $deleteCommand = new DeleteUserCommand((int) $request->get('id'));

        $this->commandBus->execute($deleteCommand);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
