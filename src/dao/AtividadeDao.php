<?php

namespace dao;

use model\Atividade;

class AtividadeDao extends GenericDAO
{
    protected static string $modelClass = Atividade::class;

    public static function buscarTitulo(string $titulo): array
    {
        return self::buscarPor(['titulo' => $titulo]);
    }
}
