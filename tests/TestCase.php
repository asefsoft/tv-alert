<?php

namespace Tests;

use App\Models\TVShow;
use App\Models\User;
use Database\Seeders\TVShowSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        echo sprintf('env: %s', $app->environment());

        // using a trick to migrate database JUST ONCE per each whole test and not on each test
        $this->initializeDatabase();

        return $app;
    }

    private function initializeDatabase(): void
    {
        // tnt scout storage is set to test db not that one in the storage folder
        config()->set('scout.tntsearch.storage', base_path('tests'));

        if (config('database.default') == 'sqlite') {
            config()->set('database.connections.sqlite.database', base_path('tests/test.db'));
            $db = app()->make('db');
            try {
                // test db is working
                $db->connection()->getPdo()->exec('pragma foreign_keys=1');

                // if tvshow table is not exists then this will throw exception
                TVShow::count();

                // if db is not working then create it
            } catch (\Exception $e) {
                // create db file if not exist
                try {
                    touch(config('database.connections.sqlite.database'));
                } catch (\Exception $e) {
                }
                // finally migrate
                Artisan::call('migrate:fresh');
            }

            // not enough tvshow exist? then seed it
            // 7 or more is needed to activate pagination
            if (TVShow::getCloseAirDateShows()->count() < 7 || TVShow::count() < TVShowSeeder::TOTAL_TVSHOWS_SEED) {
                Artisan::call('db:seed --class=TVShowSeeder');
            }

            if (User::count() < 10) {
                Artisan::call('db:seed --class=UserSeeder');
            }
        }
    }
}
