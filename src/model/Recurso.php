<?php

namespace model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use enums\TipoRecurso;

#[ORM\Entity]
#[ORM\Table(name: 'recursos')]
class Recurso extends GenericModel
{
    #[ORM\Column(type: 'string', length: 120)]
    private string $nome = '';

    #[ORM\Column(type: 'string', enumType: TipoRecurso::class, length: 20)]
    private TipoRecurso $tipo;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descricao = null;

    #[ORM\Column(type: 'integer')]
    private int $quantidade = 1;

    #[ORM\Column(name: 'custo_unitario', type: 'float', options: ['default' => 0])]
    private float $custoUnitario = 0.0;

    #[ORM\ManyToOne(targetEntity: Projeto::class)]
    #[ORM\JoinColumn(name: 'projeto_id', referencedColumnName: 'id', nullable: false)]
    private Projeto $projeto;

    #[ORM\OneToMany(mappedBy: 'recurso', targetEntity: Custo::class)]
    private Collection $custos;

    public function __construct()
    {
        $this->tipo = TipoRecurso::HUMANO;
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

    public function getTipo(): string
    {
        return $this->tipo->value;
    }

    public function getTipoEnum(): TipoRecurso
    {
        return $this->tipo;
    }

    public function setTipo(TipoRecurso|string $tipo): self
    {
        $this->tipo = $tipo instanceof TipoRecurso ? $tipo : TipoRecurso::from($tipo);
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

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;
        return $this;
    }

    public function getCustoUnitario(): float
    {
        return $this->custoUnitario;
    }

    public function setCustoUnitario(float $custoUnitario): self
    {
        $this->custoUnitario = $custoUnitario;
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

    public function getCustos(): Collection
    {
        return $this->custos;
    }
}
