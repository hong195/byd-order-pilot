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
#[Route('/api/printed-products/unassigned', name: 'find_unassigned_printed_products', methods: ['GET'])]
final class FindUnassignedProductsAction extends BaseController
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
	 * @throws ExceptionInterface
	 */
    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->privateQueryInteractor->findUnassignedPrintedProducts();

        $result = $this->normalizer->normalize($result);

        return $this->json($result, Response::HTTP_OK, []);
    }
}
