<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\Command\ReprintRoll\ReprintRollCommand;
use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Domain\Exceptions\OrderReprintException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/reprint', name: 'reprint-roll', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final readonly class ReprintRollAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

	/**
	 * Invokes the command to reprint a roll.
	 *
	 * @param int $rollId The ID of the roll to reprint.
	 * @param Request $request The request object.
	 * @return JsonResponse A JSON response indicating success.
	 */
    public function __invoke(int $rollId, Request $request): JsonResponse
    {
		$command = new ReprintRollCommand(
			rollId: $rollId,
			process: $request->get('process'),
			reason: $request->get('reason')
		);
        $this->privateCommandInteractor->reprintRoll($command);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
