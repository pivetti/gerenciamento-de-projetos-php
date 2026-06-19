<?php

namespace controller;

use dao\ProjetoDAO;
use dao\RecursoDAO;
use enums\TipoRecurso;
use InvalidArgumentException;
use model\Projeto;
use model\Recurso;
use Throwable;

class RecursoController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $recursos = $this->safeList('os recursos', fn () => RecursoDAO::listar(), $erroListagem);
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('recursos/index', [
            'pageTitle' => 'Recursos',
            'recursos' => $recursos,
            'projetos' => $projetos,
            'tipoOptions' => TipoRecurso::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));

            if (!$projeto) {
                throw new InvalidArgumentException('Selecione um projeto para o recurso.');
            }

            $recurso = (new Recurso())
                ->setNome($this->textInput('nome', true))
                ->setTipo($this->validarEnum('tipo', TipoRecurso::cases(), TipoRecurso::HUMANO->value))
                ->setDescricao($this->textInput('descricao'))
                ->setQuantidade($this->intInput('quantidade', 1, 1))
                ->setCustoUnitario($this->floatInput('custoUnitario', 0.0, 0.0))
                ->setProjeto($projeto);

            RecursoDAO::salvar($recurso);

            $this->flash('success', 'Recurso cadastrado com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->mensagemErro($e, 'Nao foi possivel cadastrar o recurso.'));
        }

        $this->redirect('recursos');
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
