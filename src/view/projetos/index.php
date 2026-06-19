<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('projetos/create'); ?>">Novo projeto</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($projetos)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum projeto cadastrado</h2>
            <p class="text-muted mb-3">Use o botao de novo projeto para iniciar o cadastro.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('projetos/create'); ?>">Cadastrar projeto</a>
        </div>
    </div>
<?php else: ?>
    <div class="card app-card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Prioridade</th>
                        <th>Inicio</th>
                        <th>Fim</th>
                        <th>Conclusao</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projetos as $projeto): ?>
                        <tr>
                            <td>
                                <strong><?php echo $this->e($projeto->getNome()); ?></strong>
                                <div class="text-muted small"><?php echo $this->e($projeto->getDescricao() ?? 'Sem descricao'); ?></div>
                            </td>
                            <td><span class="badge text-bg-secondary"><?php echo $this->e($projeto->getStatus()); ?></span></td>
                            <td><?php echo $this->e($projeto->getPrioridade()); ?></td>
                            <td><?php echo $this->formatDate($projeto->getDataInicio()); ?></td>
                            <td><?php echo $this->formatDate($projeto->getDataFim()); ?></td>
                            <td style="min-width: 160px;">
                                <div class="progress" role="progressbar" aria-valuenow="<?php echo $this->e($projeto->getPercentualConcluido()); ?>" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar" style="width: <?php echo $this->e($projeto->getPercentualConcluido()); ?>%">
                                        <?php echo $this->e($projeto->getPercentualConcluido()); ?>%
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('projetos/edit', ['id' => $projeto->getId()]); ?>">Editar</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirProjeto<?php echo $this->e($projeto->getId()); ?>">Excluir</button>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($projetos as $projeto): ?>
        <div class="modal fade" id="modalExcluirProjeto<?php echo $this->e($projeto->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirProjetoLabel<?php echo $this->e($projeto->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirProjetoLabel<?php echo $this->e($projeto->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir o projeto <?php echo $this->e($projeto->getNome()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('projetos/delete', ['id' => $projeto->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
