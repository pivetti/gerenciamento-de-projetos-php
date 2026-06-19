<?php

namespace controller;

use dao\AtividadeDAO;
use dao\ParticipanteDAO;
use dao\ProjetoDAO;
use enums\Prioridade;
use enums\StatusAtividade;
use InvalidArgumentException;
use model\Atividade;
use model\Participante;
use model\Projeto;
use Throwable;

class AtividadeController extends BaseController
{
    public function index(): void
    {
        $erroListagem = null;
        $atividades = $this->safeList('as atividades', fn () => AtividadeDAO::listar(), $erroListagem);

        $this->render('atividades/index', [
            'pageTitle' => 'Atividades',
            'atividades' => $atividades,
            'erroListagem' => $erroListagem,
        ]);
    }

    public function create(): void
    {
        $this->renderForm(new Atividade(), 'Nova atividade', 'atividades/store');
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $atividade = new Atividade();
            $this->preencherAtividade($atividade);
            AtividadeDAO::salvar($atividade);

            $this->flash('success', 'Atividade cadastrada com sucesso.');
            $this->redirect('atividades');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel cadastrar a atividade.'));
            $this->redirect('atividades/create');
        }
    }

    public function edit(): void
    {
        $atividade = $this->buscarAtividadePorId((int) ($_GET['id'] ?? 0));

        if (!$atividade) {
            $this->flash('warning', 'Atividade nao encontrada.');
            $this->redirect('atividades');
        }

        $this->renderForm($atividade, 'Editar atividade', 'atividades/update', ['id' => $atividade->getId()]);
    }

    public function update(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $this->requirePost();

            $atividade = $this->buscarAtividadePorId($id);

            if (!$atividade) {
                throw new InvalidArgumentException('Atividade nao encontrada.');
            }

            $this->preencherAtividade($atividade);
            AtividadeDAO::salvar($atividade);

            $this->flash('success', 'Atividade atualizada com sucesso.');
            $this->redirect('atividades');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel atualizar a atividade.'));
            $this->redirect($id > 0 ? 'atividades/edit' : 'atividades', $id > 0 ? ['id' => $id] : []);
        }
    }

    public function delete(): void
    {
        try {
            $this->requirePost();

            $atividade = $this->buscarAtividadePorId((int) ($_GET['id'] ?? 0));

            if (!$atividade) {
                throw new InvalidArgumentException('Atividade nao encontrada.');
            }

            AtividadeDAO::deletar($atividade);
            $this->flash('success', 'Atividade excluida com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->errorMessage($e, 'Nao foi possivel excluir a atividade. Verifique se existem custos vinculados a ela.'));
        }

        $this->redirect('atividades');
    }

    private function renderForm(Atividade $atividade, string $titulo, string $actionRoute, array $actionParams = []): void
    {
        $erroListagem = null;
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);
        $participantes = $this->safeList('os participantes', fn () => ParticipanteDAO::listar(), $erroListagem);

        $this->render('atividades/form', [
            'pageTitle' => $titulo,
            'atividade' => $atividade,
            'actionRoute' => $actionRoute,
            'actionParams' => $actionParams,
            'projetos' => $projetos,
            'participantes' => $participantes,
            'statusOptions' => StatusAtividade::cases(),
            'prioridadeOptions' => Prioridade::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    private function preencherAtividade(Atividade $atividade): void
    {
        $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));
        $responsavel = $this->buscarParticipante((int) ($_POST['responsavelId'] ?? 0));
        $dataInicio = $this->dateInput('dataInicio');
        $prazo = $this->dateInput('prazo');
        $dataConclusao = $this->dateInput('dataConclusao');

        if (!$projeto) {
            throw new InvalidArgumentException('Selecione um projeto para a atividade.');
        }

        if ($dataInicio && $prazo && $prazo < $dataInicio) {
            throw new InvalidArgumentException('O prazo nao pode ser anterior a data inicial.');
        }

        $atividade
            ->setTitulo($this->textInput('titulo', true))
            ->setDescricao($this->textInput('descricao'))
            ->setStatus($this->enumInput('status', StatusAtividade::cases(), StatusAtividade::NAO_INICIADA->value))
            ->setPrioridade($this->enumInput('prioridade', Prioridade::cases(), Prioridade::MEDIA->value))
            ->setDataInicio($dataInicio)
            ->setPrazo($prazo)
            ->setDataConclusao($dataConclusao)
            ->setPercentualConclusao($this->intInput('percentualConclusao', 0, 0, 100))
            ->setProjeto($projeto)
            ->setResponsavel($responsavel);
    }

    private function buscarAtividadePorId(int $id): ?Atividade
    {
        if ($id <= 0) {
            return null;
        }

        try {
            $atividade = $this->findById(AtividadeDAO::listar(), $id);
            return $atividade instanceof Atividade ? $atividade : null;
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

    private function buscarParticipante(int $id): ?Participante
    {
        if ($id <= 0) {
            return null;
        }

        $participante = $this->findById(ParticipanteDAO::listar(), $id);
        return $participante instanceof Participante ? $participante : null;
    }
}
