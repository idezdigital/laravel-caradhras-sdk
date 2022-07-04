<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CardSettings
 * @package App\Models\Caradhras
 */
class CardSettings extends Data
{
    use HasFactory;

    public ?int $id = null;
    public bool $permiteEcommerce = true;
    public bool $permiteSaque = true;
    public bool $permiteWallet = true;
    public bool $permiteControleMCC = true;
    public bool $permiteCompraInternacional = true;
    public bool $permiteTarjaMagnetica = true;
    public bool $permiteContactless = true;
    public float $limiteContactlessSemSenha = 100;
    public string $funcaoAtiva = 'DEBITO';
}
