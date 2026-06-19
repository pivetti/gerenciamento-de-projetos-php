<?php

namespace controller;

use dao\ProjetoDAO;
use enums\Prioridade;
use enums\StatusProjeto;
use InvalidArgumentException;
use model\Projeto;
use Throwable;

class ProjetoController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);

        $this->render('projetos/index', [
            'pageTitle' => 'Projetos',
            'projetos' => $projetos,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Projeto(), 'Novo projeto', 'projetos/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $projeto = new Projeto();
            $this->preencherProjeto($projeto);
            ProjetoDAO::salvar($projeto);

            $this->flash('success', 'Projeto cadastrado com sucesso.');
            $this->redirect('projetos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel salvar o projeto. Verifique os dados e tente novamente.'));
            $this->redirect('projetos/create');
        }
    }

    public function edit(): void
    {
        $projeto = $this->buscarProjetoPorId((int) ($_GET['id'] ?? 0));

        if (!$projeto) {
            $this->flash('warning', 'Projeto nao encontrado.');
            $this->redirect('projetos');
        }

        $this->renderForm($projeto, 'Editar projeto', 'projetos/update', ['id' => $projeto->getId()]);
    }

    public function update(): void
    {
        try {
            $this->requirePost();

            $id = (int) ($_GET['id'] ?? 0);
            $projeto = $this->buscarProjetoPorId($id);

            if (!$projeto) {
                throw new InvalidArgumentException('Projeto nao encontrado.');
            }

            $this->preencherProjeto($projeto);
            ProjetoDAO::salvar($projeto);

            $this->flash('success', 'Projeto atualizado com sucesso.');
            $this->redirect('projetos');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar o projeto. Verifique os dados e tente novamente.'));
            $this->redirect(isset($id) && $id > 0 ? 'projetos/edit' : 'projetos', isset($id) && $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $id = (int) ($_GET['id'] ?? 0);
            $projeto = $this->buscarProjetoPorId($id);

            if (!$projeto) {
                throw new InvalidArgumentException('Projeto nao encontrado.');
            }

            ProjetoDAO::deletar($projeto);

            $this->flash('success', 'Projeto excluido com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir o projeto. Verifique se existem registros vinculados a ele.'));
        }

        $this->redirect('projetos');
    }

    private function renderForm(Projeto $projeto, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $this->render('projetos/form', [
            'pageTitle' => $titulo,
            'projeto' => $projeto,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'statusOptions' => StatusProjeto::cases(),
            'prioridadeOptions' => Prioridade::cases(),
        ]);
    }

    private function preencherProjeto(Projeto $projeto): void
    {
        $nome = $this->textInput('nome', true);
        $dataInicio = $this->dateInput('dataInicio');
        $dataFim = $this->dateInput('dataFim');

        if ($dataInicio && $dataFim && $dataFim < $dataInicio) {
            throw new InvalidArgumentException('A data final nao pode ser anterior a data inicial.');
        }

        $projeto
            ->setNome($nome)
            ->setDescricao($this->textInput('descricao'))
            ->setObjetivo($this->textInput('objetivo'))
            ->setStatus($this->validarEnum('status', StatusProjeto::cases(), StatusProjeto::PLANEJADO->value))
            ->setPrioridade($this->validarEnum('prioridade', Prioridade::cases(), Prioridade::MEDIA->value))
            ->setDataInicio($dataInicio)
            ->setDataFim($dataFim)
            ->setOrcamentoPrevisto($this->floatInput('orcamentoPrevisto', 0.0, 0.0))
            ->setPercentualConcluido($this->intInput('percentualConcluido', 0, 0, 100));
    }

    private function buscarProjetoPorId(int $id): ?Projeto
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $projeto = $this->findById(ProjetoDAO::listar(), $id);
            return $projeto instanceof Projeto ? $projeto : null;
        } catch (Throwable) {
            return null;
        }
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
}
