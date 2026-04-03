<?php

namespace test\dao;

use dao\ProjetoDao;
use DateTime;
use model\Projeto;

class ProjetoDaoTest extends DaoTestCase
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

        $projetoSalvo = ProjetoDao::salvar($projeto);
        $this->trackEntity($projetoSalvo);

        $this->assertNotNull($projetoSalvo->getId());

        $this->clearEntityManager();
        $projetoBuscado = ProjetoDao::buscarId($projetoSalvo);
        $this->assertInstanceOf(Projeto::class, $projetoBuscado);
        $this->assertSame('Projeto ' . $token, $projetoBuscado->getNome());

        $idsListados = array_map(static fn (Projeto $item): int => $item->getId(), ProjetoDao::listar());
        $this->assertContains($projetoSalvo->getId(), $idsListados);

        $buscaPorNome = ProjetoDao::buscarNome('Projeto ' . $token);
        $this->assertCount(1, $buscaPorNome);

        $projetoBuscado
            ->setStatus('EM_ANDAMENTO')
            ->setPrioridade('ALTA')
            ->setPercentualConcluido(80);
        ProjetoDao::salvar($projetoBuscado);

        $this->clearEntityManager();
        $projetoAtualizado = ProjetoDao::buscarId($projetoSalvo);
        $this->assertInstanceOf(Projeto::class, $projetoAtualizado);
        $this->assertSame('EM_ANDAMENTO', $projetoAtualizado->getStatus());
        $this->assertSame('ALTA', $projetoAtualizado->getPrioridade());
        $this->assertSame(80, $projetoAtualizado->getPercentualConcluido());

        ProjetoDao::deletar($projetoAtualizado);
        $this->untrackEntity($projetoSalvo);

        $this->clearEntityManager();
        $this->assertNull(ProjetoDao::buscarId($projetoSalvo));
    }
}
