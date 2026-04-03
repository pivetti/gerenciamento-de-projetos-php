<?php

namespace dao;

use model\Projeto;

class ProjetoDao extends GenericDAO
{
    protected static string $modelClass = Projeto::class;

    public static function buscarNome(string $nome): array
    {
        return self::buscarPor(['nome' => $nome]);
    }
}
