<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Adapters\Fingerprint;
use Idez\Caradhras\Exceptions\RegDocsException;
use Illuminate\Support\Arr;

class CaradhrasRegDocsClient extends BaseApiClient
{
    public const API_PREFIX = 'regdocs';

    public const TYPE_PRIVACY_POLICY = 'PRIVACY_POLICY';
    public const TYPE_TERMS_OF_USE = 'TERMS_OF_USE';

    public const TYPES = [
        self::TYPE_PRIVACY_POLICY,
        self::TYPE_TERMS_OF_USE,
    ];

    /**
     * @throws \Idez\Caradhras\Exceptions\RegDocsException
     */
    public function getDocs($types = self::TYPES): array
    {
        if (is_array($types)) {
            $types = implode('&types=', $types);
        }

        $types = 'types=' . $types;

        $request = $this->apiClient()->get("/v1/registration?{$types}");

        if ($request->failed()) {
            throw new RegDocsException($request->json('message'), $request->status());
        }

        return $request->json('result.regulatoryDocuments');
    }

    /**
     * @throws \Idez\Caradhras\Exceptions\RegDocsException
     */
    public function getTokens(): array
    {
        return Arr::pluck($this->getDocs(), 'token');
    }

    /**
     * Validate regulatory documents tokens.
     *
     * @param  array  $tokens
     * @param  \Idez\Caradhras\Adapters\Fingerprint  $fingerprint
     * @param  null  $registrationId
     * @return object
     * @throws \Idez\Caradhras\Exceptions\RegDocsException
     */
    public function validateTokens(array $tokens, Fingerprint $fingerprint, $registrationId = null): object
    {
        $data = [
            'tokens' => $tokens,
            'fingerprint' => $fingerprint->tokenize(),
        ];

        if (! is_null($registrationId)) {
            $data['idRegistration'] = $registrationId;
        }

        $request = $this->apiClient()->post('/v1/agreement', $data);

        if ($request->failed()) {
            throw new RegDocsException($request->json('message'), $request->status());
        }

        return $request->object();
    }
}
