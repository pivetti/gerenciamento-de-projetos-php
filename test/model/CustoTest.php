<?php

namespace test\model;

use DateTime;
use model\Custo;
use model\Recurso;
use model\Atividade;
use model\Projeto;
use PHPUnit\Framework\TestCase;

class CustoTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $custo = new Custo();

        $this->assertNotNull($custo);
        $this->assertNull($custo->getId());
    }

    public function testSettersAndGetters(): void
    {
        $projeto = new Projeto();
        $dataLancamento = new DateTime('2026-03-10');
        $atividade = new Atividade();
        $atividade->setTitulo('Planejamento');
        $atividade->setProjeto($projeto);
        $recurso = new Recurso();
        $recurso->setNome('Notebook');
        $recurso->setTipo('TECNOLOGICO');
        $recurso->setQuantidade(2);
        $recurso->setCustoUnitario(3500.00);
        $recurso->setProjeto($projeto);

        $custo = new Custo();
        $custo->setDescricao('Compra de licencas');
        $custo->setTipo('AQUISICAO');
        $custo->setValorPrevisto(1500.00);
        $custo->setValorReal(1700.00);
        $custo->setDataLancamento($dataLancamento);
        $custo->setProjeto($projeto);
        $custo->setAtividade($atividade);
        $custo->setRecurso($recurso);

        $this->assertEquals('Compra de licencas', $custo->getDescricao());
        $this->assertEquals('AQUISICAO', $custo->getTipo());
        $this->assertEquals(1500.00, $custo->getValorPrevisto());
        $this->assertEquals(1700.00, $custo->getValorReal());
        $this->assertSame($dataLancamento, $custo->getDataLancamento());
        $this->assertSame($projeto, $custo->getProjeto());
        $this->assertSame($atividade, $custo->getAtividade());
        $this->assertSame($recurso, $custo->getRecurso());
    }
}
