<?php

namespace enums;

enum CategoriaRisco: string
{
    case ESCOPO = 'ESCOPO';
    case PRAZO = 'PRAZO';
    case CUSTO = 'CUSTO';
    case QUALIDADE = 'QUALIDADE';
    case RECURSOS = 'RECURSOS';
    case TECNOLOGIA = 'TECNOLOGIA';
    case COMUNICACAO = 'COMUNICACAO';
    case EXTERNO = 'EXTERNO';
}
