<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Exceptions\FindIncomeReportsException;
use Idez\Caradhras\Exceptions\SendIncomeReportsToEmailException;

class CaradhrasIncomeReportsClient extends BaseApiClient
{
    public const API_PREFIX = 'declarables';

    /**
     * Get available income reports.
     * In case of PF, return YYYY, case PJ return YYYY-QX.
     *
     * @throws \Idez\Caradhras\Exceptions\FindIncomeReportsException
     */
    public function getAvailable(int $accountId): ?object
    {
        $request = $this->apiClient(false)
            ->get("/v1/reports/{$accountId}");

        if ($request->failed()) {
            throw new FindIncomeReportsException();
        }

        return $request->object();
    }

    /**
     * Send income reports to user email.
     *
     * @throws \Idez\Caradhras\Exceptions\SendIncomeReportsToEmailException
     */
    public function sendToEmail(int $accountId, string $reportCode, string $email): object
    {
        $request = $this->apiClient(false)
            ->post('/v1/requests', [
                'accountId' => $accountId,
                'reportCode' => $reportCode,
                'email' => $email,
            ]);

        if ($request->failed()) {
            throw new SendIncomeReportsToEmailException();
        }

        return $request->object();
    }
}
