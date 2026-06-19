<?php
$isEdit = $risco->getId() !== null;
$action = $this->url($actionRoute, $actionParams);
$projetoAtual = $isEdit ? $risco->getProjeto()->getId() : null;
?>

<?php if ($erroListagem): ?>
    <div class="alert alert-warning"><?php echo $this->e($erroListagem); ?></div>
<?php endif; ?>

<?php if (empty($projetos)): ?>
    <div class="alert alert-warning">Cadastre um projeto antes de criar riscos.</div>
<?php endif; ?>

<div class="card app-card shadow-sm">
    <div class="card-body">
        <form method="post" action="<?php echo $this->e($action); ?>" class="needs-validation-js" data-validar="risco" novalidate>
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="titulo" class="form-label">Titulo *</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $this->e($risco->getTitulo()); ?>" required>
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
                    <label for="categoria" class="form-label">Categoria *</label>
                    <select class="form-select" id="categoria" name="categoria" required>
                        <?php foreach ($categoriaOptions as $categoria): ?>
                            <option value="<?php echo $this->e($categoria->value); ?>" <?php echo $this->selected($categoria->value, $risco->getCategoria()); ?>>
                                <?php echo $this->e($categoria->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status *</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php foreach ($statusOptions as $status): ?>
                            <option value="<?php echo $this->e($status->value); ?>" <?php echo $this->selected($status->value, $risco->getStatus()); ?>>
                                <?php echo $this->e($status->value); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="probabilidade" class="form-label">Probabilidade</label>
                    <input type="number" class="form-control" id="probabilidade" name="probabilidade" min="1" max="5" value="<?php echo $this->e($risco->getProbabilidade()); ?>">
                </div>
                <div class="col-md-2">
                    <label for="impacto" class="form-label">Impacto</label>
                    <input type="number" class="form-control" id="impacto" name="impacto" min="1" max="5" value="<?php echo $this->e($risco->getImpacto()); ?>">
                </div>
                <div class="col-12">
                    <label for="descricao" class="form-label">Descricao</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $this->e($risco->getDescricao()); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="estrategiaResposta" class="form-label">Estrategia de resposta</label>
                    <input type="text" class="form-control" id="estrategiaResposta" name="estrategiaResposta" value="<?php echo $this->e($risco->getEstrategiaResposta()); ?>">
                </div>
                <div class="col-md-6">
                    <label for="planoMitigacao" class="form-label">Plano de mitigacao</label>
                    <textarea class="form-control" id="planoMitigacao" name="planoMitigacao" rows="2"><?php echo $this->e($risco->getPlanoMitigacao()); ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a class="btn btn-outline-secondary" href="<?php echo $this->url('riscos'); ?>">Cancelar</a>
                <button type="submit" class="btn btn-primary" <?php echo empty($projetos) ? 'disabled' : ''; ?>><?php echo $isEdit ? 'Atualizar' : 'Salvar'; ?></button>
            </div>
        </form>
    </div>
</div>
