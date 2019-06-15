<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Widget helper. Helper para traer los valores de los widgets del sistema.
 * @author Pablo Andrés Rojo
 * @version 1.0
 */



if (!function_exists('cantOrdenesServicio')) {
    /**
     * Muestra cantidad de ordenes en curso.
     *
     * @var    Bool    $echo   Si muestra o devuelve la variable.
     * @return Void|String     Imprime la cantidad o la devuelve.
     */
    function cantOrdenesServicio($echo = TRUE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Widget');
        // Call a function of the model
        $output = $CI->Widget->getCantidad('orden_trabajo');
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

// --------------------------------------------------------------------

if (!function_exists('cantOrdenesEnCurso')) {
    /**
     * Muestra cantidad de ordenes en curso.
     *
     * @var    Bool    $echo   Si muestra o devuelve la variable.
     * @return Void|String     Imprime la cantidad o la devuelve.
     */
    function cantOrdenesEnCurso($echo = TRUE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Widget');
        // Call a function of the model
        $output = $CI->Widget->getCantidadWhere('orden_trabajo', 'estado', 'C');
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

// --------------------------------------------------------------------

if (!function_exists('cantOrdenesVencidas')) {
    /**
     * Muestra cantidad de ordenes en curso.
     *
     * @var    Bool    $echo   Si muestra o devuelve la variable.
     * @return Void|String     Imprime la cantidad o la devuelve.
     */
    function cantOrdenesVencidas($echo = TRUE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Widget');
        // Call a function of the model
        $output = $CI->Widget->getCantidadWhere('orden_trabajo', 'estado', 'V');
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

// --------------------------------------------------------------------

if (!function_exists('cantOrdenesAsignadas')) {
    /**
     * Muestra cantidad de ordenes en curso.
     *
     * @var    Bool    $echo   Si muestra o devuelve la variable.
     * @return Void|String     Imprime la cantidad o la devuelve.
     */
    function cantOrdenesAsignadas($echo = TRUE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Widget');
        // Call a function of the model
        $output = $CI->Widget->getCantidadWhere('orden_trabajo', 'estado', 'As');
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

// --------------------------------------------------------------------

if (!function_exists('cantOrdenesCritico')) {
    /**
     * Muestra cantidad de ordenes en curso.
     *
     * @var    Bool    $echo   Si muestra o devuelve la variable.
     * @return Void|String     Imprime la cantidad o la devuelve.
     */
    function cantOrdenesCritico($echo = TRUE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Widget');
        // Call a function of the model
        $output = $CI->Widget->getCantidadWhere('orden_trabajo', 'estado', 'Cr');
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}





// --------------------------------------------------------------------

if (!function_exists('cantTipoOrdenTrabajo')) {
    /**
     *
     *
     */
    function cantTipoOrdenTrabajo($echo = FALSE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Otrabajos');
        // Call a function of the model
        $output = $CI->Otrabajos->kpiCantTipoOrdenTrabajo();
        // En DB tabla orden_trabajo, en el campo tipo 
            // 1 = correctivo
            // 2 = preventivo
            // 3 = predictivo
            // 4 = backlog
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

if (!function_exists('sacarEquiposOperativos')) {
    /**
     *
     *
     */
    function sacarEquiposOperativos($echo = FALSE)
    {
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Equipos');
        // Call a function of the model
        $output = $CI->Equipos->kpiSacarEquiposOperativos();
        /* En DB tabla equipo, en el campo estado */
            // AC = activo
            // AN = anulado
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

if (!function_exists('calcularDisponibilidad')) {
    /**
     *
     *
     */
    function calcularDisponibilidad($idEquipo, $fechaInicio=false, $fechaFin=false, $echo = FALSE)
    {
        //dump($fechaFin);
        // Get a reference to the controller object
        $CI = get_instance();
        // You may need to load the model if it hasn't been pre-loaded
        $CI->load->model('Equipos');
        // Call a function of the model
        $output = $CI->Equipos->kpiCalcularDisponibilidad($idEquipo, $fechaInicio, $fechaFin);
        //dump($output);
        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}
