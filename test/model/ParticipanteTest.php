<?php

namespace test\model;

use model\Participante;
use model\Projeto;
use model\Usuario;
use PHPUnit\Framework\TestCase;

class ParticipanteTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $participante = new Participante();

        $this->assertNotNull($participante);
        $this->assertNull($participante->getId());
    }

    public function testSettersAndGetters(): void
    {
        $usuario = new Usuario();
        $usuario->setNome('Carlos');

        $projeto = new Projeto();
        $projeto->setNome('Projeto XPTO');

        $participante = new Participante();
        $participante->setUsuario($usuario);
        $participante->setProjeto($projeto);
        $participante->setFuncaoNoProjeto('Scrum Master');
        $participante->setPapelAcesso('COORDENADOR');
        $participante->setAtivo(true);

        $this->assertSame($usuario, $participante->getUsuario());
        $this->assertSame($projeto, $participante->getProjeto());
        $this->assertEquals('Scrum Master', $participante->getFuncaoNoProjeto());
        $this->assertEquals('COORDENADOR', $participante->getPapelAcesso());
        $this->assertTrue($participante->getAtivo());
    }
}
