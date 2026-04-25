<?php

namespace test\dao;

use dao\CustoDAO;
use DateTime;
use model\Custo;

class CustoDAOTest extends DAOTestCase
{
    public function testCrudCusto(): void
    {
        $token = 'crud_custo_' . str_replace('.', '', uniqid('', true));
        $projeto = $this->createProjeto();
        $responsavel = $this->createParticipante($projeto);
        $atividade = $this->createAtividade($projeto, $responsavel);
        $recurso = $this->createRecurso($projeto);

        $custo = (new Custo())
            ->setProjeto($projeto)
            ->setAtividade($atividade)
            ->setRecurso($recurso)
            ->setDescricao('Custo ' . $token)
            ->setTipo('OPERACIONAL')
            ->setValorPrevisto(999.50)
            ->setValorReal(875.40)
            ->setDataLancamento(new DateTime('2026-04-20'));

        $custoSalvo = CustoDAO::salvar($custo);
        $this->trackEntity($custoSalvo);

        $this->assertNotNull($custoSalvo->getId());

        $this->clearEntityManager();
        $custoBuscado = CustoDAO::buscarId($custoSalvo);
        $this->assertInstanceOf(Custo::class, $custoBuscado);
        $this->assertSame('Custo ' . $token, $custoBuscado->getDescricao());

        $idsListados = array_map(static fn (Custo $item): int => $item->getId(), CustoDAO::listar());
        $this->assertContains($custoSalvo->getId(), $idsListados);

        $buscaPorTipo = CustoDAO::buscarTipo('OPERACIONAL');
        $idsPorTipo = array_map(static fn (Custo $item): int => $item->getId(), $buscaPorTipo);
        $this->assertContains($custoSalvo->getId(), $idsPorTipo);

        $custoBuscado
            ->setTipo('IMPREVISTO')
            ->setValorReal(910.30)
            ->setDataLancamento(new DateTime('2026-04-25'));
        CustoDAO::salvar($custoBuscado);

        $this->clearEntityManager();
        $custoAtualizado = CustoDAO::buscarId($custoSalvo);
        $this->assertInstanceOf(Custo::class, $custoAtualizado);
        $this->assertSame('IMPREVISTO', $custoAtualizado->getTipo());
        $this->assertSame(910.30, $custoAtualizado->getValorReal());
        $this->assertSame('2026-04-25', $custoAtualizado->getDataLancamento()?->format('Y-m-d'));

        CustoDAO::deletar($custoAtualizado);
        $this->untrackEntity($custoSalvo);

        $this->clearEntityManager();
        $this->assertNull(CustoDAO::buscarId($custoSalvo));
    }
}
