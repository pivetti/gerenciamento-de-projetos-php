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
        $usuarios = $this->safeList('os usuarios', fn () => UsuarioDAO::listar(), $erroListagem);
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('participantes/index', [
            'pageTitle' => 'Participantes',
            'participantes' => $participantes,
            'usuarios' => $usuarios,
            'projetos' => $projetos,
            'papelOptions' => PapelAcesso::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $usuario = $this->buscarUsuario((int) ($_POST['usuarioId'] ?? 0));
            $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));

            if (!$usuario || !$projeto) {
                throw new InvalidArgumentException('Selecione usuario e projeto para cadastrar o participante.');
            }

            $participante = (new Participante())
                ->setUsuario($usuario)
                ->setProjeto($projeto)
                ->setFuncaoNoProjeto($this->textInput('funcaoNoProjeto', true))
                ->setPapelAcesso($this->validarEnum('papelAcesso', PapelAcesso::cases(), PapelAcesso::EXECUTOR->value))
                ->setAtivo(isset($_POST['ativo']));

            ParticipanteDAO::salvar($participante);

            $this->flash('success', 'Participante cadastrado com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->mensagemErro($e, 'Nao foi possivel cadastrar o participante.'));
        }

        $this->redirect('participantes');
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

    private function validarEnum(string $campo, array $cases, string $default): string
    {
        $valor = (string) ($_POST[$campo] ?? $default);
        $permitidos = array_map(fn ($case) => $case->value, $cases);

        if (!in_array($valor, $permitidos, true)) {
            throw new InvalidArgumentException('Opcao invalida informada.');
        }

        return $valor;
    }

    private function mensagemErro(Throwable $e, string $fallback): string
    {
        return $e instanceof InvalidArgumentException ? $e->getMessage() : $fallback;
    }
}
