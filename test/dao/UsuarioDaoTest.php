<?php

namespace test\dao;

use dao\UsuarioDao;
use model\Usuario;

class UsuarioDaoTest extends DaoTestCase
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

        $usuarioSalvo = UsuarioDao::salvar($usuario);
        $this->trackEntity($usuarioSalvo);

        $this->assertNotNull($usuarioSalvo->getId());

        $this->clearEntityManager();
        $usuarioBuscado = UsuarioDao::buscarId($usuarioSalvo);
        $this->assertInstanceOf(Usuario::class, $usuarioBuscado);
        $this->assertSame($token . '@teste.local', $usuarioBuscado->getEmail());

        $idsListados = array_map(static fn (Usuario $item): int => $item->getId(), UsuarioDao::listar());
        $this->assertContains($usuarioSalvo->getId(), $idsListados);

        $buscaPorEmail = UsuarioDao::buscarEmail($token . '@teste.local');
        $this->assertCount(1, $buscaPorEmail);

        $usuarioBuscado
            ->setNome('Usuario Atualizado ' . $token)
            ->setPerfil('GERENTE_PROJETO')
            ->setTelefone('11987654321');
        UsuarioDao::salvar($usuarioBuscado);

        $this->clearEntityManager();
        $usuarioAtualizado = UsuarioDao::buscarId($usuarioSalvo);
        $this->assertInstanceOf(Usuario::class, $usuarioAtualizado);
        $this->assertSame('Usuario Atualizado ' . $token, $usuarioAtualizado->getNome());
        $this->assertSame('GERENTE_PROJETO', $usuarioAtualizado->getPerfil());
        $this->assertSame('11987654321', $usuarioAtualizado->getTelefone());

        UsuarioDao::deletar($usuarioAtualizado);
        $this->untrackEntity($usuarioSalvo);

        $this->clearEntityManager();
        $this->assertNull(UsuarioDao::buscarId($usuarioSalvo));
    }
}
