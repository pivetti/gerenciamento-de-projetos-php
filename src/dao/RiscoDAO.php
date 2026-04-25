<?php

namespace dao;

use model\Risco;

class RiscoDAO extends GenericDAO
{
    protected static string $modelClass = Risco::class;

    public static function buscarStatus(string $status): array
    {
        return self::buscarPor(['status' => $status]);
    }
}
