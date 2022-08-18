<?php

namespace Idez\Caradhras\Enums\Pix;

use App\Models\Company;
use App\Models\Person;
use Illuminate\Support\Str;

enum DictKeyType: string
{
    case Evp = 'evp';
    case Email = 'email';
    case Phone = 'phoneNumber';
    case Document = 'nationalRegistration';

    public function label(Company|Person $holder)
    {
        return match ($this) {
            self::Document => $holder::class === Person::class ? 'CPF' : 'CNPJ',
            self::Email => 'E-mail',
            self::Phone => 'Número de Telefone',
            self::Evp => 'Chave Aleatória'
        };
    }

    public static function fromCaradhras($value)
    {
        return self::from(Str::of($value)->lower());
    }

    public function toCaradhras()
    {
        return Str::of($this->value)->upper();
    }

    public static function claimable()
    {
        return [
            self::Phone,
            self::Email,
            self::Document,
        ];
    }
}
