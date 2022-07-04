<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property  string  $numeroCartao
 * @property  string  $dataValidade
 * @property  string  $cvv2
 * @property  string  $nomePlastico
 * @property  int  $idConta
 * @property  int  $idCartao
 * @property  int  $numeroAgencia
 * @property  string  $numeroContaCorente
 * @property  int  $idStatusConta
 * @property  string  $statusConta
 * @property  int  $idStatusCartao
 * @property  string  $statusCartao
 * @property  null|int  $idMifare
 * @property  null|string  $matriculaMifare
 * @property  int  $idProduto
 * @property  int  $flagVirtual
 */
class CardDetails extends Data
{
    use HasFactory;
}
