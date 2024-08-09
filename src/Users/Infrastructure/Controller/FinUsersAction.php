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
#[Route('/api/users', methods: ['GET'])]
readonly class FinUsersAction
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
     * @param Request $request the HTTP request object
     *
     * @return JsonResponse the JSON response
     *
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $users = $this->useCaseInteractor->findUsers(
            page: (int) $request->get('page'),
            email: $request->get('email'),
            name: $request->get('name')
        );

        $result = $this->normalizer->normalize($users);

        return new JsonResponse($result);
    }
}
