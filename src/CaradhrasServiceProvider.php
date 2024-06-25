<?php

namespace Idez\Caradhras;

use Faker\Factory;
use Faker\Generator;
use Idez\Caradhras\Clients\CaradhrasAliasClient;
use Idez\Caradhras\Clients\CaradhrasBankTransferInClient;
use Idez\Caradhras\Clients\CaradhrasCardClient;
use Idez\Caradhras\Clients\CaradhrasCompanyClient;
use Idez\Caradhras\Clients\CaradhrasDataApiClient;
use Idez\Caradhras\Clients\CaradhrasIncomeClient;
use Idez\Caradhras\Clients\CaradhrasIncomeReportsClient;
use Idez\Caradhras\Clients\CaradhrasLimitsClient;
use Idez\Caradhras\Clients\CaradhrasMainClient;
use Idez\Caradhras\Clients\CaradhrasPaymentClient;
use Idez\Caradhras\Clients\CaradhrasPaymentSlipClient;
use Idez\Caradhras\Clients\CaradhrasPrecautionaryBlockClient;
use Idez\Caradhras\Clients\CaradhrasRegDocsClient;
use Idez\Caradhras\Clients\CaradhrasRemunerationsClient;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CaradhrasServiceProvider extends PackageServiceProvider
{
    protected array $clients = [
        CaradhrasMainClient::class,
        CaradhrasAliasClient::class,
        CaradhrasBankTransferInClient::class,
        CaradhrasPaymentClient::class,
        CaradhrasIncomeClient::class,
        CaradhrasPaymentSlipClient::class,
        CaradhrasRegDocsClient::class,
        CaradhrasCompanyClient::class,
        CaradhrasIncomeReportsClient::class,
        CaradhrasRemunerationsClient::class,
        CaradhrasLimitsClient::class,
        CaradhrasDataApiClient::class,
        CaradhrasCardClient::class,
        CaradhrasPrecautionaryBlockClient::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-caradhras-sdk')
            ->hasConfigFile('caradhras');
    }

    public function register()
    {
        parent::register();

        foreach ($this->clients as $client) {
            // TODO use bind and handle access_token ttl.
            $this->app->bind($client, fn ($app) => new $client(
                $app['config']['caradhras']['client'],
                $app['config']['caradhras']['secret']
            ));
        }

        $this->app->singleton(Generator::class, function () {
            return Factory::create('pt_BR');
        });
    }
}
