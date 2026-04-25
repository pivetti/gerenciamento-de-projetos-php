<?php

namespace test\dao;

use dao\UsuarioDAO;
use model\Usuario;

class UsuarioDAOTest extends DAOTestCase
{
    public function testCrudUsuario(): void
    {
        $token = 'crud_usuario_' . str_replace('.', '', uniqid('', true));

        $usuario = (new Usuario())
            ->setNome('Usuario ' . $token)
            ->setEmail($token . '@teste.local')
            ->setSenha('senhaForte123')
            ->setPerfil('ANALISTA')
            ->setTelefone('11912345678');

        $usuarioSalvo = UsuarioDAO::salvar($usuario);
        $this->trackEntity($usuarioSalvo);

        $this->assertNotNull($usuarioSalvo->getId());

        $this->clearEntityManager();
        $usuarioBuscado = UsuarioDAO::buscarId($usuarioSalvo);
        $this->assertInstanceOf(Usuario::class, $usuarioBuscado);
        $this->assertSame($token . '@teste.local', $usuarioBuscado->getEmail());

        $idsListados = array_map(static fn (Usuario $item): int => $item->getId(), UsuarioDAO::listar());
        $this->assertContains($usuarioSalvo->getId(), $idsListados);

        $buscaPorEmail = UsuarioDAO::buscarEmail($token . '@teste.local');
        $this->assertCount(1, $buscaPorEmail);

        $usuarioBuscado
            ->setNome('Usuario Atualizado ' . $token)
            ->setPerfil('GERENTE_PROJETO')
            ->setTelefone('11987654321');
        UsuarioDAO::salvar($usuarioBuscado);

        $this->clearEntityManager();
        $usuarioAtualizado = UsuarioDAO::buscarId($usuarioSalvo);
        $this->assertInstanceOf(Usuario::class, $usuarioAtualizado);
        $this->assertSame('Usuario Atualizado ' . $token, $usuarioAtualizado->getNome());
        $this->assertSame('GERENTE_PROJETO', $usuarioAtualizado->getPerfil());
        $this->assertSame('11987654321', $usuarioAtualizado->getTelefone());

        UsuarioDAO::deletar($usuarioAtualizado);
        $this->untrackEntity($usuarioSalvo);

        $this->clearEntityManager();
        $this->assertNull(UsuarioDAO::buscarId($usuarioSalvo));
    }
}
