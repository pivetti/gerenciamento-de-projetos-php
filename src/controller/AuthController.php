<?php

namespace controller;

use dao\UsuarioDAO;
use enums\PerfilUsuario;
use InvalidArgumentException;
use model\Usuario;
use Throwable;

class AuthController extends BaseController
{
    public function loginForm(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/login', [
            'pageTitle' => 'Login',
            'layout' => 'auth',
        ]);
    }

    public function cadastroForm(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/cadastro', [
            'pageTitle' => 'Cadastro',
            'layout' => 'auth',
            'perfilOptions' => PerfilUsuario::cases(),
        ]);
    }

    public function login(): void
    {
        try {
            $this->requirePost();

            $email = $this->textInput('email', true);
            $senha = $this->textInput('senha', true);
            $usuario = $this->buscarUsuarioPorEmail($email);

            if (!$usuario || !$this->senhaValida($usuario, $senha)) {
                throw new InvalidArgumentException('Email ou senha invalidos.');
            }

            $this->registrarUsuarioNaSessao($usuario);
            $this->flash('success', 'Login realizado com sucesso.');
            $this->redirect('dashboard');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel realizar login.'));
            $this->redirect('login');
        }
    }

    public function cadastro(): void
    {
        try {
            $this->requirePost();

            $nome = $this->textInput('nome', true);
            $email = $this->textInput('email', true);
            $senha = $this->textInput('senha', true);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Informe um email valido.');
            }

            if (strlen($senha) < 6) {
                throw new InvalidArgumentException('A senha deve ter pelo menos 6 caracteres.');
            }

            if ($this->buscarUsuarioPorEmail($email)) {
                throw new InvalidArgumentException('Ja existe um usuario cadastrado com este email.');
            }

            $usuario = (new Usuario())
                ->setNome($nome)
                ->setEmail($email)
                ->setSenha(password_hash($senha, PASSWORD_DEFAULT))
                ->setTelefone($this->textInput('telefone'))
                ->setPerfil($this->enumInput('perfil', PerfilUsuario::cases(), PerfilUsuario::MEMBRO_EQUIPE->value));

            UsuarioDAO::salvar($usuario);

            $this->flash('success', 'Cadastro realizado com sucesso. Agora faca login.');
            $this->redirect('login');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel realizar o cadastro.'));
            $this->redirect('cadastro');
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
        $this->flash('success', 'Voce saiu do sistema.');
        $this->redirect('login');
    }

    private function buscarUsuarioPorEmail(string $email): ?Usuario
    {
        $usuarios = UsuarioDAO::buscarEmail($email);
        $usuario = $usuarios[0] ?? null;

        return $usuario instanceof Usuario ? $usuario : null;
    }

    private function senhaValida(Usuario $usuario, string $senha): bool
    {
        $senhaArmazenada = $usuario->getSenha();

        if (password_verify($senha, $senhaArmazenada)) {
            return true;
        }

        $info = password_get_info($senhaArmazenada);
        $senhaLegadaEmTexto = $info['algo'] === 0 && hash_equals($senhaArmazenada, $senha);

        if ($senhaLegadaEmTexto) {
            $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
            UsuarioDAO::salvar($usuario);
            return true;
        }

        return false;
    }

    private function registrarUsuarioNaSessao(Usuario $usuario): void
    {
        session_regenerate_id(true);

        $_SESSION['usuario_logado'] = [
            'id' => $usuario->getId(),
            'nome' => $usuario->getNome(),
            'email' => $usuario->getEmail(),
            'perfil' => $usuario->getPerfil(),
        ];
    }
}
