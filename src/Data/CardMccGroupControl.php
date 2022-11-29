<?php

namespace Idez\Caradhras\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardMccGroupControl extends Struct
{
    use HasFactory;

    public int $id;
    public int $idCartao;
    public object $grupoMCC;
}
