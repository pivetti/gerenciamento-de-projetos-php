<?php

namespace controller;

use dao\AtividadeDAO;
use dao\CustoDAO;
use dao\ProjetoDAO;
use dao\RecursoDAO;
use enums\TipoCusto;
use InvalidArgumentException;
use model\Atividade;
use model\Custo;
use model\Projeto;
use model\Recurso;
use Throwable;

class CustoController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $custos = $this->safeList('os custos', fn () => CustoDAO::listar(), $erroListagem);

        $this->render('custos/index', [
            'pageTitle' => 'Custos',
            'custos' => $custos,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Custo(), 'Novo custo', 'custos/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $custo = new Custo();
            $this->preencherCusto($custo);
            CustoDAO::salvar($custo);

            $this->flash('success', 'Custo cadastrado com sucesso.');
            $this->redirect('custos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel cadastrar o custo.'));
            $this->redirect('custos/create');
        }
    }

    public function edit(): void
    {
        $custo = $this->buscarCustoPorId((int) ($_GET['id'] ?? 0));

        if (!$custo) {
            $this->flash('warning', 'Custo nao encontrado.');
            $this->redirect('custos');
        }

        $this->renderForm($custo, 'Editar custo', 'custos/update', ['id' => $custo->getId()]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->requirePost();

            $custo = $this->buscarCustoPorId($id);

            if (!$custo) {
                throw new InvalidArgumentException('Custo nao encontrado.');
            }

            $this->preencherCusto($custo);
            CustoDAO::salvar($custo);

            $this->flash('success', 'Custo atualizado com sucesso.');
            $this->redirect('custos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar o custo.'));
            $this->redirect($id > 0 ? 'custos/edit' : 'custos', $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $custo = $this->buscarCustoPorId((int) ($_GET['id'] ?? 0));

            if (!$custo) {
                throw new InvalidArgumentException('Custo nao encontrado.');
            }

            CustoDAO::deletar($custo);
            $this->flash('success', 'Custo excluido com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir o custo.'));
        }

        $this->redirect('custos');
    }

    private function renderForm(Custo $custo, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $erroListagem = null;
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);
        $atividades = $this->safeList('as atividades', fn () => AtividadeDAO::listar(), $erroListagem);
        $recursos = $this->safeList('os recursos', fn () => RecursoDAO::listar(), $erroListagem);

        $this->render('custos/form', [
            'pageTitle' => $titulo,
            'custo' => $custo,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'projetos' => $projetos,
            'atividades' => $atividades,
            'recursos' => $recursos,
            'tipoOptions' => TipoCusto::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    private function preencherCusto(Custo $custo): void
    {
        $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));
        $atividade = $this->buscarAtividade((int) ($_POST['atividadeId'] ?? 0));
        $recurso = $this->buscarRecurso((int) ($_POST['recursoId'] ?? 0));

        if (!$projeto) {
            throw new InvalidArgumentException('Selecione um projeto para o custo.');
        }

        $custo
            ->setDescricao($this->textInput('descricao', true))
            ->setTipo($this->enumInput('tipo', TipoCusto::cases(), TipoCusto::PLANEJADO->value))
            ->setValorPrevisto($this->floatInput('valorPrevisto', 0.0, 0.0))
            ->setValorReal($this->nullableFloatInput('valorReal', 0.0))
            ->setDataLancamento($this->dateInput('dataLancamento'))
            ->setProjeto($projeto)
            ->setAtividade($atividade)
            ->setRecurso($recurso);
    }

    private function buscarCustoPorId(int $id): ?Custo
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $custo = $this->findById(CustoDAO::listar(), $id);
            return $custo instanceof Custo ? $custo : null;
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

    private function buscarAtividade(int $id): ?Atividade
    {
        if ($id <= 0) {
            return null;
        }

        $atividade = $this->findById(AtividadeDAO::listar(), $id);
        return $atividade instanceof Atividade ? $atividade : null;
    }

    private function buscarRecurso(int $id): ?Recurso
    {
        if ($id <= 0) {
            return null;
        }

        $recurso = $this->findById(RecursoDAO::listar(), $id);
        return $recurso instanceof Recurso ? $recurso : null;
    }
}
