<?php if ($databaseWarning): ?>
    <div class="alert alert-warning">
        <?php echo $this->e($databaseWarning); ?>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <?php foreach ($totais as $label => $total): ?>
        <div class="col-12 col-sm-6 col-lg">
            <div class="card app-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1"><?php echo $this->e($label); ?></p>
                    <p class="display-6 fw-semibold mb-0"><?php echo $this->e($total); ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-4">
        <div class="card app-card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5">Projetos</h2>
                <p class="text-muted">Cadastre e acompanhe os projetos principais.</p>
                <a class="btn btn-primary" href="<?php echo $this->url('projetos'); ?>">Abrir projetos</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card app-card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5">Atividades</h2>
                <p class="text-muted">Consulte atividades vinculadas aos projetos.</p>
                <a class="btn btn-outline-primary" href="<?php echo $this->url('atividades'); ?>">Abrir atividades</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="card app-card shadow-sm h-100">
            <div class="card-body">
                <h2 class="h5">Riscos</h2>
                <p class="text-muted">Registre riscos e criticidade do projeto.</p>
                <a class="btn btn-outline-primary" href="<?php echo $this->url('riscos'); ?>">Abrir riscos</a>
            </div>
        </div>
    </div>
</div>
