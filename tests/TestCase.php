<?php

namespace Idez\Caradhras\Tests;

use Idez\Caradhras\CaradhrasServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Idez\\Caradhras\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            CaradhrasServiceProvider::class,
        ];
    }

    protected function arrayToObject(array $data): array|object
    {
        return json_decode(json_encode($data));
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('caradhras.client', 'testing-client');
        config()->set('caradhras.secret', 'testing-secret');
        config()->set('caradhras.enable_sentry_middleware', false);

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-caradhras-sdk_table.php.stub';
        $migration->up();
        */
    }
}
