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
        $projetos = $this->safeList('os projetos', fn () => ProjetoDAO::listar(), $erroListagem);
        $participantes = $this->safeList('os participantes', fn () => ParticipanteDAO::listar(), $erroListagem);

        $this->render('atividades/index', [
            'pageTitle' => 'Atividades',
            'atividades' => $atividades,
            'projetos' => $projetos,
            'participantes' => $participantes,
            'statusOptions' => StatusAtividade::cases(),
            'prioridadeOptions' => Prioridade::cases(),
            'erroListagem' => $erroListagem,
        ]);
    }

    public function store(): void
    {
        try {
            $this->requirePost();

            $projeto = $this->buscarProjeto((int) ($_POST['projetoId'] ?? 0));
            $responsavel = $this->buscarParticipante((int) ($_POST['responsavelId'] ?? 0));

            if (!$projeto) {
                throw new InvalidArgumentException('Selecione um projeto para a atividade.');
            }

            $atividade = (new Atividade())
                ->setTitulo($this->textInput('titulo', true))
                ->setDescricao($this->textInput('descricao'))
                ->setStatus($this->validarEnum('status', StatusAtividade::cases(), StatusAtividade::NAO_INICIADA->value))
                ->setPrioridade($this->validarEnum('prioridade', Prioridade::cases(), Prioridade::MEDIA->value))
                ->setDataInicio($this->dateInput('dataInicio'))
                ->setPrazo($this->dateInput('prazo'))
                ->setPercentualConclusao($this->intInput('percentualConclusao', 0, 0, 100))
                ->setProjeto($projeto)
                ->setResponsavel($responsavel);

            AtividadeDAO::salvar($atividade);

            $this->flash('success', 'Atividade cadastrada com sucesso.');
        } catch (Throwable $e) {
            $this->flash('danger', $this->mensagemErro($e, 'Nao foi possivel cadastrar a atividade.'));
        }

        $this->redirect('atividades');
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
