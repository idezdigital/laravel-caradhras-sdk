<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int idTipoTransacaoNaoProcessada
 * @property int descricaoTipoTransacaoNaoProcessada
 * @property string descricaoAbreviada
 * @property string cartaoMascarado
 * @property int idConta
 * @property string nomePortador
 * @property string|null dataOrigem,
 * @property string|null dataFaturamento
 * @property string|null dataVencimentoReal
 * @property null modoEntradaTransacao
 * @property null taxaEmbarque
 * @property null valorEntrada
 * @property float valorBRL
 * @property float valorUSD
 * @property float cotacaoUSD
 * @property string|null dataCotacaoUSD
 * @property string codigoMoedaOrigem
 * @property string codigoMoedaDestino
 * @property int|null codigoAutorizacao
 * @property int|null codigoReferencia
 * @property int|null codigoTerminal
 * @property int|null codigoMCC
 * @property int|null grupoMCC
 * @property string|null grupoDescricaoMCC
 * @property int idEstabelecimento
 * @property string|null nomeEstabelecimento
 * @property string|null nomeFantasiaEstabelecimento
 * @property string|null localidadeEstabelecimento
 * @property null plano
 * @property null parcela
 * @property string detalhesTransacao
 * @property int flagCredito
 * @property int flagFaturado
 * @property int flagEstorno
 * @property int idTransacaoEstorno
 * @property float valorTAC
 * @property float valorIOF
 * @property float valorCompraMoedaEstrangeira
 * @property string moedaEstrangeira
 */
class UnprocessedTransaction extends Data
{
    use HasFactory;

    public const TYPE_PIER = 3;

    public const TYPE_ADJUSTMENT = 1;
}
