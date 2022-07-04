<?php

namespace Idez\Caradhras\Enums\Cards;

enum PierTransactionType: string
{
    case Purchase = 'TransacaoCompra';
    case Withdrawal = 'TransacaoSaque';
    case Check = 'TransacaoConsultaStatusConta';
    case Refund = 'TransacaoCancelamento';
}
