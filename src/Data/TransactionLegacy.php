<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $idTipoTransacao
 * @property string $descricaoAbreviada
 * @property string $statusTransacao
 * @property int $idEvento
 * @property string $tipoEvento
 * @property int $idConta
 * @property string $cartaoMascarado
 * @property string $nomePortador
 * @property string|null $dataTransacaoUTC,
 * @property string $dataTransacao
 * @property string|null $dataFaturamento
 * @property string|null $dataVencimento
 * @property null $modoEntradaTransacao
 * @property null $valorTaxaEmbarque
 * @property null $valorEntrada
 * @property float $valorBRL
 * @property float $valorUSD
 * @property float $cotacaoUSD
 * @property string|null $dataCotacaoUSD
 * @property string $codigoMoedaOrigem
 * @property string $codigoMoedaDestino
 * @property int|null $codigoAutorizacao
 * @property int|null $codigoReferencia
 * @property int|null $codigoTerminal
 * @property int|null $codigoMCC
 * @property int|null $grupoMCC
 * @property string|null $grupoDescricaoMCC
 * @property int $idEstabelecimento
 * @property string|null $nomeEstabelecimento
 * @property string|null $nomeFantasiaEstabelecimento
 * @property string|null $localidadeEstabelecimento
 * @property null $planoParcelamento
 * @property null $numeroParcela
 * @property string $detalhesTransacao
 * @property int $flagCredito
 * @property int $flagFaturado
 * @property int $flagEstorno
 * @property int $idTransacaoEstorno
 * @property float $valorCompraMoedaEstrangeira
 * @property string $moedaEstrangeira
 */
class TransactionLegacy extends Data
{
    use HasFactory;

    public const P2P_SENT = 10031;
    public const P2P_RECEIVED = 10032;
    public const TRANSFER_SENT = 10038;
    public const TRANSFER_RECEIVED = 10024;
    public const PURCHASE_NATIONAL = 10045;
    public const PURCHASE_INTL = 10046;
    public const WITHDRAWAL = 10048;
    public const PAYMENT = 10000;
    public const DEPOSIT = 29;
    public const PHONE_RECHARGE = 10005;

    public const REFUND_PURCHASE_INTL = 9796;
    public const REFUND_PAYMENT = 10001;
    public const REFUND_TRANSFER = 10039;
    public const REFUND_IOF = 4521;
    public const REFUND_PHONE_RECHARGE = 10006;

    public const FEE_IOF = 462;
    public const FEE_TRANSFER = 10040;
    public const FEE_WITHDRAWAL = 10050;
    public const FEE_SUBSCRIPTION = 10092;

    public const CREDIT_BACKOFFICE = 10059;

    public const TYPES = [
        self::P2P_SENT,
        self::P2P_RECEIVED,
        self::TRANSFER_SENT,
        self::TRANSFER_RECEIVED,
        self::PURCHASE_NATIONAL,
        self::PURCHASE_INTL,
        self::WITHDRAWAL,
        self::PAYMENT,
        self::DEPOSIT,
        self::PHONE_RECHARGE,
        self::REFUND_PURCHASE_INTL,
        self::REFUND_PAYMENT,
        self::REFUND_TRANSFER,
        self::REFUND_IOF,
        self::REFUND_PHONE_RECHARGE,
        self::FEE_IOF,
        self::FEE_TRANSFER,
        self::FEE_WITHDRAWAL,
        self::FEE_SUBSCRIPTION,
    ];
}
