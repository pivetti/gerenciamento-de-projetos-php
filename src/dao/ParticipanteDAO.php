<?php

namespace dao;

use model\Participante;

class ParticipanteDAO extends GenericDAO
{
    protected static string $modelClass = Participante::class;

    public static function buscarAtivos(): array
    {
        return self::buscarPor(['ativo' => true]);
    }
}
