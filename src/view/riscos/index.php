<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('riscos/create'); ?>">Novo risco</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($riscos)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum risco cadastrado</h2>
            <p class="text-muted mb-3">Registre riscos para acompanhar probabilidade, impacto e criticidade.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('riscos/create'); ?>">Cadastrar risco</a>
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
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Prob.</th>
                        <th>Impacto</th>
                        <th>Crit.</th>
                        <th class="text-end">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riscos as $risco): ?>
                        <tr>
                            <td>
                                <strong><?php echo $this->e($risco->getTitulo()); ?></strong>
                                <div class="text-muted small"><?php echo $this->e($risco->getDescricao() ?? 'Sem descricao'); ?></div>
                            </td>
                            <td><?php echo $this->e($risco->getProjeto()->getNome()); ?></td>
                            <td><?php echo $this->e($risco->getCategoria()); ?></td>
                            <td><span class="badge text-bg-secondary"><?php echo $this->e($risco->getStatus()); ?></span></td>
                            <td><?php echo $this->e($risco->getProbabilidade()); ?></td>
                            <td><?php echo $this->e($risco->getImpacto()); ?></td>
                            <td><?php echo $this->e($risco->getCriticidade()); ?></td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('riscos/edit', ['id' => $risco->getId()]); ?>">Editar</a>
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirRisco<?php echo $this->e($risco->getId()); ?>">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php foreach ($riscos as $risco): ?>
        <div class="modal fade" id="modalExcluirRisco<?php echo $this->e($risco->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirRiscoLabel<?php echo $this->e($risco->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirRiscoLabel<?php echo $this->e($risco->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir o risco <?php echo $this->e($risco->getTitulo()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('riscos/delete', ['id' => $risco->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
