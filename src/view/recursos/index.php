<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalNovoRecurso">Novo recurso</button>
</div>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($recursos)): ?>
    <div class="card app-card shadow-sm">
        <div class="card-body text-center py-5">
            <h2 class="h5">Nenhum recurso cadastrado</h2>
            <p class="text-muted mb-0">Cadastre recursos humanos, materiais ou tecnologicos por projeto.</p>
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
                        <dl class="row small mb-0">
                            <dt class="col-6">Quantidade</dt>
                            <dd class="col-6"><?php echo $this->e($recurso->getQuantidade()); ?></dd>
                            <dt class="col-6">Custo unitario</dt>
                            <dd class="col-6">R$ <?php echo $this->e(number_format($recurso->getCustoUnitario(), 2, ',', '.')); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalNovoRecurso" tabindex="-1" aria-labelledby="modalNovoRecursoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?php echo $this->url('recursos/store'); ?>" class="needs-validation-js" data-validar="recurso" novalidate>
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="modalNovoRecursoLabel">Novo recurso</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <?php if (empty($projetos)): ?>
                        <div class="alert alert-warning">Cadastre um projeto antes de criar recursos.</div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <?php foreach ($tipoOptions as $tipo): ?>
                                    <option value="<?php echo $this->e($tipo->value); ?>"><?php echo $this->e($tipo->value); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="projetoId" class="form-label">Projeto *</label>
                            <select class="form-select" id="projetoId" name="projetoId" required>
                                <option value="">Selecione</option>
                                <?php foreach ($projetos as $projeto): ?>
                                    <option value="<?php echo $this->e($projeto->getId()); ?>"><?php echo $this->e($projeto->getNome()); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" value="1">
                        </div>
                        <div class="col-md-6">
                            <label for="custoUnitario" class="form-label">Custo unitario</label>
                            <input type="number" class="form-control" id="custoUnitario" name="custoUnitario" min="0" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="descricao" class="form-label">Descricao</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" <?php echo empty($projetos) ? 'disabled' : ''; ?>>Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
