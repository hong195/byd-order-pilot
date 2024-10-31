<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CreatePrinters;

use App\ProductionProcess\Domain\Service\Printer\InitPrintersService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class CreatePrintersCommandHandler.
 */
readonly class CreatePrintersCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructor.
     */
    public function __construct(private InitPrintersService $initPrintersService)
    {
    }

    /**
     * Invokes the command to create printers.
     *
     * @param CreatePrintersCommand $createPrintersCommand the create printers command
     *
     * @throws \Exception if not allowed to handle resource
     */
    public function __invoke(CreatePrintersCommand $createPrintersCommand): void
    {
        $this->initPrintersService->init();
    }
}
