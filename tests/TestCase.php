<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\ParallelTesting;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private static $configurationApp = null;
    public function createApplication(): Application
    {

        // using a trick to migrate database JUST ONCE per each whole test and not on each test
        // to do so, we dont let flush and clear the `application` on tearDown event and also
        // cache it here in static $configurationApp variable.
        if(is_null(self::$configurationApp)){
            $app = require __DIR__.'/../bootstrap/app.php';

            $app->make(Kernel::class)->bootstrap();

            if (config('database.default') == 'sqlite') {
                $db = app()->make('db');
                $db->connection()->getPdo()->exec("pragma foreign_keys=1");

                // migrate only if db is sqlite
                Artisan::call('migrate:fresh');
                // Artisan::call('db:seed');

            }

            self::$configurationApp = $app;
            return $app;
        }

        return self::$configurationApp;
    }

    // override tearDown event to prevent flushing app
    protected function tearDown(): void
    {
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();

            ParallelTesting::callTearDownTestCaseCallbacks($this);

//            $this->app->flush();
//
//            $this->app = null;
        }
    }
}
