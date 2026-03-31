document.addEventListener("DOMContentLoaded", function () {
  // Referencias al DOM
  const tabla = document.getElementById("content-pedidos"); //
  const buscador = document.getElementById("buscador"); //
  const skeleton = document.getElementById("sk-servicios"); //
  const lista = document.getElementById("lista-servicios"); //
  const modal = document.getElementById("modal-nuevo-pedido"); //

  // Iconos y colores por servicio
  const servicioConfig = {
    Diseño: { icono: "bi-palette", color: "#f5c400" },
    AudioVisual: { icono: "bi-camera-video", color: "#60a5fa" },
  };


  /**
   * Obtiene servicios activos desde el backend y los pinta como cards
   */
  async function cargarServicios() {
    // Mostrar skeleton (animación de barras grises)
    skeleton.style.display = "block";
    // Ocultar lista anterior (si existía)
    lista.style.display = "none";
    // Limpiar HTML anterior
    lista.innerHTML = "";

    try {   
      //Solicitar servicios al backend 
      const res = await fetch(`${base_url}cliente/nuevo-pedido/servicios`);
      // Validar respuesta HTTP
      if (res.status !== 200){ throw new Error("Error al cargar");}
      // Convertir respuesta HTTP a array de objetos JavaScript
      const data = await res.json();
      // Pintar Cards de Servicios
      data.forEach((s) => {
        // Obtener (icono, color) del servicio desde servicioConfig, Si no existe en config → usar defaults (caja gris)
        const cfg = servicioConfig[s.nombre] ?? {
          icono: "bi-box",
          color: "#888",
        };
        
        // Generar HTML del Card
        lista.innerHTML += `
            <div class="servicio-card" onclick="elegirServicio(${s.id})">
                <!-- Icono del servicio coloreado -->
                <div class="servicio-card-icon" style="color:${cfg.color};">
                    <i class="bi ${cfg.icono}"></i>
                </div>
                <!-- Información del servicio -->
                <div class="servicio-card-info">
                    <p class="servicio-card-nombre">${s.nombre}</p>
                    <p class="servicio-card-desc">${s.descripcion ?? ""}</p>
                </div>
                <!-- Indicador: ir al siguiente paso -->
                <i class="bi bi-arrow-right servicio-card-arrow"></i>
            </div>`;
      });

      //  TRANSICIÓN: SKELETON → LISTA
      skeleton.style.display = "none";
      // Mostrar lista de cards
      lista.style.display = "block";
      
    } catch (e) {
      // Si hay error HTTP o JSON parsing → mostrar mensaje de error
      console.error("Error al cargar servicios:", e);
      // Mostrar mensaje de error en el modal
      lista.innerHTML = `<p style="color:#555; text-align:center;">Error al cargar servicios</p>`;
    }
  }

  // Se ejecuta cuando el modal termina de abrirse
  modal.addEventListener("shown.bs.modal", function () { cargarServicios(); });

  // Redirige al wizard con el id del servicio elegido
  function elegirServicio(idServicio) {
    window.location.href = `/index.php/cliente/nuevo-pedido/${idServicio}`;
  }

  // OBTENER Y PINTAR LA LISTA DE PEDIDOS
  async function obtenerPedidos() {
    try {
      // El servidor ejecuta MisPedidosController::listarPedido() y devuelve un array JSON(Pedidos))
      const response = await fetch("/index.php/cliente/pedidos/listar");
      if (response.status !== 200) return;
      // Convertir la respuesta JSON a un array de objetos JavaScript
      const data = await response.json();
      // Si no llegaron datos → salir
      if (!data) {
        return;
      }
      // Actualizar los contadores del RESUMEN
      // filter() crea un array nuevo con solo los que cumplen la condición | .length cuenta cuántos quedaron
      document.getElementById("cnt-por-aprobar").textContent = data.filter(
        (p) => p.estado === "por_aprobar",
      ).length;
      document.getElementById("cnt-en-proceso").textContent = data.filter(
        (p) => p.estado === "en_proceso",
      ).length;
      document.getElementById("cnt-completado").textContent = data.filter(
        (p) => p.estado === "completado",
      ).length;
      // Total = todos los pedidos sin filtro
      document.getElementById("cnt-total").textContent = data.length;
      // ── Limpiar la tabla antes de pintar
      tabla.innerHTML = "";

      // Si no hay pedidos → mostrar mensaje vacío
      if (data.length === 0) {
        tabla.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align:center; color:#555; padding:30px;">
                            Sin pedidos registrados
                        </td>
                    </tr>`;
        return;
      }

      // Pintar las filas - El número empieza en data.length y baja con numero-- (Mas Reciente)
      let numero = data.length;
      data.forEach((p) => {
        tabla.innerHTML += `
                <tr data-numero="${numero}">
                    <!-- Número inverso -->
                    <td style="color:#555; font-size:11px;">#${numero--}</td>
                    <!-- Título — si es null muestra "Pendiente de revisión" -->
                    <td>
                        ${
                          p.titulo
                            ? `<span style="font-weight:600; font-size:13px;">${p.titulo}</span>`
                            : `<span style="color:#777; font-style:italic;">Pendiente de revisión</span>`
                        }
                    </td>
                    <!-- Servicio (Diseño / AudioVisual) -->
                    <td>${p.servicio}</td>
                    <!-- Badge de estado — generado por badgeEstado() -->
                    <td>${badgeEstado(p.estado)}</td>
                    <!-- Badge de prioridad — si es null muestra guión -->
                    <td>${
                      p.prioridad
                        ? badgePrioridad(p.prioridad)
                        : '<span style="color:#555">—</span>'
                    }
                    </td>
                    <!-- Fecha de creación -->
                    <td style="color:#777; font-size:11px;">
                        ${p.fechacreacion?.substring(0, 10) ?? "—"}
                    </td>
                    <!-- Botón que redirige a la vista de detalle (PHP clásico) -->
                    <td>
                        <a href="/index.php/cliente/pedidos/detalle/${p.id}"
                           class="btn-ver" title="Ver detalle">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>`;
      });
    } catch (e) {
      console.error("Error al obtener pedidos:", e);
    }
  }

  // BUSCADOR
  if (buscador) {
    buscador.addEventListener("keyup", function () {
      // Limpiar el texto: quitar espacios y convertir a minúsculas
      const termino = this.value.trim().toLowerCase();
      // Obtener todas las filas actuales de la tabla
      const filas = document.querySelectorAll("#tablaPedidos tbody tr");
      filas.forEach(function (fila) {
        // Búsqueda por NÚMERO (#1, #2, #3)
        if (/^\d+$/.test(termino)) {
          // data-numero es el atributo que pusimos en el <tr>
          const numero = fila.dataset.numero || "";
          // Mostrar si coincide, ocultar si no
          fila.style.display = numero === termino ? "" : "none";
          // Búsqueda por TEXTO (título, servicio, estado)
        } else {
          // nth-child(2) = columna Título
          const titulo =
            fila.querySelector("td:nth-child(2)")?.textContent.toLowerCase() ||
            "";
          // nth-child(3) = columna Servicio
          const servicio =
            fila.querySelector("td:nth-child(3)")?.textContent.toLowerCase() ||
            "";
          // nth-child(4) = columna Estado
          const estado =
            fila.querySelector("td:nth-child(4)")?.textContent.toLowerCase() ||
            "";
          // Mostrar si el término aparece en CUALQUIERA de los 3 campos
          fila.style.display =
            titulo.includes(termino) ||
            servicio.includes(termino) ||
            estado.includes(termino)
              ? ""
              : "none";
        }
      });
    });
  }

  //  Auto-ejecución al cargar la página
  obtenerPedidos();
});

// Equivalentes a las del pedido_helper.php pero en JavaScript

/**
 * Genera HTML de badge coloreado según el estado del pedido
 */
function badgeEstado(estado) {
  // Generar nombre de clase CSS: 'estado-' + estado en minúsculas
  const clase = "estado-" + estado.toLowerCase();
  
  // Generar texto visible:
  // toUpperCase(): convertir a MAYÚSCULAS - replace("_", " "): reemplazar guión bajo por espacio
  return `<span class="badge-estado ${clase}">${estado.toUpperCase().replace("_", " ")}</span>`;
}

/**
 * Genera HTML de badge coloreado según la prioridad del pedido
 */
function badgePrioridad(prio) {
  // Generar nombre de clase CSS: 'prio-' + prioridad en minúsculas
  const clase = "prio-" + prio.toLowerCase();

  // Generar texto visible: Primera letra mayúscula + resto minúsculas
  return `<span class="badge-prio ${clase}">${prio.charAt(0).toUpperCase() + prio.slice(1)}</span>`;
}
