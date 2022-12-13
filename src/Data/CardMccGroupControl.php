<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Database\Factories\CardMccGroupControlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardMccGroupControl extends Struct
{
    use HasFactory;

    protected static function newFactory()
    {
        return CardMccGroupControlFactory::new();
    }

    public int $id;
    public int $idCartao;
    public object $grupoMCC;
}
