<?php
$isEdit = $recurso->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
$projetoAtual = $isEdit ? $recurso->getProjeto()->getId() : null;
?>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($projetos)): ?>
    <div class="alert alert-warning">Cadastre um projeto antes de criar recursos.</div>
<?php endif; ?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="recurso" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $this->e($recurso->getNome()); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo *</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <?php foreach ($tipoOptions as $tipo): ?>
                            <option value="<?php echo $this->e($tipo->value); ?>" <?php echo $this->selected($tipo->value, $recurso->getTipo()); ?>>
                                <?php echo $this->e($tipo->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="projetoId" class="form-label">Projeto *</label>
                    <select class="form-select" id="projetoId" name="projetoId" required>
                        <option value="">Selecione</option>
                        <?php foreach ($projetos as $projeto): ?>
                            <option value="<?php echo $this->e($projeto->getId()); ?>" <?php echo $this->selected((string) $projeto->getId(), (string) $projetoAtual); ?>>
                                <?php echo $this->e($projeto->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quantidade" class="form-label">Quantidade *</label>
                    <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" value="<?php echo $this->e($recurso->getQuantidade()); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="custoUnitario" class="form-label">Custo unitario</label>
                    <input type="number" class="form-control" id="custoUnitario" name="custoUnitario" min="0" step="0.01" value="<?php echo $this->e($recurso->getCustoUnitario()); ?>">
                </div>
                <div class="col-md-6">
                    <label for="descricao" class="form-label">Descricao</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $this->e($recurso->getDescricao()); ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('recursos'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php echo empty($projetos) ? 'disabled' : ''; ?>><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
