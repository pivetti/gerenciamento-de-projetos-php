<?php

namespace dao;

use model\Custo;

class CustoDAO extends GenericDAO
{
    protected static string $modelClass = Custo::class;

    public static function buscarTipo(string $tipo): array
    {
        return self::buscarPor(['tipo' => $tipo]);
    }
}
