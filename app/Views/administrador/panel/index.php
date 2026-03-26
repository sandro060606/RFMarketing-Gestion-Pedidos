<?= $this->extend('plantillas/principal') ?>

<?= $this->section('styles') ?>
<link href="<?= base_url('recursos/styles/paginas/panel.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>
<?= $this->section('contenido') ?>

<p class="seccion-titulo">Resumen</p>
<div class="row g-2 mb-3">

    <div class="col-6 col-md-3">
        <div class="card p-3 h-100">
            <div class="met-label">Por Aprobar</div>
            <div class="met-num morado"><?= $porAprobar ?? 0 ?></div>
            <div class="met-sub">Esperando tu decisión</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card p-3 h-100">
            <div class="met-label">Activos</div>
            <div class="met-num amarillo"><?= $activos ?? 0 ?></div>
            <div class="met-sub">En manos del empleado</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card p-3 h-100">
            <div class="met-label">En Revisión</div>
            <div class="met-num naranja"><?= $enRevision ?? 0 ?></div>
            <div class="met-sub">Esperando tu revisión</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card p-3 h-100">
            <div class="met-label">Completados</div>
            <div class="met-num verde"><?= $completados ?? 0 ?></div>
            <div class="met-sub">Total histórico</div>
        </div>
    </div>

</div>

<!-- EMPRESAS -->
<p class="seccion-titulo">Empresas</p>

<?php if (empty($empresas)): ?>
    <div class="estado-vacio">
        <i class="bi bi-building"></i>
        <p>No hay empresas registradas todavía.</p>
    </div>
<?php else: ?>

    <div class="emp-scroll-wrap mb-1" id="empScroll">
        <?php foreach ($empresas as $empresa): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="emp-card h-100" <?= $empresa['color'] ?>;">
                    <div class="emp-head">
                        <div class="emp-inicial" style="background: <?= $empresa['color'] ?>; color: #000;">
                            <?= $empresa['inicial'] ?>
                        </div>
                        <div class="emp-info">
                            <div class="emp-nombre"><?= esc($empresa['nombreempresa']) ?></div>
                            <div class="emp-ruc">RUC <?= esc($empresa['ruc']) ?></div>
                        </div>
                        <?php if ($empresa['por_aprobar'] > 0): ?>
                            <div class="emp-badge ms-auto">
                                <span class="badge-punto" style="background: <?= $empresa['color'] ?>;"></span>
                                <?= $empresa['por_aprobar'] ?> nueva<?= $empresa['por_aprobar'] > 1 ? 's' : '' ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="emp-stats">
                        <div class="emp-stat">
                            <div class="emp-stat-num morado"><?= $empresa['por_aprobar'] ?></div>
                            <div class="emp-stat-label">Por Aprobar</div>
                        </div>
                        <div class="emp-stat">
                            <div class="emp-stat-num amarillo"><?= $empresa['activos'] ?></div>
                            <div class="emp-stat-label">Activos</div>
                        </div>
                        <div class="emp-stat">
                            <div class="emp-stat-num verde"><?= $empresa['completados'] ?></div>
                            <div class="emp-stat-label">Completados</div>
                        </div>
                    </div>

                    <div class="emp-areas">
                        <?php foreach ($areas as $area): ?>
                            <!-- Indicador de pasos  Al hacer click en un área, manda el id de la empresa y el id del área-->
                            <button class="area-btn"
                                onclick="window.location.href='<?= site_url('admin/kanban/' . $empresa['id'] . '/' . $area['id']) ?>'">
                                <?= esc($area['nombre']) ?>
                            </button>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif ?>



<?= $this->endSection() ?>