<?php

namespace test\dao;

use dao\ParticipanteDao;
use model\Participante;

class ParticipanteDaoTest extends DaoTestCase
{
    public function testCrudParticipante(): void
    {
        $token = 'crud_participante_' . str_replace('.', '', uniqid('', true));
        $projeto = $this->createProjeto();
        $usuario = $this->createUsuario();

        $participante = (new Participante())
            ->setProjeto($projeto)
            ->setUsuario($usuario)
            ->setFuncaoNoProjeto('Analista ' . $token)
            ->setPapelAcesso('COORDENADOR')
            ->setAtivo(true);

        $participanteSalvo = ParticipanteDao::salvar($participante);
        $this->trackEntity($participanteSalvo);

        $this->assertNotNull($participanteSalvo->getId());

        $this->clearEntityManager();
        $participanteBuscado = ParticipanteDao::buscarId($participanteSalvo);
        $this->assertInstanceOf(Participante::class, $participanteBuscado);
        $this->assertSame('Analista ' . $token, $participanteBuscado->getFuncaoNoProjeto());

        $idsListados = array_map(static fn (Participante $item): int => $item->getId(), ParticipanteDao::listar());
        $this->assertContains($participanteSalvo->getId(), $idsListados);

        $ativos = ParticipanteDao::buscarAtivos();
        $idsAtivos = array_map(static fn (Participante $item): int => $item->getId(), $ativos);
        $this->assertContains($participanteSalvo->getId(), $idsAtivos);

        $participanteBuscado
            ->setFuncaoNoProjeto('Coordenador ' . $token)
            ->setPapelAcesso('ADMINISTRADOR_PROJETO')
            ->setAtivo(false);
        ParticipanteDao::salvar($participanteBuscado);

        $this->clearEntityManager();
        $participanteAtualizado = ParticipanteDao::buscarId($participanteSalvo);
        $this->assertInstanceOf(Participante::class, $participanteAtualizado);
        $this->assertSame('Coordenador ' . $token, $participanteAtualizado->getFuncaoNoProjeto());
        $this->assertSame('ADMINISTRADOR_PROJETO', $participanteAtualizado->getPapelAcesso());
        $this->assertFalse($participanteAtualizado->getAtivo());

        ParticipanteDao::deletar($participanteAtualizado);
        $this->untrackEntity($participanteSalvo);

        $this->clearEntityManager();
        $this->assertNull(ParticipanteDao::buscarId($participanteSalvo));
    }
}
