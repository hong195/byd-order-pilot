<?php

namespace App\ProductionProcess\Application\Service\PrintedProduct;

interface RelatedProductsInterface
{
    /**
     * Finds products by their IDs.
     *
     * @param array $relatedProductsIds the array of product IDs to search for
     *
     * @return array the list of products found
     */
    public function findProductsByIds(array $relatedProductsIds): array;
}
