<?php
$isEdit = $custo->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
$projetoAtual = $isEdit ? $custo->getProjeto()->getId() : null;
$atividadeAtual = $isEdit && $custo->getAtividade() ? $custo->getAtividade()->getId() : null;
$recursoAtual = $isEdit && $custo->getRecurso() ? $custo->getRecurso()->getId() : null;
?>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($projetos)): ?>
    <div class="alert alert-warning">Cadastre um projeto antes de criar custos.</div>
<?php endif; ?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="custo" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="descricao" class="form-label">Descricao *</label>
                    <input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $this->e($custo->getDescricao()); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo *</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <?php foreach ($tipoOptions as $tipo): ?>
                            <option value="<?php echo $this->e($tipo->value); ?>" <?php echo $this->selected($tipo->value, $custo->getTipo()); ?>>
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
                    <label for="valorPrevisto" class="form-label">Valor previsto</label>
                    <input type="number" class="form-control" id="valorPrevisto" name="valorPrevisto" min="0" step="0.01" value="<?php echo $this->e($custo->getValorPrevisto()); ?>">
                </div>
                <div class="col-md-3">
                    <label for="valorReal" class="form-label">Valor real</label>
                    <input type="number" class="form-control" id="valorReal" name="valorReal" min="0" step="0.01" value="<?php echo $this->e($custo->getValorReal()); ?>">
                </div>
                <div class="col-md-3">
                    <label for="dataLancamento" class="form-label">Data lancamento</label>
                    <input type="date" class="form-control" id="dataLancamento" name="dataLancamento" value="<?php echo $this->inputDate($custo->getDataLancamento()); ?>">
                </div>
                <div class="col-md-3">
                    <label for="atividadeId" class="form-label">Atividade</label>
                    <select class="form-select" id="atividadeId" name="atividadeId">
                        <option value="">Sem atividade</option>
                        <?php foreach ($atividades as $atividade): ?>
                            <option value="<?php echo $this->e($atividade->getId()); ?>" <?php echo $this->selected((string) $atividade->getId(), (string) $atividadeAtual); ?>>
                                <?php echo $this->e($atividade->getTitulo()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="recursoId" class="form-label">Recurso</label>
                    <select class="form-select" id="recursoId" name="recursoId">
                        <option value="">Sem recurso</option>
                        <?php foreach ($recursos as $recurso): ?>
                            <option value="<?php echo $this->e($recurso->getId()); ?>" <?php echo $this->selected((string) $recurso->getId(), (string) $recursoAtual); ?>>
                                <?php echo $this->e($recurso->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('custos'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php echo empty($projetos) ? 'disabled' : ''; ?>><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
