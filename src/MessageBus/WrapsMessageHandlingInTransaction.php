<?php

namespace SimpleBus\DoctrineORMBridge\MessageBus;

use Doctrine\ORM\EntityManager;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Message;

class WrapsMessageHandlingInTransaction implements MessageBusMiddleware
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(Message $message, callable $next)
    {
        $this->entityManager->transactional(
            function () use ($message, $next) {
                $next($message);
            }
        );
    }
}