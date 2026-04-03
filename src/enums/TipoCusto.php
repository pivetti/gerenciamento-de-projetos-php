<?php

namespace enums;

enum TipoCusto: string
{
    case PLANEJADO = 'PLANEJADO';
    case OPERACIONAL = 'OPERACIONAL';
    case AQUISICAO = 'AQUISICAO';
    case RH = 'RH';
    case IMPREVISTO = 'IMPREVISTO';
}
