<?php

namespace test\model;

use DateTime;
use model\Projeto;
use model\Usuario;
use PHPUnit\Framework\TestCase;

class ProjetoTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $projeto = new Projeto();

        $this->assertNotNull($projeto);
        $this->assertNull($projeto->getId());
    }

    public function testSettersAndGetters(): void
    {
        $dataInicio = new DateTime('2026-03-01');
        $dataFim = new DateTime('2026-12-31');

        $projeto = new Projeto();
        $projeto->setNome('Sistema de Projetos');
        $projeto->setDescricao('Sistema para gestao de projetos');
        $projeto->setObjetivo('Controlar todo o ciclo do projeto');
        $projeto->setStatus('EM_ANDAMENTO');
        $projeto->setPrioridade('ALTA');
        $projeto->setDataInicio($dataInicio);
        $projeto->setDataFim($dataFim);
        $projeto->setOrcamentoPrevisto(50000.00);
        $projeto->setPercentualConcluido(35);

        $this->assertEquals('Sistema de Projetos', $projeto->getNome());
        $this->assertEquals('Sistema para gestao de projetos', $projeto->getDescricao());
        $this->assertEquals('Controlar todo o ciclo do projeto', $projeto->getObjetivo());
        $this->assertEquals('EM_ANDAMENTO', $projeto->getStatus());
        $this->assertEquals('ALTA', $projeto->getPrioridade());
        $this->assertSame($dataInicio, $projeto->getDataInicio());
        $this->assertSame($dataFim, $projeto->getDataFim());
        $this->assertEquals(50000.00, $projeto->getOrcamentoPrevisto());
        $this->assertEquals(35, $projeto->getPercentualConcluido());
    }
}
