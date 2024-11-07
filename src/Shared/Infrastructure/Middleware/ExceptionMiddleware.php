<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Middleware;

use App\Shared\Domain\Exception\DomainException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class ExceptionMiddleware implements MiddlewareInterface
{
    /**
     * @throws DomainException
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } catch (HandlerFailedException $e) {
            foreach ($e->getWrappedExceptions() as $exception) {
                if ($exception instanceof DomainException) {
                    throw $exception;
                }
            }
        }
    }
}
