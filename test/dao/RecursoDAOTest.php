<?php

namespace test\dao;

use dao\RecursoDAO;
use model\Recurso;

class RecursoDAOTest extends DAOTestCase
{
    public function testCrudRecurso(): void
    {
        $token = 'crud_recurso_' . str_replace('.', '', uniqid('', true));
        $projeto = $this->createProjeto();

        $recurso = (new Recurso())
            ->setProjeto($projeto)
            ->setNome('Recurso ' . $token)
            ->setTipo('MATERIAL')
            ->setDescricao('Descricao ' . $token)
            ->setQuantidade(3)
            ->setCustoUnitario(199.99);

        $recursoSalvo = RecursoDAO::salvar($recurso);
        $this->trackEntity($recursoSalvo);

        $this->assertNotNull($recursoSalvo->getId());

        $this->clearEntityManager();
        $recursoBuscado = RecursoDAO::buscarId($recursoSalvo);
        $this->assertInstanceOf(Recurso::class, $recursoBuscado);
        $this->assertSame('Recurso ' . $token, $recursoBuscado->getNome());

        $idsListados = array_map(static fn (Recurso $item): int => $item->getId(), RecursoDAO::listar());
        $this->assertContains($recursoSalvo->getId(), $idsListados);

        $buscaPorNome = RecursoDAO::buscarNome('Recurso ' . $token);
        $this->assertCount(1, $buscaPorNome);

        $recursoBuscado
            ->setTipo('SERVICO')
            ->setQuantidade(5)
            ->setCustoUnitario(250.50);
        RecursoDAO::salvar($recursoBuscado);

        $this->clearEntityManager();
        $recursoAtualizado = RecursoDAO::buscarId($recursoSalvo);
        $this->assertInstanceOf(Recurso::class, $recursoAtualizado);
        $this->assertSame('SERVICO', $recursoAtualizado->getTipo());
        $this->assertSame(5, $recursoAtualizado->getQuantidade());
        $this->assertSame(250.50, $recursoAtualizado->getCustoUnitario());

        RecursoDAO::deletar($recursoAtualizado);
        $this->untrackEntity($recursoSalvo);

        $this->clearEntityManager();
        $this->assertNull(RecursoDAO::buscarId($recursoSalvo));
    }
}
