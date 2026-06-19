<div class="d-flex justify-content-end mb-3">
    <a class="btn btn-primary" href="<?php echo $this->url('recursos/create'); ?>">Novo recurso</a>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($recursos)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum recurso cadastrado</h2>
            <p class="text-muted mb-3">Cadastre recursos humanos, materiais ou tecnologicos por projeto.</p>
            <a class="btn btn-primary" href="<?php echo $this->url('recursos/create'); ?>">Cadastrar recurso</a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($recursos as $recurso): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card app-card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between gap-2">
                            <h2 class="h5 mb-1"><?php echo $this->e($recurso->getNome()); ?></h2>
                            <span class="badge text-bg-info align-self-start"><?php echo $this->e($recurso->getTipo()); ?></span>
                        </div>
                        <p class="text-muted small mb-2"><?php echo $this->e($recurso->getProjeto()->getNome()); ?></p>
                        <p class="mb-3"><?php echo $this->e($recurso->getDescricao() ?? 'Sem descricao'); ?></p>
                        <dl class="row small mb-3">
                            <dt class="col-6">Quantidade</dt>
                            <dd class="col-6"><?php echo $this->e($recurso->getQuantidade()); ?></dd>
                            <dt class="col-6">Custo unitario</dt>
                            <dd class="col-6">R$ <?php echo $this->e(number_format($recurso->getCustoUnitario(), 2, ',', '.')); ?></dd>
                        </dl>
                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="<?php echo $this->url('recursos/edit', ['id' => $recurso->getId()]); ?>">Editar</a>
                            <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#modalExcluirRecurso<?php echo $this->e($recurso->getId()); ?>">Excluir</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($recursos as $recurso): ?>
        <div class="modal fade" id="modalExcluirRecurso<?php echo $this->e($recurso->getId()); ?>" tabindex="-1" aria-labelledby="modalExcluirRecursoLabel<?php echo $this->e($recurso->getId()); ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalExcluirRecursoLabel<?php echo $this->e($recurso->getId()); ?>">Confirmar exclusao</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        Deseja excluir o recurso <?php echo $this->e($recurso->getNome()); ?>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form method="post" action="<?php echo $this->url('recursos/delete', ['id' => $recurso->getId()]); ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
