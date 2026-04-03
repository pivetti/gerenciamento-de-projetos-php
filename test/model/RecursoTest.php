<?php

namespace test\model;

use model\Projeto;
use model\Recurso;
use PHPUnit\Framework\TestCase;

class RecursoTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $recurso = new Recurso();

        $this->assertNotNull($recurso);
        $this->assertNull($recurso->getId());
    }

    public function testSettersAndGetters(): void
    {
        $projeto = new Projeto();
        $projeto->setNome('Projeto XPTO');

        $recurso = new Recurso();
        $recurso->setNome('Servidor cloud');
        $recurso->setTipo('TECNOLOGICO');
        $recurso->setDescricao('Ambiente principal de deploy');
        $recurso->setQuantidade(1);
        $recurso->setCustoUnitario(950.00);
        $recurso->setProjeto($projeto);

        $this->assertEquals('Servidor cloud', $recurso->getNome());
        $this->assertEquals('TECNOLOGICO', $recurso->getTipo());
        $this->assertEquals('Ambiente principal de deploy', $recurso->getDescricao());
        $this->assertEquals(1, $recurso->getQuantidade());
        $this->assertEquals(950.00, $recurso->getCustoUnitario());
        $this->assertSame($projeto, $recurso->getProjeto());
    }
}
