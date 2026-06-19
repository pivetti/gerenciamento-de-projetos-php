<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('atividades/create'); ?>">Nova atividade</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($atividades)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhuma atividade cadastrada</h2>
            <p class="text-muted mb-3">Cadastre atividades vinculadas a projetos existentes.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('atividades/create'); ?>">Cadastrar atividade</a>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Titulo</th>
                        <th>Projeto</th>
                        <th>Status</th>
                        <th>Prioridade</th>
                        <th>Prazo</th>
                        <th>Conclusao</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atividades as $atividade): ?>
                        <tr>
                            <td>
                                <strong><?php echo $this->e($atividade->getTitulo()); ?></strong>
                                <div class="text-muted small"><?php echo $this->e($atividade->getDescricao() ?? 'Sem descricao'); ?></div>
                            </td>
                            <td><?php echo $this->e($atividade->getProjeto()->getNome()); ?></td>
                            <td><span class="badge text-bg-secondary"><?php echo $this->e($atividade->getStatus()); ?></span></td>
                            <td><?php echo $this->e($atividade->getPrioridade()); ?></td>
                            <td><?php echo $this->formatDate($atividade->getPrazo()); ?></td>
                            <td style="min-width: 140px;">
                                <div class="progress" role="progressbar" aria-valuenow="<?php echo $this->e($atividade->getPercentualConclusao()); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: <?php echo $this->e($atividade->getPercentualConclusao()); ?>%">
                                        <?php echo $this->e($atividade->getPercentualConclusao()); ?>%
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('atividades/edit', ['id' => $atividade->getId()]); ?>">Editar</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirAtividade<?php echo $this->e($atividade->getId()); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($atividades as $atividade): ?>
        <div class="modal fade" id="modalExcluirAtividade<?php echo $this->e($atividade->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirAtividadeLabel<?php echo $this->e($atividade->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirAtividadeLabel<?php echo $this->e($atividade->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir a atividade <?php echo $this->e($atividade->getTitulo()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('atividades/delete', ['id' => $atividade->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
