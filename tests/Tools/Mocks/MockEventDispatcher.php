<?php

declare(strict_types=1);

namespace App\Tests\Tools\Mocks;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MockEventDispatcher implements EventDispatcherInterface
{
    public function dispatch(object $event, ?string $eventName = null): object
    {
        return $event;
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0): void
    {
    }

    public function addSubscriber(object $subscriber): void
    {
    }

    public function removeListener(string $eventName, callable $listener): void
    {
    }

    public function removeSubscriber(object $subscriber): void
    {
    }

    public function getListeners(?string $eventName = null): array
    {
        return [];
    }

    public function hasListeners(?string $eventName = null): bool
    {
        return false;
    }

    public function getListenerPriority(string $eventName, callable $listener): ?int
    {
        return null;
    }
}
