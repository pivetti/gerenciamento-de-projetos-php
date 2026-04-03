<?php

namespace enums;

enum StatusAtividade: string
{
    case NAO_INICIADA = 'NAO_INICIADA';
    case EM_ANDAMENTO = 'EM_ANDAMENTO';
    case BLOQUEADA = 'BLOQUEADA';
    case CONCLUIDA = 'CONCLUIDA';
    case CANCELADA = 'CANCELADA';
}
