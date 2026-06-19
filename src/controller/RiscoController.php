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

        $this->render('riscos/index', [
            'pageTitle' => 'Riscos',
            'riscos' => $riscos,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Risco(), 'Novo risco', 'riscos/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $risco = new Risco();
            $this->preencherRisco($risco);
            RiscoDAO::salvar($risco);

            $this->flash('success', 'Risco cadastrado com sucesso.');
            $this->redirect('riscos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel cadastrar o risco.'));
            $this->redirect('riscos/create');
        }
    }

    public function edit(): void
    {
        $risco = $this->buscarRiscoPorId((int) ($_GET['id'] ?? 0));

        if (!$risco) {
            $this->flash('warning', 'Risco nao encontrado.');
            $this->redirect('riscos');
        }

        $this->renderForm($risco, 'Editar risco', 'riscos/update', ['id' => $risco->getId()]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->requirePost();

            $risco = $this->buscarRiscoPorId($id);

            if (!$risco) {
                throw new InvalidArgumentException('Risco nao encontrado.');
            }

            $this->preencherRisco($risco);
            RiscoDAO::salvar($risco);

            $this->flash('success', 'Risco atualizado com sucesso.');
            $this->redirect('riscos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar o risco.'));
            $this->redirect($id > 0 ? 'riscos/edit' : 'riscos', $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $risco = $this->buscarRiscoPorId((int) ($_GET['id'] ?? 0));

            if (!$risco) {
                throw new InvalidArgumentException('Risco nao encontrado.');
            }

            RiscoDAO::deletar($risco);
            $this->flash('success', 'Risco excluido com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir o risco.'));
        }

        $this->redirect('riscos');
    }

    private function renderForm(Risco $risco, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $erroListagem = null;
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('riscos/form', [
            'pageTitle' => $titulo,
            'risco' => $risco,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'projetos' => $projetos,
            'categoriaOptions' => CategoriaRisco::cases(),
            'statusOptions' => StatusRisco::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    private function preencherRisco(Risco $risco): void
    {
        $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));
        $probabilidade = $this->intInput('probabilidade', 1, 1, 5);
        $impacto = $this->intInput('impacto', 1, 1, 5);

        if (!$projeto) {
            throw new InvalidArgumentException('Selecione um projeto para o risco.');
        }

        $risco
            ->setTitulo($this->textInput('titulo', true))
            ->setDescricao($this->textInput('descricao'))
            ->setCategoria($this->enumInput('categoria', CategoriaRisco::cases(), CategoriaRisco::ESCOPO->value))
            ->setProbabilidade($probabilidade)
            ->setImpacto($impacto)
            ->setCriticidade($probabilidade * $impacto)
            ->setStatus($this->enumInput('status', StatusRisco::cases(), StatusRisco::IDENTIFICADO->value))
            ->setEstrategiaResposta($this->textInput('estrategiaResposta'))
            ->setPlanoMitigacao($this->textInput('planoMitigacao'))
            ->setProjeto($projeto);
    }

    private function buscarRiscoPorId(int $id): ?Risco
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $risco = $this->findById(RiscoDAO::listar(), $id);
            return $risco instanceof Risco ? $risco : null;
        } catch (Throwable $e) {
            $this->logException($e);
            return null;
        }
    }

    private function buscarProjeto(int $id): ?Projeto
    {
        $projeto = $this->findById(ProjetoDAO::listar(), $id);
        return $projeto instanceof Projeto ? $projeto : null;
    }
}
