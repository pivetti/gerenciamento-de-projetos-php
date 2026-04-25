<?php

namespace dao;

use model\Recurso;

class RecursoDAO extends GenericDAO
{
    protected static string $modelClass = Recurso::class;

    public static function buscarNome(string $nome): array
    {
        return self::buscarPor(['nome' => $nome]);
    }
}
