<!-- CSS específico de esta página -->
<?= $this->section('estilos') ?>
<link rel="stylesheet" href="<?= base_url('recursos/styles/cliente/paginas/mis_pedidos.css') ?>">
<?= $this->endSection() ?>
<!-- Extiende la plantilla base-->
<?= $this->extend('plantillas/cliente') ?>
<?= $this->section('contenido') ?>

<!-- ── Encabezado de página -->
<div class="seccion-titulo" style="font-size:14px;">MIS PEDIDOS</div>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <!-- Nombre del cliente desde sesión -->
        <h2 class="bebas mb-0" style="font-size:2rem;">
            <?= session()->get('nombre') ?>
        </h2>
        <p class="small mb-0" style="color:#aaa;">Cliente — Historial de requerimientos</p>
    </div>
    <a href="<?= base_url('cliente/nuevo-pedido') ?>" class="btn-rf">
        <i class="bi bi-plus-lg"></i> Nuevo Pedido
    </a>
</div>

<!-- Métricas resumen -->
<div class="seccion-titulo" style="font-size:14px;">RESUMEN</div>
<div class="row g-2 mb-4">
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <div class="met-label">Por Aprobar</div>
            <div class="met-num amarillo" id="cnt-por-aprobar">—</div>
            <div class="met-sub">Pendientes de revisión</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <div class="met-label">En Proceso</div>
            <div class="met-num azul" id="cnt-en-proceso">—</div>
            <div class="met-sub">En curso</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <div class="met-label">Completados</div>
            <div class="met-num verde" id="cnt-completado">—</div>
            <div class="met-sub">Total histórico</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3">
            <div class="met-label">Total</div>
            <div class="met-num" style="color:#f0f0f0" id="cnt-total">—</div>
            <div class="met-sub">Todos los pedidos</div>
        </div>
    </div>
</div>

<!-- Tabla de pedidosh -->
<div class="seccion-titulo" style="font-size:14px;">TODOS LOS PEDIDOS</div>
<div class="card" style="overflow:hidden;">

    <div class="tabla-header">
        <div class="buscador-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="buscador" placeholder="Buscar pedido..." class="input-buscar">
        </div>
    </div>

    <div class="table-responsive">
        <table class="tabla-rf" id="tablaPedidos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <!-- JS inserta aquí las filas con innerHTML += `<tr>...</tr>` -->
            <tbody id="content-pedidos"></tbody>
        </table>
    </div>

</div>

<?= $this->endSection() ?>

<!-- JS de esta página -->
<?= $this->section('scripts') ?>
<script src="<?= base_url('recursos/js/cliente/paginas/mis-pedidos.js') ?>"></script>
<?= $this->endSection() ?>