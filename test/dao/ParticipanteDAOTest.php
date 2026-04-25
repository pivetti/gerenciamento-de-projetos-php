<?php

namespace test\dao;

use dao\ParticipanteDAO;
use model\Participante;

class ParticipanteDAOTest extends DAOTestCase
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

        $participanteSalvo = ParticipanteDAO::salvar($participante);
        $this->trackEntity($participanteSalvo);

        $this->assertNotNull($participanteSalvo->getId());

        $this->clearEntityManager();
        $participanteBuscado = ParticipanteDAO::buscarId($participanteSalvo);
        $this->assertInstanceOf(Participante::class, $participanteBuscado);
        $this->assertSame('Analista ' . $token, $participanteBuscado->getFuncaoNoProjeto());

        $idsListados = array_map(static fn (Participante $item): int => $item->getId(), ParticipanteDAO::listar());
        $this->assertContains($participanteSalvo->getId(), $idsListados);

        $ativos = ParticipanteDAO::buscarAtivos();
        $idsAtivos = array_map(static fn (Participante $item): int => $item->getId(), $ativos);
        $this->assertContains($participanteSalvo->getId(), $idsAtivos);

        $participanteBuscado
            ->setFuncaoNoProjeto('Coordenador ' . $token)
            ->setPapelAcesso('ADMINISTRADOR_PROJETO')
            ->setAtivo(false);
        ParticipanteDAO::salvar($participanteBuscado);

        $this->clearEntityManager();
        $participanteAtualizado = ParticipanteDAO::buscarId($participanteSalvo);
        $this->assertInstanceOf(Participante::class, $participanteAtualizado);
        $this->assertSame('Coordenador ' . $token, $participanteAtualizado->getFuncaoNoProjeto());
        $this->assertSame('ADMINISTRADOR_PROJETO', $participanteAtualizado->getPapelAcesso());
        $this->assertFalse($participanteAtualizado->getAtivo());

        ParticipanteDAO::deletar($participanteAtualizado);
        $this->untrackEntity($participanteSalvo);

        $this->clearEntityManager();
        $this->assertNull(ParticipanteDAO::buscarId($participanteSalvo));
    }
}
