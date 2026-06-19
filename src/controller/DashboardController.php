<?php

namespace controller;

use dao\AtividadeDAO;
use dao\ParticipanteDAO;
use dao\ProjetoDAO;
use dao\RecursoDAO;
use dao\RiscoDAO;
use Throwable;

class DashboardController extends BaseController
{
    public function index(): void
    {
        $houveErro = false;

        $totais = [
            'Projetos' => $this->contar(fn () => ProjetoDAO::listar(), $houveErro),
            'Atividades' => $this->contar(fn () => AtividadeDAO::listar(), $houveErro),
            'Participantes' => $this->contar(fn () => ParticipanteDAO::listar(), $houveErro),
            'Recursos' => $this->contar(fn () => RecursoDAO::listar(), $houveErro),
            'Riscos' => $this->contar(fn () => RiscoDAO::listar(), $houveErro),
        ];

        $this->render('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'totais' => $totais,
            'databaseWarning' => $houveErro ? 'Alguns totais nao puderam ser carregados. Confira se o banco esta configurado e com as tabelas criadas.' : null,
        ]);
    }

    private function contar(callable $callback, bool &$houveErro): int
    {
        try {
            return count($callback());
        } catch (Throwable) {
            $houveErro = true;
            return 0;
        }
    }
}
