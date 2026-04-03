<?php

namespace enums;

enum StatusProjeto: string
{
    case PLANEJADO = 'PLANEJADO';
    case EM_ANDAMENTO = 'EM_ANDAMENTO';
    case PAUSADO = 'PAUSADO';
    case CONCLUIDO = 'CONCLUIDO';
    case CANCELADO = 'CANCELADO';
}
