<?php

namespace model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use enums\Prioridade;
use enums\StatusAtividade;

#[ORM\Entity]
#[ORM\Table(name: 'atividades')]
class Atividade extends GenericModel
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $titulo = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descricao = null;

    #[ORM\Column(type: 'string', enumType: StatusAtividade::class, length: 20)]
    private StatusAtividade $status;

    #[ORM\Column(type: 'string', enumType: Prioridade::class, length: 20)]
    private Prioridade $prioridade;

    #[ORM\Column(name: 'data_inicio', type: 'date', nullable: true)]
    private ?DateTime $dataInicio = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTime $prazo = null;

    #[ORM\ManyToOne(targetEntity: Projeto::class)]
    #[ORM\JoinColumn(name: 'projeto_id', referencedColumnName: 'id', nullable: false)]
    private Projeto $projeto;

    #[ORM\Column(name: 'data_conclusao', type: 'date', nullable: true)]
    private ?DateTime $dataConclusao = null;

    #[ORM\Column(name: 'percentual_conclusao', type: 'integer', options: ['default' => 0])]
    private int $percentualConclusao = 0;

    #[ORM\ManyToOne(targetEntity: Participante::class)]
    #[ORM\JoinColumn(name: 'responsavel_id', referencedColumnName: 'id', nullable: true)]
    private ?Participante $responsavel = null;

    #[ORM\OneToMany(mappedBy: 'atividade', targetEntity: Custo::class)]
    private Collection $custos;

    public function __construct()
    {
        $this->status = StatusAtividade::NAO_INICIADA;
        $this->prioridade = Prioridade::MEDIA;
        $this->custos = new ArrayCollection();
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;
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

    public function getStatus(): string
    {
        return $this->status->value;
    }

    public function getStatusEnum(): StatusAtividade
    {
        return $this->status;
    }

    public function setStatus(StatusAtividade|string $status): self
    {
        $this->status = $status instanceof StatusAtividade ? $status : StatusAtividade::from($status);
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

    public function getPrazo(): ?DateTime
    {
        return $this->prazo;
    }

    public function setPrazo(?DateTime $prazo): self
    {
        $this->prazo = $prazo;
        return $this;
    }

    public function getDataConclusao(): ?DateTime
    {
        return $this->dataConclusao;
    }

    public function setDataConclusao(?DateTime $dataConclusao): self
    {
        $this->dataConclusao = $dataConclusao;
        return $this;
    }

    public function getPercentualConclusao(): int
    {
        return $this->percentualConclusao;
    }

    public function setPercentualConclusao(int $percentualConclusao): self
    {
        $this->percentualConclusao = $percentualConclusao;
        return $this;
    }

    public function getProjeto(): Projeto
    {
        return $this->projeto;
    }

    public function setProjeto(Projeto $projeto): self
    {
        $this->projeto = $projeto;
        return $this;
    }

    public function getResponsavel(): ?Participante
    {
        return $this->responsavel;
    }

    public function setResponsavel(?Participante $responsavel): self
    {
        $this->responsavel = $responsavel;
        return $this;
    }

    public function getCustos(): Collection
    {
        return $this->custos;
    }
}
