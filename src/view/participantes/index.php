<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('participantes/create'); ?>">Novo participante</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($participantes)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum participante cadastrado</h2>
            <p class="text-muted mb-3">Vincule usuarios existentes aos projetos cadastrados.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('participantes/create'); ?>">Cadastrar participante</a>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Projeto</th>
                        <th>Funcao</th>
                        <th>Papel</th>
                        <th>Status</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participantes as $participante): ?>
                        <tr>
                            <td><?php echo $this->e($participante->getUsuario()->getNome()); ?></td>
                            <td><?php echo $this->e($participante->getProjeto()->getNome()); ?></td>
                            <td><?php echo $this->e($participante->getFuncaoNoProjeto()); ?></td>
                            <td><?php echo $this->e($participante->getPapelAcesso()); ?></td>
                            <td>
                                <span class="badge <?php echo $participante->getAtivo() ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                                    <?php echo $participante->getAtivo() ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('participantes/edit', ['id' => $participante->getId()]); ?>">Editar</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirParticipante<?php echo $this->e($participante->getId()); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($participantes as $participante): ?>
        <div class="modal fade" id="modalExcluirParticipante<?php echo $this->e($participante->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirParticipanteLabel<?php echo $this->e($participante->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirParticipanteLabel<?php echo $this->e($participante->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir o participante <?php echo $this->e($participante->getUsuario()->getNome()); ?> do projeto <?php echo $this->e($participante->getProjeto()->getNome()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('participantes/delete', ['id' => $participante->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
