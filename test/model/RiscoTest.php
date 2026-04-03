<?php

namespace test\model;

use model\Projeto;
use model\Risco;
use PHPUnit\Framework\TestCase;

class RiscoTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $risco = new Risco();

        $this->assertNotNull($risco);
        $this->assertNull($risco->getId());
    }

    public function testSettersAndGetters(): void
    {
        $projeto = new Projeto();

        $risco = new Risco();
        $risco->setTitulo('Atraso na entrega');
        $risco->setDescricao('Fornecedor pode atrasar a entrega');
        $risco->setCategoria('PRAZO');
        $risco->setProbabilidade(5);
        $risco->setImpacto(4);
        $risco->setCriticidade(20);
        $risco->setStatus('EM_ANALISE');
        $risco->setEstrategiaResposta('Mitigar');
        $risco->setPlanoMitigacao('Acompanhar fornecedor semanalmente');
        $risco->setProjeto($projeto);

        $this->assertEquals('Atraso na entrega', $risco->getTitulo());
        $this->assertEquals('Fornecedor pode atrasar a entrega', $risco->getDescricao());
        $this->assertEquals('PRAZO', $risco->getCategoria());
        $this->assertEquals(5, $risco->getProbabilidade());
        $this->assertEquals(4, $risco->getImpacto());
        $this->assertEquals(20, $risco->getCriticidade());
        $this->assertEquals('EM_ANALISE', $risco->getStatus());
        $this->assertEquals('Mitigar', $risco->getEstrategiaResposta());
        $this->assertEquals('Acompanhar fornecedor semanalmente', $risco->getPlanoMitigacao());
        $this->assertSame($projeto, $risco->getProjeto());
    }
}
