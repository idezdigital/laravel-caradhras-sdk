<?php

namespace Idez\Caradhras\Clients;

use GuzzleHttp\Psr7\Stream;
use Idez\Caradhras\Data\CompanyDocument;
use Idez\Caradhras\Enums\Documents\DocumentErrorCode;
use Idez\Caradhras\Enums\Documents\DocumentSelfieReasonCode;
use Idez\Caradhras\Exceptions\Documents\DuplicatedImageException;
use Idez\Caradhras\Exceptions\Documents\FaceNotVisibleException;
use Idez\Caradhras\Exceptions\Documents\InconsistentSelfieException;
use Idez\Caradhras\Exceptions\Documents\InvalidDocumentException;
use Idez\Caradhras\Exceptions\Documents\InvalidSelfieException;
use Idez\Caradhras\Exceptions\Documents\LowQualitySelfieException;
use Idez\Caradhras\Exceptions\Documents\SendDocumentException;
use Idez\Caradhras\Exceptions\Documents\UniquePartnerException;
use Idez\Caradhras\Exceptions\FailedCreateCompanyAccount;
use Idez\Caradhras\Exceptions\GetCompanyRegistrationException;
use Idez\Caradhras\Exceptions\UpdateCompanyRegistrationException;
use Illuminate\Http\Client\Response;

class CaradhrasIncomeClient extends BaseApiClient
{
    public const API_PREFIX = 'income';

    /**
     * @param int $accountId
     * @param int $profitablePercentage
     * @param int $splitPercentage
     * @return Response
     */
    public function createParametrizationForAccount(
        int $accountId,
        int $profitablePercentage,
        int $splitPercentage
    ): Response {
        return $this
            ->apiClient()
            ->post(
                url: '/v1/setup/accounts',
                data: [
                    'accountId' => $accountId,
                    'profitablePercentage' => $profitablePercentage,
                    'splitPercentage' => $splitPercentage,
                ]
            );
    }
}
