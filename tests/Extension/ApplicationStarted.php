<?php

namespace Tests\Extension;

use PHPUnit\Event;

final class ApplicationStarted implements Event\TestRunner\ExecutionStartedSubscriber
{
    public function __construct()
    {
    }

    public function notify(Event\TestRunner\ExecutionStarted $event): void
    {
    }
}
