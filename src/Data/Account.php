<?php

namespace Idez\Caradhras\Data;

use App\Enums\Account\AccountStatus;

/**
* @property int $id
* @property int $idPessoa
* @property string $nome
* @property int $idProduto
* @property int $idOrigemComercial
* @property string $nomeOrigemComercial
* @property int $idFantasiaBasica
* @property string $nomeFantasiaBasica
* @property int $idStatusConta
* @property string $statusConta
* @property int $diaVencimento
* @property int $melhorDiaCompra
* @property string $dataStatusConta
* @property string $dataCadastro
* @property string|null $dataUltimaAlteracaoVencimento
* @property string|null $dataHoraUltimaCompra
* @property int $numeroAgencia
* @property string $numeroContaCorrente
* @property float $valorRenda
* @property string $formaEnvioFatura
* @property bool $titular
* @property float $limiteGlobal
* @property float $limiteSaqueGlobal
* @property float $saldoDisponivelGlobal
* @property float $saldoDisponivelSaque
* @property bool $impedidoFinanciamento
* @property int $diasAtraso
* @property string $proximoVencimentoPadrao
* @property int $idProposta
* @property string $quantidadePagamentos
* @property int $correspondencia
* @property string|null $dataInicioAtraso
* @property float $rotativoPagaJuros
* @property float $totalPosProx
* @property float $saldoAtualFinal
* @property float $saldoExtratoAnterior
* @property false|null $aceitaNovaContaPorGrupoProduto
* @property string|null $funcaoAtiva
* @property bool $possuiOverLimit
* @property int $behaviorScore
*/
class Account extends Data
{
    public const STATUS_MAP = [
        0 => AccountStatus::Active,
        1 => AccountStatus::Blocked,
        33 => AccountStatus::Blocked,
        2 => AccountStatus::Canceled,
    ];

    public const CR_WAITING_DOCUMENTS = 'WAITING_DOCUMENTS';
    public const CR_WAITING_ANALYSIS = 'WAITING_ANALYSIS';
    public const CR_WAITING_CORRECTIONS = 'WAITING_CORRECTIONS';
    public const CR_ACTIVE = 'ACTIVE';
    public const CR_DECLINED = 'DECLINED';
    public const CR_CANCELED = 'CANCELED';

    public const STATUS = [
        self::CR_WAITING_DOCUMENTS => AccountStatus::WaitingDocuments,
        self::CR_WAITING_ANALYSIS => AccountStatus::UnderReview,
        self::CR_WAITING_CORRECTIONS => AccountStatus::UnderReview,
        self::CR_ACTIVE => AccountStatus::Active,
        self::CR_DECLINED => AccountStatus::Denied,
        self::CR_CANCELED => AccountStatus::Canceled,
    ];
}
