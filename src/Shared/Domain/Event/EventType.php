<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event;

interface EventType
{
    public const ROLL_WAS_SENT_TO_PRINT_CHECK_IN = 'production_process.roll.was_sent_to_print_check_in';
    public const ROLL_PROCESS_WAS_UPDATED = 'production_process.roll.process_was_updated';
    public const ROLL_WAS_SENT_TO_GLOW_CHECK_IN = 'production_process.roll.was_sent_to_glow_check_in';
    public const ROLL_WAS_SENT_TO_CUT_CHECK_IN = 'production_process.roll.was_sent_to_cut_check_in';
    public const PRINTED_PRODUCT_REPRINTED = 'production_process.roll.printed_product.reprinted';
    public const PRODUCT_CREATED = 'orders.product.created';
    public const FILM_WAS_CREATED = 'inventory.film.was_created';
    public const FILM_WAS_DELETED = 'inventory.film.was_deleted';
    public const FILM_WAS_UPDATED = 'inventory.film.was_updated';
    public const FILM_WAS_USED = 'inventory.film.was_used';
    public const FILE_WAS_REMOVED = 'shared.film.was_used';
}
