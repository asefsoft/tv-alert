<?php

namespace Tests\Extension;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\PendingCommand;
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
