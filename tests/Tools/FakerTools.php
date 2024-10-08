<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use Faker\Factory;
use Faker\Generator;

/**
 * FakerTools trait for generating fake data using Faker library.
 */
trait FakerTools
{
    /**
     * Gets a Faker instance.
     */
    public function getFaker(): Generator
    {
        return Factory::create();
    }
}
