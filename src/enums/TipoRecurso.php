<?php

namespace enums;

enum TipoRecurso: string
{
    case HUMANO = 'HUMANO';
    case MATERIAL = 'MATERIAL';
    case TECNOLOGICO = 'TECNOLOGICO';
    case FINANCEIRO = 'FINANCEIRO';
    case SERVICO = 'SERVICO';
}
