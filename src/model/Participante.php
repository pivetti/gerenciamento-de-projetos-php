<?php

namespace model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use enums\PapelAcesso;

#[ORM\Entity]
#[ORM\Table(name: 'participantes')]
class Participante extends GenericModel
{
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    private Usuario $usuario;

    #[ORM\ManyToOne(targetEntity: Projeto::class)]
    #[ORM\JoinColumn(name: 'projeto_id', referencedColumnName: 'id', nullable: false)]
    private Projeto $projeto;

    #[ORM\Column(name: 'funcao_no_projeto', type: 'string', length: 100)]
    private string $funcaoNoProjeto = '';

    #[ORM\Column(name: 'papel_acesso', type: 'string', enumType: PapelAcesso::class, length: 30)]
    private PapelAcesso $papelAcesso;

    #[ORM\Column(type: 'boolean')]
    private bool $ativo = true;

    #[ORM\OneToMany(mappedBy: 'responsavel', targetEntity: Atividade::class)]
    private Collection $atividadesResponsaveis;

    public function __construct()
    {
        $this->papelAcesso = PapelAcesso::EXECUTOR;
        $this->atividadesResponsaveis = new ArrayCollection();
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;
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

    public function getFuncaoNoProjeto(): string
    {
        return $this->funcaoNoProjeto;
    }

    public function setFuncaoNoProjeto(string $funcaoNoProjeto): self
    {
        $this->funcaoNoProjeto = $funcaoNoProjeto;
        return $this;
    }

    public function getPapelAcesso(): string
    {
        return $this->papelAcesso->value;
    }

    public function getPapelAcessoEnum(): PapelAcesso
    {
        return $this->papelAcesso;
    }

    public function setPapelAcesso(PapelAcesso|string $papelAcesso): self
    {
        $this->papelAcesso = $papelAcesso instanceof PapelAcesso ? $papelAcesso : PapelAcesso::from($papelAcesso);
        return $this;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = $ativo;
        return $this;
    }

    public function getAtividadesResponsaveis(): Collection
    {
        return $this->atividadesResponsaveis;
    }
}
