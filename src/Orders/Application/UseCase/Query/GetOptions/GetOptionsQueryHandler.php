<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\GetOptions;

use App\ProductionProcess\Application\DTO\RollDataTransformer;
use App\ProductionProcess\Domain\Repository\RollRepositoryInterface;
use App\ProductionProcess\Domain\ValueObject\FilmType;
use App\ProductionProcess\Domain\ValueObject\LaminationType;
use App\ProductionProcess\Domain\ValueObject\OrderType;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * FindRollsHandler constructor.
 *
 * @param RollRepositoryInterface $rollRepository
 * @param RollDataTransformer     $rollDataTransformer
 */
final readonly class GetOptionsQueryHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private AccessControlService $accessControlService)
    {
    }

    /**
     * Invokes the class.
     *
     * @param GetOptionsQuery $optionsQuery the GetPrintedProductsProcessDetailQuery instance
     *
     * @return GetOptionsQueryResult the GetOptionsQueryResult instance
     *
     * @throws \Exception if access is denied
     */
    public function __invoke(GetOptionsQuery $optionsQuery): GetOptionsQueryResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $laminationTypes = [
            LaminationType::GOLD_FLAKES->value => $this->prepareLabel(LaminationType::GOLD_FLAKES->name),
            LaminationType::HOLO_FLAKES->value => $this->prepareLabel(LaminationType::HOLO_FLAKES->name),
            LaminationType::GLOSSY->value => $this->prepareLabel(LaminationType::GLOSSY->name),
            LaminationType::MATT->value => $this->prepareLabel(LaminationType::MATT->name),
        ];

        $filmTypes = [
            FilmType::CHROME->value => $this->prepareLabel(FilmType::CHROME->name),
            FilmType::NEON->value => $this->prepareLabel(FilmType::NEON->name),
            FilmType::WHITE->value => $this->prepareLabel(FilmType::WHITE->name),
            FilmType::ECO->value => $this->prepareLabel(FilmType::ECO->name),
        ];

        $orderTypes = [
            OrderType::Product->value => 'Product',
            OrderType::Combined->value => 'Combined',
        ];

        return new GetOptionsQueryResult(compact('filmTypes', 'laminationTypes', 'orderTypes'));
    }

    private function prepareLabel(string $word): string
    {
        return ucwords(mb_strtolower(str_replace('_', ' ', $word)), ' ');
    }
}
