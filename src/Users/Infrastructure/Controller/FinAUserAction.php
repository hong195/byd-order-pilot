<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Controller;

use App\Users\Application\UseCase\PrivateUseCaseInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * The FinUsersAction class is a controller that handles the GET request to '/api/users' route.
 *
 * @Route('/api/users', methods: ['GET'])
 */
#[AsController]
#[Route('/api/users/{id}', requirements: ['id' => '^\w+$'], methods: ['GET'])]
readonly class FinAUserAction
{
    /**
     * Constructs a new instance of the class.
     */
    public function __construct(private PrivateUseCaseInteractor $useCaseInteractor, private NormalizerInterface $normalizer)
    {
    }

    /**
     * Invokes the controller action.
     *
     * @return JsonResponse the JSON response
     *
     * @throws ExceptionInterface
     */
    public function __invoke(string $id): JsonResponse
    {
        $user = $this->useCaseInteractor->findAUser($id);

        $result = $this->normalizer->normalize($user->user);

        return new JsonResponse($result);
    }
}
