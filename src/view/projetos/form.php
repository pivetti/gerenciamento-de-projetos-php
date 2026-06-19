<?php
$isEdit = $projeto->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="projeto" novalidate>
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="nome" class="form-label">Nome *</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $this->e($projeto->getNome()); ?>" required>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($statusOptions as $status): ?>
                            <option value="<?php echo $this->e($status->value); ?>" <?php echo $this->selected($status->value, $projeto->getStatus()); ?>>
                                <?php echo $this->e($status->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="prioridade" class="form-label">Prioridade *</label>
                    <select class="form-select" id="prioridade" name="prioridade" required>
                        <?php foreach ($prioridadeOptions as $prioridade): ?>
                            <option value="<?php echo $this->e($prioridade->value); ?>" <?php echo $this->selected($prioridade->value, $projeto->getPrioridade()); ?>>
                                <?php echo $this->e($prioridade->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="descricao" class="form-label">Descricao</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4"><?php echo $this->e($projeto->getDescricao()); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="objetivo" class="form-label">Objetivo</label>
                    <textarea class="form-control" id="objetivo" name="objetivo" rows="4"><?php echo $this->e($projeto->getObjetivo()); ?></textarea>
                </div>
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Data inicio</label>
                    <input type="date" class="form-control" id="dataInicio" name="dataInicio" value="<?php echo $this->inputDate($projeto->getDataInicio()); ?>">
                </div>
                <div class="col-md-3">
                    <label for="dataFim" class="form-label">Data fim</label>
                    <input type="date" class="form-control" id="dataFim" name="dataFim" value="<?php echo $this->inputDate($projeto->getDataFim()); ?>">
                </div>
                <div class="col-md-3">
                    <label for="orcamentoPrevisto" class="form-label">Orcamento previsto</label>
                    <input type="number" class="form-control" id="orcamentoPrevisto" name="orcamentoPrevisto" step="0.01" min="0" value="<?php echo $this->e($projeto->getOrcamentoPrevisto()); ?>">
                </div>
                <div class="col-md-3">
                    <label for="percentualConcluido" class="form-label">Percentual concluido</label>
                    <input type="number" class="form-control" id="percentualConcluido" name="percentualConcluido" min="0" max="100" value="<?php echo $this->e($projeto->getPercentualConcluido()); ?>">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('projetos'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
