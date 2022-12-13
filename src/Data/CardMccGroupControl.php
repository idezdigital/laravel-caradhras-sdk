<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Idez\Caradhras\Database\Factories\CardMccGroupControlFactory;

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
