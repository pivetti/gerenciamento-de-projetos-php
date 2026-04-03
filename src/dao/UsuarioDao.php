<?php

namespace dao;

use model\Usuario;

class UsuarioDao extends GenericDAO
{
    protected static string $modelClass = Usuario::class;

    public static function buscarEmail(string $email): array
    {
        return self::buscarPor(['email' => $email]);
    }
}
