<?php

namespace model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use enums\Prioridade;
use enums\StatusProjeto;

#[ORM\Entity]
#[ORM\Table(name: 'projetos')]
class Projeto extends GenericModel
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $nome = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descricao = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $objetivo = null;

    #[ORM\Column(type: 'string', enumType: StatusProjeto::class, length: 20)]
    private StatusProjeto $status;

    #[ORM\Column(type: 'string', enumType: Prioridade::class, length: 20)]
    private Prioridade $prioridade;

    #[ORM\Column(name: 'data_inicio', type: 'date', nullable: true)]
    private ?DateTime $dataInicio = null;

    #[ORM\Column(name: 'data_fim', type: 'date', nullable: true)]
    private ?DateTime $dataFim = null;

    #[ORM\Column(name: 'orcamento_previsto', type: 'float', options: ['default' => 0])]
    private float $orcamentoPrevisto = 0.0;

    #[ORM\Column(name: 'percentual_concluido', type: 'integer', options: ['default' => 0])]
    private int $percentualConcluido = 0;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: Participante::class)]
    private Collection $participantes;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: Atividade::class)]
    private Collection $atividades;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: Risco::class)]
    private Collection $riscos;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: Recurso::class)]
    private Collection $recursos;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: Custo::class)]
    private Collection $custos;

    public function __construct()
    {
        $this->status = StatusProjeto::PLANEJADO;
        $this->prioridade = Prioridade::MEDIA;
        $this->participantes = new ArrayCollection();
        $this->atividades = new ArrayCollection();
        $this->riscos = new ArrayCollection();
        $this->recursos = new ArrayCollection();
        $this->custos = new ArrayCollection();
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getObjetivo(): ?string
    {
        return $this->objetivo;
    }

    public function setObjetivo(?string $objetivo): self
    {
        $this->objetivo = $objetivo;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status->value;
    }

    public function getStatusEnum(): StatusProjeto
    {
        return $this->status;
    }

    public function setStatus(StatusProjeto|string $status): self
    {
        $this->status = $status instanceof StatusProjeto ? $status : StatusProjeto::from($status);
        return $this;
    }

    public function getPrioridade(): string
    {
        return $this->prioridade->value;
    }

    public function getPrioridadeEnum(): Prioridade
    {
        return $this->prioridade;
    }

    public function setPrioridade(Prioridade|string $prioridade): self
    {
        $this->prioridade = $prioridade instanceof Prioridade ? $prioridade : Prioridade::from($prioridade);
        return $this;
    }

    public function getDataInicio(): ?DateTime
    {
        return $this->dataInicio;
    }

    public function setDataInicio(?DateTime $dataInicio): self
    {
        $this->dataInicio = $dataInicio;
        return $this;
    }

    public function getDataFim(): ?DateTime
    {
        return $this->dataFim;
    }

    public function setDataFim(?DateTime $dataFim): self
    {
        $this->dataFim = $dataFim;
        return $this;
    }

    public function getOrcamentoPrevisto(): float
    {
        return $this->orcamentoPrevisto;
    }

    public function setOrcamentoPrevisto(float $orcamentoPrevisto): self
    {
        $this->orcamentoPrevisto = $orcamentoPrevisto;
        return $this;
    }

    public function getPercentualConcluido(): int
    {
        return $this->percentualConcluido;
    }

    public function setPercentualConcluido(int $percentualConcluido): self
    {
        $this->percentualConcluido = $percentualConcluido;
        return $this;
    }

    public function getParticipantes(): Collection
    {
        return $this->participantes;
    }

    public function getAtividades(): Collection
    {
        return $this->atividades;
    }

    public function getRiscos(): Collection
    {
        return $this->riscos;
    }

    public function getRecursos(): Collection
    {
        return $this->recursos;
    }

    public function getCustos(): Collection
    {
        return $this->custos;
    }
}
