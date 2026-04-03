<?php

namespace test\model;

use DateTime;
use model\Atividade;
use model\Participante;
use model\Projeto;
use model\Usuario;
use PHPUnit\Framework\TestCase;

class AtividadeTest extends TestCase
{
    public function testCriarObjeto(): void
    {
        $atividade = new Atividade();

        $this->assertNotNull($atividade);
        $this->assertNull($atividade->getId());
    }

    public function testSettersAndGetters(): void
    {
        $projeto = new Projeto();
        $projeto->setNome('Projeto Base');

        $usuario = new Usuario();
        $usuario->setNome('Maria');
        $responsavel = new Participante();
        $responsavel->setUsuario($usuario);
        $responsavel->setProjeto($projeto);
        $responsavel->setFuncaoNoProjeto('Desenvolvedora');
        $responsavel->setPapelAcesso('EXECUTOR');
        $responsavel->setAtivo(true);

        $dataInicio = new DateTime('2026-03-01');
        $prazo = new DateTime('2026-03-20');
        $dataConclusao = new DateTime('2026-03-18');

        $atividade = new Atividade();
        $atividade->setTitulo('Implementar login');
        $atividade->setDescricao('Criar tela e validacao de acesso');
        $atividade->setStatus('EM_ANDAMENTO');
        $atividade->setPrioridade('ALTA');
        $atividade->setDataInicio($dataInicio);
        $atividade->setPrazo($prazo);
        $atividade->setDataConclusao($dataConclusao);
        $atividade->setPercentualConclusao(80);
        $atividade->setProjeto($projeto);
        $atividade->setResponsavel($responsavel);

        $this->assertEquals('Implementar login', $atividade->getTitulo());
        $this->assertEquals('Criar tela e validacao de acesso', $atividade->getDescricao());
        $this->assertEquals('EM_ANDAMENTO', $atividade->getStatus());
        $this->assertEquals('ALTA', $atividade->getPrioridade());
        $this->assertSame($dataInicio, $atividade->getDataInicio());
        $this->assertSame($prazo, $atividade->getPrazo());
        $this->assertSame($dataConclusao, $atividade->getDataConclusao());
        $this->assertEquals(80, $atividade->getPercentualConclusao());
        $this->assertSame($projeto, $atividade->getProjeto());
        $this->assertSame($responsavel, $atividade->getResponsavel());
    }
}
