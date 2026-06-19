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

        $this->render('recursos/index', [
            'pageTitle' => 'Recursos',
            'recursos' => $recursos,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Recurso(), 'Novo recurso', 'recursos/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $recurso = new Recurso();
            $this->preencherRecurso($recurso);
            RecursoDAO::salvar($recurso);

            $this->flash('success', 'Recurso cadastrado com sucesso.');
            $this->redirect('recursos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel cadastrar o recurso.'));
            $this->redirect('recursos/create');
        }
    }

    public function edit(): void
    {
        $recurso = $this->buscarRecursoPorId((int) ($_GET['id'] ?? 0));

        if (!$recurso) {
            $this->flash('warning', 'Recurso nao encontrado.');
            $this->redirect('recursos');
        }

        $this->renderForm($recurso, 'Editar recurso', 'recursos/update', ['id' => $recurso->getId()]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->requirePost();

            $recurso = $this->buscarRecursoPorId($id);

            if (!$recurso) {
                throw new InvalidArgumentException('Recurso nao encontrado.');
            }

            $this->preencherRecurso($recurso);
            RecursoDAO::salvar($recurso);

            $this->flash('success', 'Recurso atualizado com sucesso.');
            $this->redirect('recursos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar o recurso.'));
            $this->redirect($id > 0 ? 'recursos/edit' : 'recursos', $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $recurso = $this->buscarRecursoPorId((int) ($_GET['id'] ?? 0));

            if (!$recurso) {
                throw new InvalidArgumentException('Recurso nao encontrado.');
            }

            RecursoDAO::deletar($recurso);
            $this->flash('success', 'Recurso excluido com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir o recurso. Verifique se existem custos vinculados a ele.'));
        }

        $this->redirect('recursos');
    }

    private function renderForm(Recurso $recurso, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $erroListagem = null;
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('recursos/form', [
            'pageTitle' => $titulo,
            'recurso' => $recurso,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'projetos' => $projetos,
            'tipoOptions' => TipoRecurso::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    private function preencherRecurso(Recurso $recurso): void
    {
        $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));

        if (!$projeto) {
            throw new InvalidArgumentException('Selecione um projeto para o recurso.');
        }

        $recurso
            ->setNome($this->textInput('nome', true))
            ->setTipo($this->enumInput('tipo', TipoRecurso::cases(), TipoRecurso::HUMANO->value))
            ->setDescricao($this->textInput('descricao'))
            ->setQuantidade($this->intInput('quantidade', 1, 1))
            ->setCustoUnitario($this->floatInput('custoUnitario', 0.0, 0.0))
            ->setProjeto($projeto);
    }

    private function buscarRecursoPorId(int $id): ?Recurso
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $recurso = $this->findById(RecursoDAO::listar(), $id);
            return $recurso instanceof Recurso ? $recurso : null;
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
