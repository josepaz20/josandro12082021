<?php

// ********************** MODULO CAMBIO TITULAR **********************
//require_once('../permisos/modelo.php');

$diccionario = array(
    'subtitulo' => array(
        vINDEX => 'Listado de Cambios de Titular',
    ),
    'form_actions' => array(
    )
);

function render_dinamico_datos($html, $data) {
    foreach ($data as $clave => $valor) {
        $html = str_replace('{' . $clave . '}', $valor, $html);
    }
    return $html;
}

function getPlantilla($form = '') {
    $archivo = '../../' . PUBLICO . $form . '.html';
    $template = file_get_contents($archivo);
    return $template;
}

function verVista($vista = '', $datos = array()) {

//    verificarPermisos($vista);

    global $diccionario;
    global $tablaCambios;
    global $titulo;

    $html = getPlantilla('plantilla');

    $html = str_replace('{user_name}', $_SESSION['NOMBRES_APELLIDO_USUARIO'], $html);
    $html = str_replace('{user_charge}', $_SESSION['CARGO_USUARIO'], $html);
    $html = str_replace('{titulo}', $titulo, $html);
    $html = str_replace('{subtitulo}', $diccionario['subtitulo'][$vista], $html);
    $html = str_replace('{contenido}', getPlantilla($vista), $html);
    $html = str_replace('{tablaCambiosTitular}', $tablaCambios, $html);

    $html = render_dinamico_datos($html, $diccionario['form_actions']);
    $html = render_dinamico_datos($html, $datos);

    print $html;
}

function verVistaAjax($vista = '', $datos = array()) {
    return render_dinamico_datos(getPlantilla($vista), $datos);
}

function setTablaCambiosTitular($datos = array()) {
    global $tablaCambios;
    global $titulo;
    foreach ($datos as $registro) {
        $tablaCambios .= '<tr>';
        $tablaCambios .= '<td>' . $registro['idCambioTitular'] . '</td>';
        $tablaCambios .= '<td>';
        $tablaCambios .= '<a href="javascript:verDetalle(' . $registro['idCambioTitular'] . ')" title="VER DETALLE"><i class="fa fa-eye"></i></a>';
        $tablaCambios .= '&nbsp;&nbsp;';
        $tablaCambios .= '<a href="javascript:verEliminar(' . $registro['idCambioTitular'] . ')" title="VER ELIMINAR"><i class="fa fa-trash"></i></a>';
        $tablaCambios .= '&nbsp;&nbsp;';
        $tablaCambios .= '<a href="javascript:verAprobar(' . $registro['idCambioTitular'] . ')" title="VER APROBAR"><i class="fa fa-thumbs-o-up"></i></a>';
        $tablaCambios .= '</td>';
        $tablaCambios .= '<td>' . strtoupper($registro['conceptoFacturacion']) . '</td>';
        $tablaCambios .= '<td>' . strtoupper($registro['clienteAntiguo']) . '</td>';
        $tablaCambios .= '<td>' . $registro['identificacionAntiguo'] . '</td>';
        $tablaCambios .= '<td>' . strtoupper($registro['clienteNuevo']) . '</td>';
        $tablaCambios .= '<td>' . $registro['identificacionNuevo'] . '</td>';
        $tablaCambios .= '<td>' . $registro['estado'] . '</td>';
        $tablaCambios .= '<td>' . $registro['registradopor'] . '</td>';
        $tablaCambios .= '<td>' . $registro['fechahorareg'] . '</td>';
        $tablaCambios .= '</tr>';
    }
    $titulo = 'CAMBIOS DE TITULAR';
}

function setTablaClientes($datos = array()) {
    $tabla = '';
    foreach ($datos as $registro) {
        $tabla .= '<tr>';
        $tabla .= '<td>' . $registro['idCliente'] . '</td>';
        $tabla .= '<td><a href="javascript:seleccionarCliente(' . $registro['idCliente'] . ')" title="SELECCIONAR ESTE CLIENTE" style="color: blue">' . strtoupper($registro['cliente']) . '</a></td>';
        $tabla .= '<td>' . $registro['identificacion'] . '</td>';
        $tabla .= '</tr>';
    }
    return $tabla;
}

function setListaServicios($datos = array()) {
    $lista = '<option value="">Seleccione...</option>';
    foreach ($datos as $registro) {
        $lista .= '<option value="' . $registro['idServicio'] . '">' . $registro['conceptoFacturacion'] . '</option>';
    }
    return $lista;
}

?>
