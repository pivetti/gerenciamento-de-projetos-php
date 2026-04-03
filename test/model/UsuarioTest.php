<?php

namespace test\model;

use model\Usuario;
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $usuario = new Usuario();

        $this->assertNotNull($usuario);
        $this->assertNull($usuario->getId());
    }

    public function testSettersAndGetters(): void
    {
        $usuario = new Usuario();
        $usuario->setNome('Eduardo Alba');
        $usuario->setEmail('eduardo@email.com');
        $usuario->setSenha('123456');
        $usuario->setPerfil('GERENTE_PROJETO');
        $usuario->setTelefone('(11)99999-9999');

        $this->assertEquals('Eduardo Alba', $usuario->getNome());
        $this->assertEquals('eduardo@email.com', $usuario->getEmail());
        $this->assertEquals('123456', $usuario->getSenha());
        $this->assertEquals('GERENTE_PROJETO', $usuario->getPerfil());
        $this->assertEquals('(11)99999-9999', $usuario->getTelefone());
    }
}
