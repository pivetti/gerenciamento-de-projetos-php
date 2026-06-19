<?php

namespace controller;

use dao\ParticipanteDAO;
use dao\ProjetoDAO;
use dao\UsuarioDAO;
use enums\PapelAcesso;
use InvalidArgumentException;
use model\Participante;
use model\Projeto;
use model\Usuario;
use Throwable;

class ParticipanteController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $participantes = $this->safeList('os participantes', fn () => ParticipanteDAO::listar(), $erroListagem);

        $this->render('participantes/index', [
            'pageTitle' => 'Participantes',
            'participantes' => $participantes,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Participante(), 'Novo participante', 'participantes/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $participante = new Participante();
            $this->preencherParticipante($participante);
            ParticipanteDAO::salvar($participante);

            $this->flash('success', 'Participante cadastrado com sucesso.');
            $this->redirect('participantes');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel cadastrar o participante.'));
            $this->redirect('participantes/create');
        }
    }

    public function edit(): void
    {
        $participante = $this->buscarParticipantePorId((int) ($_GET['id'] ?? 0));

        if (!$participante) {
            $this->flash('warning', 'Participante nao encontrado.');
            $this->redirect('participantes');
        }

        $this->renderForm($participante, 'Editar participante', 'participantes/update', ['id' => $participante->getId()]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->requirePost();

            $participante = $this->buscarParticipantePorId($id);

            if (!$participante) {
                throw new InvalidArgumentException('Participante nao encontrado.');
            }

            $this->preencherParticipante($participante);
            ParticipanteDAO::salvar($participante);

            $this->flash('success', 'Participante atualizado com sucesso.');
            $this->redirect('participantes');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar o participante.'));
            $this->redirect($id > 0 ? 'participantes/edit' : 'participantes', $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $participante = $this->buscarParticipantePorId((int) ($_GET['id'] ?? 0));

            if (!$participante) {
                throw new InvalidArgumentException('Participante nao encontrado.');
            }

            ParticipanteDAO::deletar($participante);
            $this->flash('success', 'Participante excluido com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir o participante. Verifique se existem atividades vinculadas a ele.'));
        }

        $this->redirect('participantes');
    }

    private function renderForm(Participante $participante, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $erroListagem = null;
        $usuarios = $this->safeList('os usuarios', fn () => UsuarioDAO::listar(), $erroListagem);
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('participantes/form', [
            'pageTitle' => $titulo,
            'participante' => $participante,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'usuarios' => $usuarios,
            'projetos' => $projetos,
            'papelOptions' => PapelAcesso::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    private function preencherParticipante(Participante $participante): void
    {
        $usuario = $this->buscarUsuario((int) ($_POST['usuarioId'] ?? 0));
        $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));

        if (!$usuario || !$projeto) {
            throw new InvalidArgumentException('Selecione usuario e projeto para cadastrar o participante.');
        }

        $participante
            ->setUsuario($usuario)
            ->setProjeto($projeto)
            ->setFuncaoNoProjeto($this->textInput('funcaoNoProjeto', true))
            ->setPapelAcesso($this->enumInput('papelAcesso', PapelAcesso::cases(), PapelAcesso::EXECUTOR->value))
            ->setAtivo(isset($_POST['ativo']));
    }

    private function buscarParticipantePorId(int $id): ?Participante
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $participante = $this->findById(ParticipanteDAO::listar(), $id);
            return $participante instanceof Participante ? $participante : null;
        } catch (Throwable $e) {
            $this->logException($e);
            return null;
        }
    }

    private function buscarUsuario(int $id): ?Usuario
    {
        $usuario = $this->findById(UsuarioDAO::listar(), $id);
        return $usuario instanceof Usuario ? $usuario : null;
    }

    private function buscarProjeto(int $id): ?Projeto
    {
        $projeto = $this->findById(ProjetoDAO::listar(), $id);
        return $projeto instanceof Projeto ? $projeto : null;
    }
}
