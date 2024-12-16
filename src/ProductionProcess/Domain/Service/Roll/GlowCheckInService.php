<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Roll\Roll;
use App\ProductionProcess\Domain\Events\RollsWereSentToGlowCheckInEvent;
use App\ProductionProcess\Domain\Exceptions\RollCantBeSentToGlowException;
use  App\ProductionProcess\Domain\Repository\Roll\RollRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\GroupService;
use App\ProductionProcess\Domain\ValueObject\Process;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * This class is responsible for handling the printing process.
 */
final readonly class GlowCheckInService
{
    /**
     * Class constructor.
     */
    public function __construct(
        private RollRepositoryInterface $rollRepository, private GroupService $groupService,
        private RollMaker $rollMaker, private EventDispatcherInterface $eventDispatcher,
        private GeneralProcessValidation $generalProcessValidatior,
    ) {
    }

    /**
     * Print a roll.
     *
     * @param string $rollId The ID of the roll to be printed
     *
     * @throws NotFoundHttpException         If the roll is not found
     * @throws RollCantBeSentToGlowException
     */
    public function handle(string $rollId): void
    {
        $rollToGlow = $this->rollRepository->findById($rollId);

        $this->generalProcessValidatior->validate($rollToGlow);

        if (!$rollToGlow->getProcess()->equals(Process::PRINTING_CHECK_IN)) {
            throw new RollCantBeSentToGlowException('Roll cannot be glowed! It is not in the correct process.');
        }

        $printedProducts = new ArrayCollection($rollToGlow->getPrintedProducts()->toArray());
        $printedProductsGroups = $this->groupService->handle($printedProducts);

        if (1 === count($printedProductsGroups)) {
            $firstPrintedProduct = $rollToGlow->getPrintedProducts()->first();
            $hasLamination = null !== $firstPrintedProduct->getLaminationType();
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $rollToGlow->updateProcess($process);

            $this->rollRepository->save($rollToGlow);
            $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent([$rollToGlow->getId()]));

            return;
        }

        $rollToGlow->removePrintedProducts();
        $sendToGlowingRolls = [];

        $rollToGlow->updateProcess(Process::CUT);

        $this->rollRepository->save($rollToGlow);

        foreach ($printedProductsGroups as $group => $groupPrintedProducts) {
            $roll = $this->rollMaker->make(name: $rollToGlow->getName(), filmId: $rollToGlow->getFilmId());

            $roll->setEmployeeId($rollToGlow->getEmployeeId());
            $roll->assignPrinter($rollToGlow->getPrinter());
            $roll->setParentRoll($rollToGlow);

            /** @var PrintedProduct $firstPrintedProduct */
            $firstPrintedProduct = $groupPrintedProducts->first();
            $hasLamination = null !== $firstPrintedProduct->getLaminationType();
            // if roll does not have lamination it goes directly to cut check in, otherwise to glow check in
            $process = $hasLamination ? Process::GLOW_CHECK_IN : Process::CUTTING_CHECK_IN;

            $roll->updateProcess($process);

            foreach ($groupPrintedProducts as $printedProduct) {
                $roll->addPrintedProduct($printedProduct);
            }

            $this->rollRepository->save($roll);
            $sendToGlowingRolls[] = $roll;
        }

        $this->eventDispatcher->dispatch(new RollsWereSentToGlowCheckInEvent(array_map(fn (Roll $roll) => $roll->getId(), $sendToGlowingRolls)));
    }
}
