<?php

/**
 * Genera el badge de estado con clases CSS
 * @param mixed $estado
 * @return string
 */
function badge_estado($estado)
{
    $clase = 'estado-' . strtolower(str_replace(' ', '_', $estado));
    return '<span class="badge-estado ' . $clase . '">' . strtoupper($estado) . '</span>';
}

/**
 * Genera el badge de prioridad
 * @param mixed $prioridad
 * @return string
 */
function badge_prioridad($prioridad)
{
    $clase = 'prio-' . strtolower($prioridad);
    return '<span class="badge-prio ' . $clase . '">' . ucfirst($prioridad) . '</span>';
}

/**
 * Formatea la fecha para que se vea limpia en la tabla
 * @param mixed $fecha
 * @return string
 */
function formato_fecha($fecha)
{
    return date('Y-m-d', strtotime($fecha));
}