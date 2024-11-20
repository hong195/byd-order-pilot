<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/rolls/merge', name: 'merge_rolls', methods: ['POST'])]
final readonly class MergeRollsAction
{
    /**
     * Class constructor.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor the private command interactor to be injected
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->privateCommandInteractor->mergeRolls((array) $request->get('rollIds'));

        return new JsonResponse(['Success'], Response::HTTP_NO_CONTENT);
    }
}
