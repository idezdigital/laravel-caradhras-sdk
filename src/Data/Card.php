<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Database\Factories\CardFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $flagTitular
 * @property int $idPessoa
 * @property int $sequencialCartao
 * @property int $idConta
 * @property int $idStatus
 * @property string $dataStatus
 * @property int $idEstagio
 * @property string $dataEstagio
 * @property int $numeroBin
 * @property string $numeroCartao
 * @property string $numeroCartaoHash
 * @property string $numeroCartaoCriptografado
 * @property string $dataEmissao
 * @property string $dataValidade
 * @property int $cartaoVirtual
 * @property string $impressaoAvulsa
 * @property string $dataImpressao
 * @property string $nomeArquivoImpressao
 * @property int $idProduto
 * @property string $nomeImpresso
 * @property string $codigoDesbloqueio
 * @property string $tipoPortador
 * @property int $idStatusCartao
 * @property string $dataStatusCartao
 * @property int $idEstagioCartao
 * @property string $dataEstagioCartao
 * @property string $dataGeracao
 * @property int $flagVirtual
 * @property string $flagImpressaoOrigemComercial
 * @property string $arquivoImpressao
 * @property int $portador
 * @property string $flagCartaoMifare
 * @property string $idImagem
 * @property string $descricaoTipoCartao
 * @property int tipoCartao
 * @property string $idMifare
 * @property string $matriculaMifare
 */
class Card extends Data
{
    use HasFactory;

    protected static function newFactory()
    {
        return CardFactory::new();
    }
}
