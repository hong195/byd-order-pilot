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
#[Route('/api/rolls', name: 'add_roll', methods: ['POST'])]
final readonly class AddRoll
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the controller.
     *
     * @param Request $request the request object
     *
     * @return JsonResponse the JSON response
     */
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $quality = $request->get('quality');
        $rollType = $request->get('rollType');
        $length = $request->get('length');
        $priority = $request->get('priority');
        $qualityNotes = $request->get('qualityNotes');

        $id = $this->privateCommandInteractor->addRoll($name, $quality, $rollType, $length, $priority, $qualityNotes);

        return new JsonResponse([
			'id' => $id,
			'message' => 'Roll added successfully',
		], Response::HTTP_CREATED);
    }
}
