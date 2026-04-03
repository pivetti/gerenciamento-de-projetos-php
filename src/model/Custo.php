<?php

namespace model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use enums\TipoCusto;

#[ORM\Entity]
#[ORM\Table(name: 'custos')]
class Custo extends GenericModel
{
    #[ORM\Column(type: 'text')]
    private string $descricao = '';

    #[ORM\Column(type: 'string', enumType: TipoCusto::class, length: 20)]
    private TipoCusto $tipo;

    #[ORM\Column(name: 'valor_previsto', type: 'float', options: ['default' => 0])]
    private float $valorPrevisto = 0.0;

    #[ORM\Column(name: 'valor_real', type: 'float', nullable: true)]
    private ?float $valorReal = null;

    #[ORM\Column(name: 'data_lancamento', type: 'date', nullable: true)]
    private ?DateTime $dataLancamento = null;

    #[ORM\ManyToOne(targetEntity: Projeto::class)]
    #[ORM\JoinColumn(name: 'projeto_id', referencedColumnName: 'id', nullable: false)]
    private Projeto $projeto;

    #[ORM\ManyToOne(targetEntity: Atividade::class)]
    #[ORM\JoinColumn(name: 'atividade_id', referencedColumnName: 'id', nullable: true)]
    private ?Atividade $atividade = null;

    #[ORM\ManyToOne(targetEntity: Recurso::class)]
    #[ORM\JoinColumn(name: 'recurso_id', referencedColumnName: 'id', nullable: true)]
    private ?Recurso $recurso = null;

    public function __construct()
    {
        $this->tipo = TipoCusto::PLANEJADO;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo->value;
    }

    public function getTipoEnum(): TipoCusto
    {
        return $this->tipo;
    }

    public function setTipo(TipoCusto|string $tipo): self
    {
        $this->tipo = $tipo instanceof TipoCusto ? $tipo : TipoCusto::from($tipo);
        return $this;
    }

    public function getValorPrevisto(): float
    {
        return $this->valorPrevisto;
    }

    public function setValorPrevisto(float $valorPrevisto): self
    {
        $this->valorPrevisto = $valorPrevisto;
        return $this;
    }

    public function getValorReal(): ?float
    {
        return $this->valorReal;
    }

    public function setValorReal(?float $valorReal): self
    {
        $this->valorReal = $valorReal;
        return $this;
    }

    public function getDataLancamento(): ?DateTime
    {
        return $this->dataLancamento;
    }

    public function setDataLancamento(?DateTime $dataLancamento): self
    {
        $this->dataLancamento = $dataLancamento;
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

    public function getAtividade(): ?Atividade
    {
        return $this->atividade;
    }

    public function setAtividade(?Atividade $atividade): self
    {
        $this->atividade = $atividade;
        return $this;
    }

    public function getRecurso(): ?Recurso
    {
        return $this->recurso;
    }

    public function setRecurso(?Recurso $recurso): self
    {
        $this->recurso = $recurso;
        return $this;
    }
}
