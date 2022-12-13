<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Database\Factories\IndividualFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property  int $id
 * @property  string $nome
 * @property  string $tipo
 * @property  string $cpf
 * @property  string $cnpj
 * @property  string $dataNascimento
 * @property  string $sexo
 * @property  string $numeroIdentidade
 * @property  string $orgaoExpedidorIdentidade
 * @property  string $unidadeFederativaIdentidade
 * @property  ?string $dataEmissaoIdentidade
 * @property  bool $flagDeficienteVisual
 */
class Individual extends Data
{
    use HasFactory;

    protected static function newFactory()
    {
        return IndividualFactory::new();
    }
}
