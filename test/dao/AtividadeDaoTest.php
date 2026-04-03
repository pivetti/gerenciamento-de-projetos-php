<?php

namespace test\dao;

use dao\AtividadeDao;
use DateTime;
use model\Atividade;

class AtividadeDaoTest extends DaoTestCase
{
    public function testCrudAtividade(): void
    {
        $token = 'crud_atividade_' . str_replace('.', '', uniqid('', true));
        $projeto = $this->createProjeto();
        $responsavel = $this->createParticipante($projeto);

        $atividade = (new Atividade())
            ->setProjeto($projeto)
            ->setResponsavel($responsavel)
            ->setTitulo('Atividade ' . $token)
            ->setDescricao('Descricao ' . $token)
            ->setStatus('NAO_INICIADA')
            ->setPrioridade('ALTA')
            ->setDataInicio(new DateTime('2026-04-02'))
            ->setPrazo(new DateTime('2026-05-10'))
            ->setPercentualConclusao(5);

        $atividadeSalva = AtividadeDao::salvar($atividade);
        $this->trackEntity($atividadeSalva);

        $this->assertNotNull($atividadeSalva->getId());

        $this->clearEntityManager();
        $atividadeBuscada = AtividadeDao::buscarId($atividadeSalva);
        $this->assertInstanceOf(Atividade::class, $atividadeBuscada);
        $this->assertSame('Atividade ' . $token, $atividadeBuscada->getTitulo());

        $idsListados = array_map(static fn (Atividade $item): int => $item->getId(), AtividadeDao::listar());
        $this->assertContains($atividadeSalva->getId(), $idsListados);

        $buscaPorTitulo = AtividadeDao::buscarTitulo('Atividade ' . $token);
        $this->assertCount(1, $buscaPorTitulo);

        $atividadeBuscada
            ->setStatus('CONCLUIDA')
            ->setPercentualConclusao(100)
            ->setDataConclusao(new DateTime('2026-05-08'));
        AtividadeDao::salvar($atividadeBuscada);

        $this->clearEntityManager();
        $atividadeAtualizada = AtividadeDao::buscarId($atividadeSalva);
        $this->assertInstanceOf(Atividade::class, $atividadeAtualizada);
        $this->assertSame('CONCLUIDA', $atividadeAtualizada->getStatus());
        $this->assertSame(100, $atividadeAtualizada->getPercentualConclusao());
        $this->assertSame('2026-05-08', $atividadeAtualizada->getDataConclusao()?->format('Y-m-d'));

        AtividadeDao::deletar($atividadeAtualizada);
        $this->untrackEntity($atividadeSalva);

        $this->clearEntityManager();
        $this->assertNull(AtividadeDao::buscarId($atividadeSalva));
    }
}
