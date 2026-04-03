<?php

namespace enums;

enum StatusRisco: string
{
    case IDENTIFICADO = 'IDENTIFICADO';
    case EM_ANALISE = 'EM_ANALISE';
    case EM_TRATAMENTO = 'EM_TRATAMENTO';
    case MITIGADO = 'MITIGADO';
    case ENCERRADO = 'ENCERRADO';
}
