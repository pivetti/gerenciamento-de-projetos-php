<?php

namespace model;

use Doctrine\ORM\Mapping as ORM;
use enums\CategoriaRisco;
use enums\StatusRisco;

#[ORM\Entity]
#[ORM\Table(name: 'riscos')]
class Risco extends GenericModel
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $titulo = '';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descricao = null;

    #[ORM\Column(type: 'string', enumType: CategoriaRisco::class, length: 20)]
    private CategoriaRisco $categoria;

    #[ORM\Column(type: 'integer')]
    private int $probabilidade = 1;

    #[ORM\Column(type: 'integer')]
    private int $impacto = 1;

    #[ORM\Column(type: 'integer')]
    private int $criticidade = 1;

    #[ORM\Column(type: 'string', enumType: StatusRisco::class, length: 20)]
    private StatusRisco $status;

    #[ORM\Column(name: 'estrategia_resposta', type: 'string', length: 500, nullable: true)]
    private ?string $estrategiaResposta = null;

    #[ORM\Column(name: 'plano_mitigacao', type: 'text', nullable: true)]
    private ?string $planoMitigacao = null;

    #[ORM\ManyToOne(targetEntity: Projeto::class)]
    #[ORM\JoinColumn(name: 'projeto_id', referencedColumnName: 'id', nullable: false)]
    private Projeto $projeto;

    public function __construct()
    {
        $this->categoria = CategoriaRisco::ESCOPO;
        $this->status = StatusRisco::IDENTIFICADO;
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

    public function getCategoria(): string
    {
        return $this->categoria->value;
    }

    public function getCategoriaEnum(): CategoriaRisco
    {
        return $this->categoria;
    }

    public function setCategoria(CategoriaRisco|string $categoria): self
    {
        $this->categoria = $categoria instanceof CategoriaRisco ? $categoria : CategoriaRisco::from($categoria);
        return $this;
    }

    public function getProbabilidade(): int
    {
        return $this->probabilidade;
    }

    public function setProbabilidade(int $probabilidade): self
    {
        $this->probabilidade = $probabilidade;
        return $this;
    }

    public function getImpacto(): int
    {
        return $this->impacto;
    }

    public function setImpacto(int $impacto): self
    {
        $this->impacto = $impacto;
        return $this;
    }

    public function getCriticidade(): int
    {
        return $this->criticidade;
    }

    public function setCriticidade(int $criticidade): self
    {
        $this->criticidade = $criticidade;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status->value;
    }

    public function getStatusEnum(): StatusRisco
    {
        return $this->status;
    }

    public function setStatus(StatusRisco|string $status): self
    {
        $this->status = $status instanceof StatusRisco ? $status : StatusRisco::from($status);
        return $this;
    }

    public function getEstrategiaResposta(): ?string
    {
        return $this->estrategiaResposta;
    }

    public function setEstrategiaResposta(?string $estrategiaResposta): self
    {
        $this->estrategiaResposta = $estrategiaResposta;
        return $this;
    }

    public function getPlanoMitigacao(): ?string
    {
        return $this->planoMitigacao;
    }

    public function setPlanoMitigacao(?string $planoMitigacao): self
    {
        $this->planoMitigacao = $planoMitigacao;
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
}
