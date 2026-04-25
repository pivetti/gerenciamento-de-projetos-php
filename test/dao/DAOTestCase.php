<?php

namespace test\dao;

use DateTime;
use Doctrine\ORM\EntityManager;
use model\Atividade;
use model\Custo;
use model\GenericModel;
use model\Participante;
use model\Projeto;
use model\Recurso;
use model\Risco;
use model\Usuario;
use PHPUnit\Framework\TestCase;
use Throwable;
use utils\Conexao;

abstract class DAOTestCase extends TestCase
{
    protected EntityManager $entityManager;

    /** @var array<string, array{class: class-string, id: int}> */
    private array $cleanupQueue = [];

    protected function setUp(): void
    {
        parent::setUp();

        try {
            /** @var EntityManager $entityManager */
            $entityManager = Conexao::getEntityManager();
            $entityManager->getConnection()->connect();
            $this->entityManager = $entityManager;
        } catch (Throwable $exception) {
            self::markTestSkipped('Banco indisponivel para os testes DAO: ' . $exception->getMessage());
        }
    }

    protected function tearDown(): void
    {
        if (isset($this->entityManager)) {
            $this->cleanupTrackedEntities();
            $this->entityManager->clear();
        }

        parent::tearDown();
    }

    protected function clearEntityManager(): void
    {
        $this->entityManager->clear();
    }

    protected function trackEntity(GenericModel $entity): void
    {
        if ($entity->getId() === null) {
            return;
        }

        $key = $entity::class . ':' . $entity->getId();
        $this->cleanupQueue[$key] = [
            'class' => $entity::class,
            'id' => $entity->getId(),
        ];
    }

    protected function untrackEntity(GenericModel $entity): void
    {
        if ($entity->getId() === null) {
            return;
        }

        unset($this->cleanupQueue[$entity::class . ':' . $entity->getId()]);
    }

    protected function createProjeto(?string $token = null): Projeto
    {
        $token ??= $this->uniqueToken('projeto');

        $projeto = (new Projeto())
            ->setNome('Projeto ' . $token)
            ->setDescricao('Descricao ' . $token)
            ->setObjetivo('Objetivo ' . $token)
            ->setStatus('PLANEJADO')
            ->setPrioridade('MEDIA')
            ->setDataInicio(new DateTime('2026-03-01'))
            ->setDataFim(new DateTime('2026-12-01'))
            ->setOrcamentoPrevisto(1500.50)
            ->setPercentualConcluido(10);

        $this->persistSupportEntity($projeto);

        return $projeto;
    }

    protected function createUsuario(?string $token = null): Usuario
    {
        $token ??= $this->uniqueToken('usuario');

        $usuario = (new Usuario())
            ->setNome('Usuario ' . $token)
            ->setEmail($token . '@teste.local')
            ->setSenha('senhaSegura123')
            ->setPerfil('ANALISTA')
            ->setTelefone('11999999999');

        $this->persistSupportEntity($usuario);

        return $usuario;
    }

    protected function createParticipante(?Projeto $projeto = null, ?Usuario $usuario = null, ?string $token = null): Participante
    {
        $token ??= $this->uniqueToken('participante');
        $projeto ??= $this->createProjeto();
        $usuario ??= $this->createUsuario();

        $participante = (new Participante())
            ->setProjeto($projeto)
            ->setUsuario($usuario)
            ->setFuncaoNoProjeto('Funcao ' . $token)
            ->setPapelAcesso('EXECUTOR')
            ->setAtivo(true);

        $this->persistSupportEntity($participante);

        return $participante;
    }

    protected function createAtividade(?Projeto $projeto = null, ?Participante $responsavel = null, ?string $token = null): Atividade
    {
        $token ??= $this->uniqueToken('atividade');
        $projeto ??= $this->createProjeto();

        if ($responsavel === null) {
            $responsavel = $this->createParticipante($projeto);
        }

        $atividade = (new Atividade())
            ->setProjeto($projeto)
            ->setResponsavel($responsavel)
            ->setTitulo('Atividade ' . $token)
            ->setDescricao('Descricao da atividade ' . $token)
            ->setStatus('NAO_INICIADA')
            ->setPrioridade('ALTA')
            ->setDataInicio(new DateTime('2026-04-01'))
            ->setPrazo(new DateTime('2026-05-01'))
            ->setDataConclusao(null)
            ->setPercentualConclusao(0);

        $this->persistSupportEntity($atividade);

        return $atividade;
    }

    protected function createRecurso(?Projeto $projeto = null, ?string $token = null): Recurso
    {
        $token ??= $this->uniqueToken('recurso');
        $projeto ??= $this->createProjeto();

        $recurso = (new Recurso())
            ->setProjeto($projeto)
            ->setNome('Recurso ' . $token)
            ->setTipo('TECNOLOGICO')
            ->setDescricao('Descricao do recurso ' . $token)
            ->setQuantidade(2)
            ->setCustoUnitario(300.75);

        $this->persistSupportEntity($recurso);

        return $recurso;
    }

    protected function createRisco(?Projeto $projeto = null, ?string $token = null): Risco
    {
        $token ??= $this->uniqueToken('risco');
        $projeto ??= $this->createProjeto();

        $risco = (new Risco())
            ->setProjeto($projeto)
            ->setTitulo('Risco ' . $token)
            ->setDescricao('Descricao do risco ' . $token)
            ->setCategoria('TECNOLOGIA')
            ->setProbabilidade(3)
            ->setImpacto(4)
            ->setCriticidade(12)
            ->setStatus('IDENTIFICADO')
            ->setEstrategiaResposta('Mitigar rapidamente')
            ->setPlanoMitigacao('Plano de mitigacao ' . $token);

        $this->persistSupportEntity($risco);

        return $risco;
    }

    protected function createCusto(
        ?Projeto $projeto = null,
        ?Atividade $atividade = null,
        ?Recurso $recurso = null,
        ?string $token = null
    ): Custo {
        $token ??= $this->uniqueToken('custo');
        $projeto ??= $this->createProjeto();
        $atividade ??= $this->createAtividade($projeto);
        $recurso ??= $this->createRecurso($projeto);

        $custo = (new Custo())
            ->setProjeto($projeto)
            ->setAtividade($atividade)
            ->setRecurso($recurso)
            ->setDescricao('Custo ' . $token)
            ->setTipo('OPERACIONAL')
            ->setValorPrevisto(450.25)
            ->setValorReal(430.10)
            ->setDataLancamento(new DateTime('2026-04-15'));

        $this->persistSupportEntity($custo);

        return $custo;
    }

    private function persistSupportEntity(GenericModel $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $this->trackEntity($entity);
    }

    private function cleanupTrackedEntities(): void
    {
        $trackedEntities = array_reverse($this->cleanupQueue);
        $this->cleanupQueue = [];

        foreach ($trackedEntities as $trackedEntity) {
            $managedEntity = $this->entityManager->find($trackedEntity['class'], $trackedEntity['id']);

            if ($managedEntity === null) {
                continue;
            }

            $this->entityManager->remove($managedEntity);
            $this->entityManager->flush();
        }
    }

    private function uniqueToken(string $prefix): string
    {
        return $prefix . '_' . str_replace('.', '', uniqid('', true));
    }
}
