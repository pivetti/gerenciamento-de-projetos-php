<?php

namespace controller;

use dao\ProjetoDAO;
use dao\RiscoDAO;
use enums\CategoriaRisco;
use enums\StatusRisco;
use InvalidArgumentException;
use model\Projeto;
use model\Risco;
use Throwable;

class RiscoController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $riscos = $this->safeList('os riscos', fn () => RiscoDAO::listar(), $erroListagem);
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('riscos/index', [
            'pageTitle' => 'Riscos',
            'riscos' => $riscos,
            'projetos' => $projetos,
            'categoriaOptions' => CategoriaRisco::cases(),
            'statusOptions' => StatusRisco::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));

            if (!$projeto) {
                throw new InvalidArgumentException('Selecione um projeto para o risco.');
            }

            $probabilidade = $this->intInput('probabilidade', 1, 1, 5);
            $impacto = $this->intInput('impacto', 1, 1, 5);

            $risco = (new Risco())
                ->setTitulo($this->textInput('titulo', true))
                ->setDescricao($this->textInput('descricao'))
                ->setCategoria($this->validarEnum('categoria', CategoriaRisco::cases(), CategoriaRisco::ESCOPO->value))
                ->setProbabilidade($probabilidade)
                ->setImpacto($impacto)
                ->setCriticidade($probabilidade * $impacto)
                ->setStatus($this->validarEnum('status', StatusRisco::cases(), StatusRisco::IDENTIFICADO->value))
                ->setEstrategiaResposta($this->textInput('estrategiaResposta'))
                ->setPlanoMitigacao($this->textInput('planoMitigacao'))
                ->setProjeto($projeto);

            RiscoDAO::salvar($risco);

            $this->flash('success', 'Risco cadastrado com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->mensagemErro($e, 'Nao foi possivel cadastrar o risco.'));
        }

        $this->redirect('riscos');
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
