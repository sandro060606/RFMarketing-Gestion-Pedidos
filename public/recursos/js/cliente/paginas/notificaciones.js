/**
 * 
 * Script para cargar y mostrar notificaciones del cliente
 * Implementa Polling automático: consulta notificaciones cada 15 segundos
 */
async function cargarNotificaciones() {
  const contenedor = document.getElementById("content-notificaciones");
  try {
    // Fetch a endpoint JSON del servidor
    const resp = await fetch(`${base_url}cliente/notificaciones/listar`);
    const data = await resp.json();

    // Si no hay notificaciones → mostrar mensaje vacío y salir
    if (data.length === 0) {
      contenedor.innerHTML =
        '<div class="estado-vacio">No hay notificaciones.</div>';
      return;
    }

    // ACTUALIZAR MÉTRICAS DINÁMICAMENTE 
    // Total de notificaciones
    document.getElementById("cnt-total").innerText = data.length;

    // Contar solo las del tipo "estado"
    document.getElementById("cnt-estado").innerText = data.filter(
      (n) => n.tipoalerta === "estado"
    ).length;

    //  LIMPIAR Y PINTAR NOTIFICACIONES 
    contenedor.innerHTML = "";

    data.forEach((n) => {
      // Obtener icono inteligente según asunto
      const icono = getIcono(n.asunto);

      // Extraer solo la fecha (YYYY-MM-DD) de fechaenvio
      const fecha = n.fechaenvio.substring(0, 10);

      // Generar HTML de la notificación
      contenedor.innerHTML += `
        <div class="noti-card">
          <div class="noti-icono tipo-${n.tipoalerta.toLowerCase()}">${icono}</div>
          <div class="noti-contenido">
            <div class="noti-top">
              <span class="noti-asunto">${n.asunto}</span>
              <span class="noti-fecha">${fecha}</span>
            </div>
            <p class="noti-mensaje">${n.mensaje}</p>
            <div class="noti-pedido"><i class="bi bi-hash"></i> ${n.pedido}</div>
          </div>
        </div>`;
    });
  } catch (e) {
    console.error("Error cargando notificaciones", e);
  }
}

/**
 * 
 * Retorna icono HTML Bootstrap según el asunto de la notificación.
 * Permite visualización más clara del tipo de alerta.
 */
function getIcono(asunto) {
  const s = asunto.toLowerCase();

  // Icono de "en proceso" (rotación, azul)
  if (s.includes("proceso")) {
    return '<i class="bi bi-arrow-repeat" style="color:#60a5fa"></i>';
  }

  // Icono de "completado" (check, verde)
  if (s.includes("completado")) {
    return '<i class="bi bi-check-circle-fill" style="color:#22c55e"></i>';
  }

  // Icono por defecto (campana)
  return '<i class="bi bi-bell-fill"></i>';
}

//  AUTO-EJECUCIÓN 
// Cargar notificaciones cuando se abre la página
document.addEventListener("DOMContentLoaded", cargarNotificaciones);