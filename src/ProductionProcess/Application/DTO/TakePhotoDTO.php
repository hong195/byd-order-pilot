<?php

namespace App\ProductionProcess\Application\DTO;

final readonly class TakePhotoDTO {
    /**
     * @param int      $productId
     * @param int|null $photoId
     */
    public function __construct(public int $productId, public ?int $photoId = null)
    {
    }
}