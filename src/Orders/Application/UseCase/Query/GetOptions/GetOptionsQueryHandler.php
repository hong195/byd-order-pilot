<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\GetOptions;

use App\Orders\Application\DTO\RollDataTransformer;
use App\Orders\Domain\Repository\RollRepositoryInterface;
use App\Orders\Domain\ValueObject\FilmType;
use App\Orders\Domain\ValueObject\LaminationType;
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
     * @param GetOptionsQuery $optionsQuery the FindRollsQuery instance
     *
     * @return GetOptionsQueryResult the GetOptionsQueryResult instance
     *
     * @throws \Exception if access is denied
     */
    public function __invoke(GetOptionsQuery $optionsQuery): GetOptionsQueryResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $lamination = [
            ucwords(LaminationType::GOLD_FLAKES->name, '_') => LaminationType::GOLD_FLAKES->value,
            ucwords(LaminationType::HOLO_FLAKES->name, '_') => LaminationType::HOLO_FLAKES->value,
            ucwords(LaminationType::GLOSSY->name, '_') => LaminationType::GLOSSY->value,
            ucwords(LaminationType::MATT->name, '_') => LaminationType::MATT->value,
        ];

        $filmTypes = [
            ucwords(FilmType::CHROME->name, '_') => FilmType::CHROME->value,
            ucwords(FilmType::NEON->name, '_') => FilmType::NEON->value,
            ucwords(FilmType::WHITE->name, '_') => FilmType::WHITE->value,
            ucwords(FilmType::ECO->name, '_') => FilmType::ECO->value,
        ];

        return new GetOptionsQueryResult(compact('filmTypes', 'lamination'));
    }
}
