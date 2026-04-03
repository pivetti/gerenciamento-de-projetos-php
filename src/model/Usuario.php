<?php

namespace model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use enums\PerfilUsuario;

#[ORM\Entity]
#[ORM\Table(name: 'usuarios')]
class Usuario extends GenericModel
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $nome = '';

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email = '';

    #[ORM\Column(type: 'string', length: 255)]
    private string $senha = '';

    #[ORM\Column(type: 'string', enumType: PerfilUsuario::class, length: 30)]
    private PerfilUsuario $perfil;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $telefone = null;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: Participante::class)]
    private Collection $participacoes;

    public function __construct()
    {
        $this->perfil = PerfilUsuario::MEMBRO_EQUIPE;
        $this->participacoes = new ArrayCollection();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): self
    {
        $this->senha = $senha;
        return $this;
    }

    public function getPerfil(): string
    {
        return $this->perfil->value;
    }

    public function getPerfilEnum(): PerfilUsuario
    {
        return $this->perfil;
    }

    public function setPerfil(PerfilUsuario|string $perfil): self
    {
        $this->perfil = $perfil instanceof PerfilUsuario ? $perfil : PerfilUsuario::from($perfil);
        return $this;
    }

    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(?string $telefone): self
    {
        $this->telefone = $telefone;
        return $this;
    }

    public function getParticipacoes(): Collection
    {
        return $this->participacoes;
    }
}
