<?php

namespace enums;

enum PerfilUsuario: string
{
    case ADMINISTRADOR = 'ADMINISTRADOR';
    case GERENTE_PROJETO = 'GERENTE_PROJETO';
    case ANALISTA = 'ANALISTA';
    case MEMBRO_EQUIPE = 'MEMBRO_EQUIPE';
    case STAKEHOLDER = 'STAKEHOLDER';
}
