<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasRegDocsClient;
use Idez\Caradhras\Data\Fingerprint;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasRegDocsClientTest extends TestCase
{
    use WithFaker;

    protected CaradhrasRegDocsClient $regDocsClient;
    protected string $baseAPI;

    protected function setUp(): void
    {
        parent::setUp();
        $this->regDocsClient = app(CaradhrasRegDocsClient::class);
        $this->baseAPI = $this->regDocsClient->getApiBaseUrl();
    }

    protected function fakeHttpTokenRegistration(array $documents)
    {
        Http::fake([
            "{$this->baseAPI}/v1/registration?types=PRIVACY_POLICY&types=TERMS_OF_USE" => Http::response([
                'message' => 'Regulatory Documents retrieved successfully',
                'result' => [
                    'regulatoryDocuments' => $documents,
                ],
            ]),
        ]);
    }

    public function testCaradhrasApiPrefixIsPixBass()
    {
        $this->assertEquals('regdocs', $this->regDocsClient::API_PREFIX);
    }

    public function testGetDocsReturnFullDocs()
    {
        $originalDocs = [
            [
                'regDocObj' => '123_batatinha.pdf',
                'type' => 'TERMS_OF_USE',
                'token' => '10327fa8-9af6-4e59-a2fc-b0d1ffdf9529',
            ],
            [
                'regDocObj' => 'regras_da_firma.pdf',
                'type' => 'PRIVACY_POLICY',
                'token' => 'de61d61e-fed5-4d5f-adee-7301fa3aafbf',
            ],
        ];

        $this->fakeHttpTokenRegistration($originalDocs);

        $retrievedDocs = $this->regDocsClient->getDocs();
        $this->assertEquals($originalDocs, $retrievedDocs);
    }

    public function testTokensAreReturnedCorrectly()
    {
        $originalDocs = [
            [
                'regDocObj' => '123_batatinha.pdf',
                'type' => 'TERMS_OF_USE',
                'token' => '2345Meia78',
            ],
            [
                'regDocObj' => 'regras_da_firma.pdf',
                'type' => 'PRIVACY_POLICY',
                'token' => 'Tá na hora de molhar o biscoito',
            ],
        ];

        $this->fakeHttpTokenRegistration($originalDocs);

        $tokens = $this->regDocsClient->getTokens();
        $this->assertEquals([
            '2345Meia78',
            'Tá na hora de molhar o biscoito',
        ], $tokens);
    }

    public function testTokensAreValidatedCorrectly()
    {
        Http::fake([
            "{$this->baseAPI}/v1/agreement" => Http::response([
                'message' => 'Agreements accepted successfully',
            ]),
        ]);

        $fingerprint = new Fingerprint('agent-1', '192.168.0.1');
        $this->regDocsClient->validateTokens(['a', 'b'], $fingerprint, '1234');

        Http::assertSent(
            fn (Request $request) => $request['tokens'] === ['a', 'b']
            && $request['fingerprint'] === $fingerprint->tokenize()
        );
    }
}
