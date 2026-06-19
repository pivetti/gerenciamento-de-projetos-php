<?php
$isEdit = $atividade->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
$projetoAtual = $isEdit ? $atividade->getProjeto()->getId() : null;
$responsavelAtual = $isEdit && $atividade->getResponsavel() ? $atividade->getResponsavel()->getId() : null;
?>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($projetos)): ?>
    <div class="alert alert-warning">Cadastre um projeto antes de criar atividades.</div>
<?php endif; ?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="atividade" novalidate>
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="titulo" class="form-label">Titulo *</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $this->e($atividade->getTitulo()); ?>" required>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($statusOptions as $status): ?>
                            <option value="<?php echo $this->e($status->value); ?>" <?php echo $this->selected($status->value, $atividade->getStatus()); ?>>
                                <?php echo $this->e($status->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="prioridade" class="form-label">Prioridade *</label>
                    <select class="form-select" id="prioridade" name="prioridade" required>
                        <?php foreach ($prioridadeOptions as $prioridade): ?>
                            <option value="<?php echo $this->e($prioridade->value); ?>" <?php echo $this->selected($prioridade->value, $atividade->getPrioridade()); ?>>
                                <?php echo $this->e($prioridade->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="responsavelId" class="form-label">Responsavel</label>
                    <select class="form-select" id="responsavelId" name="responsavelId">
                        <option value="">Sem responsavel</option>
                        <?php foreach ($participantes as $participante): ?>
                            <option value="<?php echo $this->e($participante->getId()); ?>" <?php echo $this->selected((string) $participante->getId(), (string) $responsavelAtual); ?>>
                                <?php echo $this->e($participante->getUsuario()->getNome()); ?> - <?php echo $this->e($participante->getProjeto()->getNome()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="dataInicio" class="form-label">Data inicio</label>
                    <input type="date" class="form-control" id="dataInicio" name="dataInicio" value="<?php echo $this->inputDate($atividade->getDataInicio()); ?>">
                </div>
                <div class="col-md-4">
                    <label for="prazo" class="form-label">Prazo</label>
                    <input type="date" class="form-control" id="prazo" name="prazo" value="<?php echo $this->inputDate($atividade->getPrazo()); ?>">
                </div>
                <div class="col-md-4">
                    <label for="dataConclusao" class="form-label">Data conclusao</label>
                    <input type="date" class="form-control" id="dataConclusao" name="dataConclusao" value="<?php echo $this->inputDate($atividade->getDataConclusao()); ?>">
                </div>
                <div class="col-md-4">
                    <label for="percentualConclusao" class="form-label">Conclusao (%)</label>
                    <input type="number" class="form-control" id="percentualConclusao" name="percentualConclusao" min="0" max="100" value="<?php echo $this->e($atividade->getPercentualConclusao()); ?>">
                </div>
                <div class="col-12">
                    <label for="descricao" class="form-label">Descricao</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4"><?php echo $this->e($atividade->getDescricao()); ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('atividades'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php echo empty($projetos) ? 'disabled' : ''; ?>><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
