<?php

namespace enums;

enum PapelAcesso: string
{
    case ADMINISTRADOR_PROJETO = 'ADMINISTRADOR_PROJETO';
    case COORDENADOR = 'COORDENADOR';
    case EXECUTOR = 'EXECUTOR';
    case VISUALIZADOR = 'VISUALIZADOR';
}
