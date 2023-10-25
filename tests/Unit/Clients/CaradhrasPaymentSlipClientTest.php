<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasPaymentSlipClient;
use Illuminate\Support\Facades\Http;
use Monolog\Test\TestCase;

class CaradhrasPaymentSlipClientTest extends TestCase
{
    public function getCaradhrasInstance(): CaradhrasPaymentSlipClient
    {
        return app(CaradhrasPaymentSlipClient::class);
    }

    public function testCanCancelABankSlip()
    {
        $bankSlip = '123456789';
        Http::fake([
            "https://api.caradhras.io/v1/$bankSlip/write-off" => Http::response([], 200),
        ]);

        $this->getCaradhrasInstance()->cancel($bankSlip);

        Http::assertSent(function ($request) use ($bankSlip) {
            return $request->url() === "https://api.caradhras.io/v1/$bankSlip/write-off"
                && $request->method() === 'POST';
        });
    }
}
