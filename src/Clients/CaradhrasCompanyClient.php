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
use Idez\Caradhras\Exceptions\Documents\OffCenterFaceException;
use Idez\Caradhras\Exceptions\Documents\SendDocumentException;
use Idez\Caradhras\Exceptions\Documents\UniquePartnerException;
use Idez\Caradhras\Exceptions\FailedCreateCompanyAccount;
use Idez\Caradhras\Exceptions\GetCompanyRegistrationException;
use Idez\Caradhras\Exceptions\UpdateCompanyRegistrationException;

class CaradhrasCompanyClient extends BaseApiClient
{
    public const API_PREFIX = 'companies';

    public const API_DOCUMENTS_PREFIX = 'docspy-api';

    /**
     * @throws UpdateCompanyRegistrationException
     */
    public function updateRegistration(array $companyData, array $productSettings, string $registrationId): object
    {
        $request = $this->apiClient(false)
            ->put("/v1/registrations/{$registrationId}", [
                'company' => $companyData,
                'productSettings' => $productSettings,
            ]);

        if ($request->failed()) {
            throw new UpdateCompanyRegistrationException();
        }

        return $request->object();
    }

    /**
     * Get a company registration.
     *
     * @throws GetCompanyRegistrationException
     */
    public function getRegistration(string $registrationId, bool $spd = true): object
    {
        $request = $this->apiClient(false)
            ->get("/v1/registrations/{$registrationId}", ['statusSPD' => $spd ? 'true' : 'false']);

        if ($request->failed()) {
            throw new GetCompanyRegistrationException();
        }

        return $request->object()->result;
    }

    public function getCompany(string $companyId, bool $spd = true)
    {
        $request = $this->apiClient(false)
            ->get('/v1/registered/companies/'.$companyId, ['statusSPD' => $spd ? 'true' : 'false']);

        if ($request->failed()) {
            throw new GetCompanyRegistrationException();
        }

        return $request;
    }

    public function updateCompany(string $companyId, array $companyData, array $productSettings)
    {
        $request = $this->apiClient(false)
            ->put("/v1/registered/companies/{$companyId}", [
                'company' => $companyData,
                'productSettings' => $productSettings,
            ]);

        if ($request->failed()) {
            throw new UpdateCompanyRegistrationException();
        }

        return $request;
    }

    /**
     * @throws FailedCreateCompanyAccount
     */
    public function createRegistration(array $companyAccountPayload): object
    {
        $request = $this->apiClient(false)
            ->post('/v1/registrations', $companyAccountPayload);

        if ($request->failed()) {
            throw new FailedCreateCompanyAccount($request->json());
        }

        return $request->object();
    }

    /**
     * Add a company document.
     *
     *
     * @throws DuplicatedImageException
     * @throws FaceNotVisibleException
     * @throws InconsistentSelfieException
     * @throws InvalidDocumentException
     * @throws InvalidSelfieException
     * @throws LowQualitySelfieException
     * @throws SendDocumentException
     */
    public function addCompanyDocument(string $registrationId, string $documentType, Stream $file, string $contentType = 'image/jpeg'): CompanyDocument
    {
        return $this->sendDocumentWithCustomApi(
            apiPrefix: self::API_DOCUMENTS_PREFIX,
            endpoint: "/v1/companies/{$registrationId}/documents",
            documentType: $documentType,
            file: $file,
            contentType: $contentType
        );
    }

    /**
     * Add a partner document.
     *
     *
     * @throws DuplicatedImageException
     * @throws FaceNotVisibleException
     * @throws InconsistentSelfieException
     * @throws InvalidDocumentException
     * @throws InvalidSelfieException
     * @throws LowQualitySelfieException
     * @throws SendDocumentException
     */
    public function addPartnerDocument(string $registrationId, string $documentType, Stream $file, string $contentType = 'image/jpeg'): CompanyDocument
    {
        return $this->sendDocumentWithCustomApi(
            apiPrefix: self::API_PREFIX,
            endpoint: "/v1/registrations/{$registrationId}/documents",
            documentType: $documentType,
            file: $file,
            contentType: $contentType
        );
    }

    /**
     * Send company or partner document with custom API prefix and endpoint.
     *
     *
     * @throws DuplicatedImageException
     * @throws FaceNotVisibleException
     * @throws InconsistentSelfieException
     * @throws InvalidDocumentException
     * @throws InvalidSelfieException
     * @throws LowQualitySelfieException
     * @throws SendDocumentException
     * @throws UniquePartnerException
     */
    private function sendDocumentWithCustomApi(string $apiPrefix, string $endpoint, string $documentType, Stream $file, string $contentType = 'image/jpeg'): CompanyDocument
    {
        $queryParams = http_build_query([
            'additionalDetails' => true,
            'category' => $documentType,
        ]);

        $response = $this->apiClient(false)
            ->withBody($file, $contentType)
            ->baseUrl($this->createApiBaseUri($apiPrefix))
            ->post($endpoint.'?'.$queryParams);

        if ($response->failed()) {
            $this->throwsDocumentError($response);
        }

        return new CompanyDocument($response->object()->result);
    }

    public function getPendingDocuments(string $registrationId): object
    {
        return $this->apiClient()
            ->get("/v1/registrations/{$registrationId}/documents/status")
            ->throw()
            ->object();
    }

    /**
     * @throws DuplicatedImageException
     * @throws FaceNotVisibleException
     * @throws InconsistentSelfieException
     * @throws InvalidDocumentException
     * @throws InvalidSelfieException
     * @throws LowQualitySelfieException
     * @throws SendDocumentException
     * @throws UniquePartnerException
     */
    private function throwsDocumentError($response): never
    {
        $errorCode = $response->json('errorCode');
        $reasonCode = $response->json('reasonCode');

        if (filled($errorCode)) {
            throw match ($errorCode) {
                DocumentErrorCode::DuplicatedImage => new DuplicatedImageException(),
                DocumentErrorCode::UniquePartner => new UniquePartnerException(),
                DocumentErrorCode::InvalidSelfie => match (DocumentSelfieReasonCode::tryFrom($reasonCode)) {
                    DocumentSelfieReasonCode::LowQuality => new LowQualitySelfieException(),
                    DocumentSelfieReasonCode::FaceNotVisible => new FaceNotVisibleException(),
                    DocumentSelfieReasonCode::Inconsistent => new InconsistentSelfieException(),
                    default => new InvalidSelfieException()
                },
                DocumentErrorCode::OffCenterFace => new OffCenterFaceException(),
                default => new InvalidDocumentException(),
            };
        }

        throw new SendDocumentException($response->json(), $response->status());
    }

    /**
     * @throws LowQualitySelfieException
     * @throws InconsistentSelfieException
     * @throws InvalidDocumentException
     * @throws DuplicatedImageException
     * @throws InvalidSelfieException
     * @throws FaceNotVisibleException
     * @throws SendDocumentException
     * @throws UniquePartnerException
     */
    public function addCompanyDocumentBiometric(
        string $registrationId,
        string $documentType,
        $base64,
        $jwt
    ): CompanyDocument {
        $queryParams = http_build_query([
            'additionalDetails' => true,
            'category' => $documentType,
        ]);

        $body = match ($documentType) {
            'SELFIE' => ['jwt' => $jwt],
            'DIGITAL_DRIVER_LICENSE' => ['imageBase64' => $base64],
            default => [
                'imageBase64' => $base64,
                'jwt' => $jwt,
            ],
        };

        $response = $this->apiClient(false)
            ->post("/v1/registrations/{$registrationId}/documents/biometric?".$queryParams, $body);

        if ($response->failed()) {
            $this->throwsDocumentError($response);
        }

        return new CompanyDocument($response->object()->result);
    }
}
