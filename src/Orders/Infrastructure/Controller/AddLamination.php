<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/laminations', name: 'add_lamination', methods: ['POST'])]
final readonly class AddLamination
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * __invoke method.
     *
     * This method is triggered when the request is made to the corresponding endpoint.
     *
     * @param Request $request The request object
     *
     * @return JsonResponse Returns a JSON response with a success message
     *
     * @throws \Exception Throws an exception if there is an error while adding the lamination
     */

    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $quality = $request->get('quality');
        $rollType = $request->get('laminationType');

        $this->privateCommandInteractor->addLamination($name, $quality, $rollType);

        return new JsonResponse(['message' => 'Lamination added successfully'], Response::HTTP_CREATED);
    }
}
