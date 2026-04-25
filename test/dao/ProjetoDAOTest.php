<?php

namespace test\dao;

use dao\ProjetoDAO;
use DateTime;
use model\Projeto;

class ProjetoDAOTest extends DAOTestCase
{
    public function testCrudProjeto(): void
    {
        $token = 'crud_projeto_' . str_replace('.', '', uniqid('', true));

        $projeto = (new Projeto())
            ->setNome('Projeto ' . $token)
            ->setDescricao('Descricao ' . $token)
            ->setObjetivo('Objetivo ' . $token)
            ->setStatus('PLANEJADO')
            ->setPrioridade('MEDIA')
            ->setDataInicio(new DateTime('2026-03-10'))
            ->setDataFim(new DateTime('2026-11-20'))
            ->setOrcamentoPrevisto(2200.90)
            ->setPercentualConcluido(15);

        $projetoSalvo = ProjetoDAO::salvar($projeto);
        $this->trackEntity($projetoSalvo);

        $this->assertNotNull($projetoSalvo->getId());

        $this->clearEntityManager();
        $projetoBuscado = ProjetoDAO::buscarId($projetoSalvo);
        $this->assertInstanceOf(Projeto::class, $projetoBuscado);
        $this->assertSame('Projeto ' . $token, $projetoBuscado->getNome());

        $idsListados = array_map(static fn (Projeto $item): int => $item->getId(), ProjetoDAO::listar());
        $this->assertContains($projetoSalvo->getId(), $idsListados);

        $buscaPorNome = ProjetoDAO::buscarNome('Projeto ' . $token);
        $this->assertCount(1, $buscaPorNome);

        $projetoBuscado
            ->setStatus('EM_ANDAMENTO')
            ->setPrioridade('ALTA')
            ->setPercentualConcluido(80);
        ProjetoDAO::salvar($projetoBuscado);

        $this->clearEntityManager();
        $projetoAtualizado = ProjetoDAO::buscarId($projetoSalvo);
        $this->assertInstanceOf(Projeto::class, $projetoAtualizado);
        $this->assertSame('EM_ANDAMENTO', $projetoAtualizado->getStatus());
        $this->assertSame('ALTA', $projetoAtualizado->getPrioridade());
        $this->assertSame(80, $projetoAtualizado->getPercentualConcluido());

        ProjetoDAO::deletar($projetoAtualizado);
        $this->untrackEntity($projetoSalvo);

        $this->clearEntityManager();
        $this->assertNull(ProjetoDAO::buscarId($projetoSalvo));
    }
}
