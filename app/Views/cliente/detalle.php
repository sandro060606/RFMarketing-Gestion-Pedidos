<?= $this->section('estilos') ?>
<link rel="stylesheet" href="<?= base_url('recursos/styles/cliente/paginas/detalle_pedido.css') ?>">
<?= $this->endSection() ?>

<?= $this->extend('plantillas/cliente') ?>
<?= $this->section('contenido') ?>

<!-- Encabezado -->
<div class="seccion-titulo" style="font-size:14px;">MIS PEDIDOS</div>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="bebas mb-0" style="font-size:2rem;"><?= session()->get('nombre') ?> <?= session()->get('apellidos') ?></h2>
        <p class="small mb-0" style="color:#aaa;">Cliente — Detalle del requerimiento</p>
    </div>
    <!-- Volver a la lista -->
    <a href="<?= base_url('cliente/') ?>" class="btn-rf-outline">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<!-- ── Fila superior: info principal + datos técnicos -->
<div class="row g-3 mb-4">

    <!-- Tarjeta izquierda: título, objetivo, brief -->
    <div class="col-md-8">
        <div class="card p-4 h-100">
            <!-- Servicio + badges en una línea -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="servicio-tag"><?= esc($pedido['servicio']) ?></span>
                <!-- badge_estado() viene del pedido_helper.php cargado en el controller -->
                <?= badge_estado($pedido['estado']) ?>
                <?php if ($pedido['prioridad']): ?>
                    <?= badge_prioridad($pedido['prioridad']) ?>
                <?php endif; ?>
            </div>

            <!-- Título — si es null muestra texto en cursiva -->
            <h3 class="detalle-titulo mb-1">
                <?= $pedido['titulo']
                    ? esc($pedido['titulo'])
                    : '<em style="color:#666;">Pendiente de revisión</em>' ?>
            </h3>
            <p class="detalle-empresa mb-3"><?= esc($pedido['empresa']) ?></p>
            <div class="detalle-sep mb-3"></div>

            <!-- Campos del formulario original -->
            <div class="detalle-campo">
                <span class="campo-label">OBJETIVO DE COMUNICACIÓN</span>
                <p class="campo-valor"><?= esc($pedido['objetivo_comunicacion'] ?? '—') ?></p>
            </div>
            <div class="detalle-campo">
                <span class="campo-label">PÚBLICO OBJETIVO</span>
                <p class="campo-valor"><?= esc($pedido['publico_objetivo'] ?? '—') ?></p>
            </div>
            <div class="detalle-campo">
                <span class="campo-label">DESCRIPCIÓN / BRIEF</span>
                <p class="campo-valor"><?= esc($pedido['descripcion'] ?? '—') ?></p>
            </div>

        </div>
    </div>
    <!-- Tarjeta derecha: datos técnicos -->
    <div class="col-md-4">
        <div class="card p-4 h-100">

            <p class="campo-label mb-3">INFORMACIÓN DEL PEDIDO</p>
            <!-- Empleado asignado — LEFT JOIN, puede ser null -->
            <div class="info-item">
                <i class="bi bi-person-workspace"></i>
                <div>
                    <span class="info-label">Empleado asignado</span>
                    <span class="info-valor">
                        <?= $pedido['empleado'] ? esc($pedido['empleado']) : 'Sin asignar aún' ?>
                    </span>
                </div>
            </div>
            <!-- Fecha requerida por el cliente en el formulario -->
            <div class="info-item">
                <i class="bi bi-calendar-event"></i>
                <div>
                    <span class="info-label">Fecha requerida</span>
                    <span class="info-valor">
                        <?= $pedido['fecharequerida'] ? formato_fecha($pedido['fecharequerida']) : '—' ?>
                    </span>
                </div>
            </div>
            <!-- Fecha de creación del pedido en el sistema -->
            <div class="info-item">
                <i class="bi bi-calendar-plus"></i>
                <div>
                    <span class="info-label">Fecha de creación</span>
                    <span class="info-valor"><?= formato_fecha($pedido['fechacreacion']) ?></span>
                </div>
            </div>
            <!-- Fecha inicio — solo si ya empezó el trabajo -->
            <?php if ($pedido['fechainicio']): ?>
                <div class="info-item">
                    <i class="bi bi-play-circle"></i>
                    <div>
                        <span class="info-label">Inicio de trabajo</span>
                        <span class="info-valor"><?= formato_fecha($pedido['fechainicio']) ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Fecha completado — solo si está completado -->
            <?php if ($pedido['fechacompletado']): ?>
                <div class="info-item">
                    <i class="bi bi-check-circle" style="color:#22c55e;"></i>
                    <div>
                        <span class="info-label">Completado</span>
                        <span class="info-valor"><?= formato_fecha($pedido['fechacompletado']) ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Número de modificaciones solicitadas -->
            <div class="info-item">
                <i class="bi bi-arrow-repeat"></i>
                <div>
                    <span class="info-label">Modificaciones</span>
                    <span class="info-valor"><?= $pedido['num_modificaciones'] ?? '0' ?></span>
                </div>
            </div>
            <!-- Observación — solo si el admin dejó una nota -->
            <?php if (!empty($pedido['observacion_revision'])): ?>
                <div class="detalle-sep my-3"></div>
                <span class="campo-label">OBSERVACIÓN DE REVISIÓN</span>
                <p class="campo-valor mt-1"><?= esc($pedido['observacion_revision']) ?></p>
            <?php endif; ?>

        </div>
    </div>
</div>
<!-- ── Fila inferior: canales, formatos, tipo, área -->
<div class="row g-3 mb-4">

    <!-- Canales de difusión -->
    <div class="col-md-6">
        <div class="card p-4">
            <p class="campo-label mb-3">CANALES DE DIFUSIÓN</p>
            <div class="tags-wrap">
                <?php $canales = json_decode($pedido['canales_difusion'] ?? '[]', true); ?>
                <?php if (!empty($canales)): ?>
                    <?php foreach ($canales as $canal): ?>
                        <span class="tag-item"><?= esc($canal) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span style="color:#555;">—</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Formatos solicitados -->
    <div class="col-md-6">
        <div class="card p-4">
            <p class="campo-label mb-3">FORMATOS SOLICITADOS</p>
            <div class="tags-wrap">
                <?php $formatos = json_decode($pedido['formatos_solicitados'] ?? '[]', true); ?>
                <?php if (!empty($formatos)): ?>
                    <?php foreach ($formatos as $formato): ?>
                        <span class="tag-item"><?= esc($formato) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span style="color:#555;">—</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Tipo de requerimiento + Área solicitante -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card p-4">
            <p class="campo-label mb-2">TIPO DE REQUERIMIENTO</p>
            <p class="campo-valor mb-0"><?= esc($pedido['tipo_requerimiento'] ?? '—') ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4">
            <p class="campo-label mb-2">ÁREA SOLICITANTE</p>
            <p class="campo-valor mb-0"><?= esc($pedido['area'] ?? '—') ?></p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>