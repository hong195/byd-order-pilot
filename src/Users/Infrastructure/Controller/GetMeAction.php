<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Shared\Domain\Security\UserFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/api/users/me', methods: ['GET'])]
readonly class GetMeAction
{
    public function __construct(private UserFetcherInterface $userFetcher)
    {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->userFetcher->requiredUser();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
			'name' => $user->getName(),
			'roles' => $user->getRoles(),
        ]);
    }
}
