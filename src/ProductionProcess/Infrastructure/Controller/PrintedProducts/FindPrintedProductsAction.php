<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\Shared\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Controller to find a roll by ID.
 *
 * @Route("/api/rolls/{id}", requirements={"id"="\d+"}, methods={"GET"})
 */
#[AsController]
#[Route('/api/rolls/{rollId}/printed-products', name: 'find_printed_products_by_roll_id', requirements: ['rollId' => '^\w+$'], methods: ['GET'])]
final class FindPrintedProductsAction extends BaseController
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor The private query interactor object
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor, private NormalizerInterface $normalizer)
    {
    }

    /**
     * Invokes the action to find printed products.
     *
     * @param Request $request The request object
     *
     * @return JsonResponse The JSON response
     *
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->privateQueryInteractor->findPrintedProductByRollId(
            rollId: (string) $request->get('rollId')
        );

        $result = $this->normalizer->normalize($result);

        return $this->json($result, Response::HTTP_OK, []);
    }
}
