<!-- Plantilla Heredada -->
<?= $this->extend('plantillas/cliente') ?>
<!--  Cargar CSS  -->
<?= $this->section('estilos') ?>
<link rel="stylesheet" href="<?= base_url('recursos/styles/cliente/paginas/notificaciones.css') ?>">
<?= $this->endSection() ?>
<!-- ── Contenido principal -->
<?= $this->section('contenido') ?>
<div class="seccion-titulo">MI CUENTA</div>

<!-- Encabezado con título y métricas -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="bebas mb-0" style="font-size:1.2rem; letter-spacing:3px; color:#aaa;">CENTRO DE</p>
        <h2 class="bebas mb-0" style="font-size:3.8rem; letter-spacing:2px; line-height:0.9;">NOTIFICACIONES</h2>
    </div>
    
    <!-- Métricas y botón de refresh -->
    <div class="d-flex gap-4 align-items-center" id="metricas-noti">
        <!-- Contador de notificaciones por tipo "estado" -->
        <div class="text-center">
            <div class="bebas counter" id="cnt-estado" style="font-size:3.2rem; color:#F5C400;">0</div>
            <div style="font-size:11px; color:#666;">ESTADO</div>
        </div>
        
        <!-- Contador total de notificaciones -->
        <div class="text-center">
            <div class="bebas counter" id="cnt-total" style="font-size:3.2rem; color:#f0f0f0;">0</div>
            <div style="font-size:11px; color:#666;">TOTAL</div>
        </div>
        
        <!-- Botón de refresh manual -->
        <button class="btn btn-sm btn-outline-warning" onclick="cargarNotificaciones()" title="Actualizar notificaciones">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>
</div>

<!-- Sección de historial -->
<div class="seccion-titulo">HISTORIAL</div>

<!-- Contenedor JS insertará las notificaciones dinámicamente -->
<div id="content-notificaciones" class="noti-lista">
    <!-- Aquí las notificaciones vía JS -->
</div>

<?= $this->endSection() ?>

<!--  Cargar scripts -->
<?= $this->section('scripts') ?>
<script>
    const base_url = "<?= base_url() ?>/";
</script>
<script src="<?= base_url('recursos/js/cliente/paginas/notificaciones.js') ?>"></script>
<?= $this->endSection() ?>