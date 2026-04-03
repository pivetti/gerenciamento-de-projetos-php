<?php

namespace test\dao;

use dao\RiscoDao;
use model\Risco;

class RiscoDaoTest extends DaoTestCase
{
    public function testCrudRisco(): void
    {
        $token = 'crud_risco_' . str_replace('.', '', uniqid('', true));
        $projeto = $this->createProjeto();

        $risco = (new Risco())
            ->setProjeto($projeto)
            ->setTitulo('Risco ' . $token)
            ->setDescricao('Descricao ' . $token)
            ->setCategoria('PRAZO')
            ->setProbabilidade(2)
            ->setImpacto(5)
            ->setCriticidade(10)
            ->setStatus('IDENTIFICADO')
            ->setEstrategiaResposta('Transferir')
            ->setPlanoMitigacao('Plano ' . $token);

        $riscoSalvo = RiscoDao::salvar($risco);
        $this->trackEntity($riscoSalvo);

        $this->assertNotNull($riscoSalvo->getId());

        $this->clearEntityManager();
        $riscoBuscado = RiscoDao::buscarId($riscoSalvo);
        $this->assertInstanceOf(Risco::class, $riscoBuscado);
        $this->assertSame('Risco ' . $token, $riscoBuscado->getTitulo());

        $idsListados = array_map(static fn (Risco $item): int => $item->getId(), RiscoDao::listar());
        $this->assertContains($riscoSalvo->getId(), $idsListados);

        $buscaPorStatus = RiscoDao::buscarStatus('IDENTIFICADO');
        $idsPorStatus = array_map(static fn (Risco $item): int => $item->getId(), $buscaPorStatus);
        $this->assertContains($riscoSalvo->getId(), $idsPorStatus);

        $riscoBuscado
            ->setStatus('MITIGADO')
            ->setProbabilidade(1)
            ->setCriticidade(5);
        RiscoDao::salvar($riscoBuscado);

        $this->clearEntityManager();
        $riscoAtualizado = RiscoDao::buscarId($riscoSalvo);
        $this->assertInstanceOf(Risco::class, $riscoAtualizado);
        $this->assertSame('MITIGADO', $riscoAtualizado->getStatus());
        $this->assertSame(1, $riscoAtualizado->getProbabilidade());
        $this->assertSame(5, $riscoAtualizado->getCriticidade());

        RiscoDao::deletar($riscoAtualizado);
        $this->untrackEntity($riscoSalvo);

        $this->clearEntityManager();
        $this->assertNull(RiscoDao::buscarId($riscoSalvo));
    }
}
