<?php

// ********************** MODULO CAMBIOS TITULAR **********************

session_name('SW2CLICK');
session_start();
require_once('../../servicios/sesionOK.php');
require_once('../../servicios/evitarInyeccionSQL.php');
require_once('constantes.php');
require_once('vista.php');
require_once('modelo.php');

//if ($_SESSION['PRIVILEGIO_USUARIO'] != 1 && $_SESSION['PRIVILEGIO_USUARIO'] != 3 && $_SESSION['ID_USUARIO'] != 152 && $_SESSION['ID_USUARIO'] != 166 && $_SESSION['ID_USUARIO'] != 270 && $_SESSION['ID_USUARIO'] != 553) {
//    header('location:/swInventario/modulos/secciones/seccionGeneral');
//}

controlador();

function controlador() {
    $evento = '';
    $url = $_SERVER['REQUEST_URI'];

    $peticiones = array(vINDEX, vREGISTRAR, vDETALLE, vELIMINAR,vAPROBAR, GET_CLIENTE, SELECCIONAR_CLIENTE, GET_INFO_SERVICIO);

    foreach ($peticiones as $peticion) {
        $url_peticion = MODULO . $peticion;
        if (strpos($url, $url_peticion) == true) {
            $evento = $peticion;
        }
    }

    $CambiosTitularOBJ = new CambiosTitular();
    $datos = getDatos();

    switch ($evento) {
        case vINDEX:
            $CambiosTitularOBJ->getCambiosTitular();
            setTablaCambiosTitular($CambiosTitularOBJ->registros);

            if (array_key_exists('msg', $datos)) {
                $datos['mensaje'] = getMensaje($datos['msg']);
            } else {
                $datos['mensaje'] = $CambiosTitularOBJ->mensaje;
            }
            $datos['ordenar'] = 0;
            verVista($evento, $datos);
            break;
        case vREGISTRAR:
            echo verVistaAjax($evento, $datos);
            break;
        case vDETALLE:
            if (array_key_exists('idCambioTitular', $datos)) {
                $CambiosTitularOBJ->getCambioTitular($datos['idCambioTitular']);
                $datos = $CambiosTitularOBJ->registros[0];
            }
            echo verVistaAjax($evento, $datos);
            break;
        case vELIMINAR:
            if (array_key_exists('idCambioTitular', $datos)) {
                $CambiosTitularOBJ->getEliminarTitular($datos['idCambioTitular']);
                $datos = $CambiosTitularOBJ->registros[0];
            }
            echo verVistaAjax($evento, $datos);
            break;
        case vAPROBAR:
            if (array_key_exists('idCambioTitular', $datos)) {
                $CambiosTitularOBJ->getAprobarTitular($datos['idCambioTitular']);
                $datos = $CambiosTitularOBJ->registros[0];
            }
            echo verVistaAjax($evento, $datos);
            break;
        case GET_CLIENTE:
            if (array_key_exists('tipoClienteBusq', $datos) && array_key_exists('buscarPor', $datos) && array_key_exists('busqueda', $datos)) {
                $buscarPor = intval($datos['buscarPor']);
                $busqueda = trim($datos['busqueda']);
                switch ($buscarPor) {
                    case 1: // BUSQUEDA POR NOMBRES
                        $filtro = "WHERE residencial.nombres LIKE '%$busqueda%'";
                        break;
                    case 2: // APELLIDOS
                        $filtro = "WHERE residencial.apellidos LIKE '%$busqueda%'";
                        break;
                    case 3: // IDENTIFICACION
                        $filtro = "WHERE residencial.cedula LIKE '%$busqueda%'";
                        break;
                }
                $CambiosTitularOBJ->getClienteRES($filtro);
                $datos['tablaClientes'] = setTablaClientes($CambiosTitularOBJ->registros);
            }
            echo verVistaAjax($evento, $datos);
            break;
        case SELECCIONAR_CLIENTE:
            $infoCliente = array();
            if (array_key_exists('idCliente', $datos)) {
                $idCliente = $datos['idCliente'];
                $filtro = "WHERE residencial.idResidencial = $idCliente LIMIT 1";
                $CambiosTitularOBJ->getClienteRES($filtro);
                $infoCliente = $CambiosTitularOBJ->registros[0];
                $CambiosTitularOBJ->getServiciosRES($idCliente);
                $infoCliente['listaServicios'] = setListaServicios($CambiosTitularOBJ->registros);
            }
            echo verVistaAjax($evento, $infoCliente);
            break;
        case GET_INFO_SERVICIO:
            $info = array(
                'html' => '',
                'idServicio' => 0,
            );
            if (array_key_exists('idServicio', $datos)) {
                $CambiosTitularOBJ->getInfoServicio($datos['idServicio']);
                $infoServicio = $CambiosTitularOBJ->registros[0];
                $info['html'] = verVistaAjax($evento, $infoServicio);
                $info['idServicio'] = $datos['idServicio'];
            }
            echo json_encode($info);
            break;
    }
}

function getMensaje($msg = 0) {
    $mensaje = "<script>
                    $(document).ready(function(){
                      setTimeout(function(){ $('.mensajes').fadeOut(1000).fadeIn(1000).fadeOut(700).fadeIn(700).fadeOut(1000);}, 5000); 
                    });
                </script>";
    switch ($msg) {
        case 0:
            $mensaje .= '<div class="mensajes error">
                            <b>[ ERROR ]</b> -- La operacion solicitada <b>NO</b> fue realizada.<br>
                            Comuniquese con el Administrador del Sistema.
                        </div>';
            break;
        case 1:
            $mensaje .= '<div class="mensajes exito">
                            <b>[ OK ]</b> -- Devolucion REGISTRADA en el Sistema.
                         </div>';
            break;
    }
    return $mensaje;
}

function getDatos() {
    $datos = array();
    if ($_POST) {
        if (array_key_exists('nombres', $_POST))
            $datos['nombres'] = $_POST['nombres'];
        if (array_key_exists('apellidos', $_POST))
            $datos['apellidos'] = $_POST['apellidos'];
    } else if ($_GET) {
        if (array_key_exists('msg', $_GET))
            $datos['msg'] = $_GET['msg'];
        if (array_key_exists('tipoClienteBusq', $_GET))
            $datos['tipoClienteBusq'] = $_GET['tipoClienteBusq'];
        if (array_key_exists('buscarPor', $_GET))
            $datos['buscarPor'] = $_GET['buscarPor'];
        if (array_key_exists('busqueda', $_GET))
            $datos['busqueda'] = $_GET['busqueda'];
        if (array_key_exists('idCliente', $_GET))
            $datos['idCliente'] = $_GET['idCliente'];
        if (array_key_exists('idServicio', $_GET))
            $datos['idServicio'] = $_GET['idServicio'];
        if (array_key_exists('identificacion', $_GET))
            $datos['identificacion'] = $_GET['identificacion'];
        if (array_key_exists('idCambioTitular', $_GET))
            $datos['idCambioTitular'] = $_GET['idCambioTitular'];
        if (array_key_exists('identificacionNuevo', $_GET))
            $datos['identificacionNuevo'] = $_GET['identificacionNuevo'];
    }
    return $datos;
}

?>
