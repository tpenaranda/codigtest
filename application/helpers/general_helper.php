<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dump helper. Función que vuelca variables en pantalla de una manera bien formateada. Basada en el helper 'Dump Helper' de Joos Van Veen.
 * @author Pablo Andrés Rojo
 * @version 1.0
 */

if (!function_exists('dump')) {
    /**
     * dump
     * Volcado de variable en pantalla, de una manera bien formateada.
     *
     * @var    string  $var    Variable a volcar
     * @var    string  $label  Nombre a mostrar de la variable
     * @var    bool    $echo   Si muestra o devuelve la variable
     * @return string|void     Vuleca la variable o no devuelve nada
     */
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Almacenar volcado en variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Agregar formato
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

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

if (!function_exists('dump_exit')) {
    /**
     * dump_exit
     * Volcado de variable en pantalla, de una manera bien formateada, y termina la ejecución.
     *
     * @var    string  $var    Variable a volcar
     * @var    string  $label  Nombre a mostrar de la variable
     * @return void
     */
    function dump_exit($var, $label = 'Dump') {
        dump ($var, $label, $echo = TRUE);
        exit;
    }
}


