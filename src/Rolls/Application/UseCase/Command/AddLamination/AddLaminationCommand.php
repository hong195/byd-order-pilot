<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Command\AddLamination;


use App\Shared\Application\Command\CommandInterface;

readonly final class AddLaminationCommand implements CommandInterface
{
	public function __construct(
		public string $name,
		public string $quality,
		public string $laminationType,
	) {
	}
}