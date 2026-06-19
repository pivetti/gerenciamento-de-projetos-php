<?php

namespace controller;

use dao\UsuarioDAO;
use enums\PerfilUsuario;
use InvalidArgumentException;
use model\Usuario;
use Throwable;

class UsuarioController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $usuarios = $this->safeList('os usuarios', fn () => UsuarioDAO::listar(), $erroListagem);

        $this->render('usuarios/index', [
            'pageTitle' => 'Usuarios',
            'usuarios' => $usuarios,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Usuario(), 'Novo usuario', 'usuarios/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $usuario = new Usuario();
            $this->preencherUsuario($usuario, true);
            UsuarioDAO::salvar($usuario);

            $this->flash('success', 'Usuario cadastrado com sucesso.');
            $this->redirect('usuarios');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel salvar o usuario.'));
            $this->redirect('usuarios/create');
        }
    }

    public function edit(): void
    {
        $usuario = $this->buscarUsuarioPorId((int) ($_GET['id'] ?? 0));

        if (!$usuario) {
            $this->flash('warning', 'Usuario nao encontrado.');
            $this->redirect('usuarios');
        }

        $this->renderForm($usuario, 'Editar usuario', 'usuarios/update', ['id' => $usuario->getId()]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->requirePost();

            $usuario = $this->buscarUsuarioPorId($id);

            if (!$usuario) {
                throw new InvalidArgumentException('Usuario nao encontrado.');
            }

            $this->preencherUsuario($usuario, false);
            UsuarioDAO::salvar($usuario);

            $this->flash('success', 'Usuario atualizado com sucesso.');
            $this->redirect('usuarios');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar o usuario.'));
            $this->redirect($id > 0 ? 'usuarios/edit' : 'usuarios', $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $usuario = $this->buscarUsuarioPorId((int) ($_GET['id'] ?? 0));

            if (!$usuario) {
                throw new InvalidArgumentException('Usuario nao encontrado.');
            }

            UsuarioDAO::deletar($usuario);
            $this->flash('success', 'Usuario excluido com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir o usuario. Verifique se existem participantes vinculados a ele.'));
        }

        $this->redirect('usuarios');
    }

    private function renderForm(Usuario $usuario, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $this->render('usuarios/form', [
            'pageTitle' => $titulo,
            'usuario' => $usuario,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'perfilOptions' => PerfilUsuario::cases(),
        ]);
    }

    private function preencherUsuario(Usuario $usuario, bool $senhaObrigatoria): void
    {
        $senha = $this->textInput('senha', $senhaObrigatoria);

        $usuario
            ->setNome($this->textInput('nome', true))
            ->setEmail($this->textInput('email', true))
            ->setTelefone($this->textInput('telefone'))
            ->setPerfil($this->enumInput('perfil', PerfilUsuario::cases(), PerfilUsuario::MEMBRO_EQUIPE->value));

        if ($senha !== null) {
            $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
        }
    }

    private function buscarUsuarioPorId(int $id): ?Usuario
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $usuario = $this->findById(UsuarioDAO::listar(), $id);
            return $usuario instanceof Usuario ? $usuario : null;
        } catch (Throwable $e) {
            $this->logException($e);
            return null;
        }
    }
}
