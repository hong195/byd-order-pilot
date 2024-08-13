<?php

namespace App\Orders\Infrastructure\Adapter;

interface InventoryApiInterface
{
    public function getAvailableFilms(): array;
}
