<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Rolls;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Shared\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class ShipAndCollectOrdersAction.
 *
 * This class represents a controller action that sends a roll to print check-in and returns a JSON response.
 *
 * @Route("/api/rolls/ship-and-collect/{rollId}", name="ship_and_collect_orders", requirements={"rollId"="\d+"}, methods={"POST"})
 */
#[AsController]
#[Route('/api/rolls/ship-and-collect/{rollId}', name: 'ship_and_collect_orders', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final class ShipAndCollectOrdersAction extends BaseController
{
    /**
     * Class description: this class represents a constructor for the PrivateCommandInteractor class.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor the private command interactor object
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Class description: this class represents a method that is invoked when a specific endpoint is called.
     *
     * @param int $rollId the ID of the roll
     *
     * @return JsonResponse a JSON response object containing a success message
     */
    public function __invoke(int $rollId): JsonResponse
    {
        $this->privateCommandInteractor->shipAndCollectOrders($rollId);

        return $this->json(['message' => 'Success'], Response::HTTP_OK);
    }
}
