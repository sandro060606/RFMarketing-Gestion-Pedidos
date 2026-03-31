<!-- CSS específico de esta página -->
<?= $this->section('estilos') ?>
<link rel="stylesheet" href="<?= base_url('recursos/styles/cliente/paginas/mis_pedidos.css') ?>">
<?= $this->endSection() ?>
<!-- Extiende la plantilla base-->
<?= $this->extend('plantillas/cliente') ?>
<!-- Contenido Principal -->
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
    <!-- Boton para el Modal Servicios -->
    <button class="btn-rf" data-bs-toggle="modal" data-bs-target="#modal-nuevo-pedido">
        <i class="bi bi-plus-lg"></i> Nuevo Pedido
    </button>
</div>

<!-- Métricas resumen: Contadores por estado -->
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

<!-- Tabla de pedidos:Historial de Pedidos -->
<div class="seccion-titulo" style="font-size:14px;">TODOS LOS PEDIDOS</div>
<div class="card" style="overflow:hidden;">
    <!-- Header con buscador -->
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
<!-- Modal Seleccion de Servicios -->
<div class="modal fade" id="modal-nuevo-pedido" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-rf">
            <div class="modal-header modal-rf-header">
                <div>
                    <p class="campo-label mb-1">NUEVO PEDIDO</p>
                    <h5 class="modal-title mb-0">Selecciona el tipo de servicio</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body modal-rf-body p-4">

                <!-- Skeleton -->
                <div id="sk-servicios">
                    <div class="sk-line sk-full mb-3" style="height:80px; border-radius:10px;"></div>
                    <div class="sk-line sk-full" style="height:80px; border-radius:10px;"></div>
                </div>
                <!-- Cards de servicios — JS las pinta -->
                <div id="lista-servicios" style="display:none;">
                    <!-- JS inserta aquí las cards -->
                </div>

            </div>

        </div>
    </div>
</div>
<script>
    const base_url = "<?= base_url() ?>/";
</script>
<?= $this->endSection() ?>
<!-- JS de esta página -->
<?= $this->section('scripts') ?>
<script src="<?= base_url('recursos/js/cliente/paginas/mis-pedidos.js') ?>"></script>
<?= $this->endSection() ?>