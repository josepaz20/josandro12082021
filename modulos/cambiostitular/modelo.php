<?php

// ********************** MODULO CAMBIOS TITULAR **********************

require_once('../../servicios/accesoDatos.php');

date_default_timezone_set('America/Bogota');

class CambiosTitular extends AccesoDatos {

    /**
     * Obtiene la informacion de todos los EMPLEADOS (TECNICOS) registradas en el sistema. 
     *
     * @return boolean true: si encuentra registros, en caso contrario false.
     */
    public function getCambiosTitular($filtro = '') {
        $this->consulta = "SELECT 
                            cambio_titular.idCambioTitular,
                            cambio_titular.idNuevoTitular,
                            cambio_titular.idServicio,
                            cambio_titular.estado,
                            cambio_titular.registradopor,
                            cambio_titular.fechahorareg,
                            servicio.conceptoFacturacion,
                            CONCAT(residencial.nombres, ' ', residencial.apellidos) AS clienteAntiguo, 
                            residencial.cedula AS identificacionAntiguo,
                            CONCAT(nuevo_titular.nombres, ' ', nuevo_titular.apellidos) AS clienteNuevo, 
                            nuevo_titular.identificacion AS identificacionNuevo
                           FROM cambio_titular
                           INNER JOIN servicio ON cambio_titular.idServicio = servicio.idServicio
                           INNER JOIN contrato ON servicio.idContrato = contrato.idContrato
                           INNER JOIN residencial ON contrato.idResidencial = residencial.idResidencial
                           INNER JOIN nuevo_titular ON cambio_titular.idNuevoTitular = nuevo_titular.idNuevoTitular
                           ";
        if ($filtro != '') {
            $this->consulta .= ' ' . $filtro;
        }
//        echo $this->consulta;
        $numRegistros = $this->consultarBD();
        $this->mensaje = "Registros Encontrados: <b>$numRegistros</b>";
        if ($numRegistros > 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------    
public function getCambioTitular($idCambioTitular =0) {
        $this->consulta = "SELECT 
                            cambio_titular.idCambioTitular,
                            cambio_titular.idNuevoTitular,
                            cambio_titular.idServicio,
                            cambio_titular.estado,
                            cambio_titular.registradopor,
                            cambio_titular.fechahorareg,
                            servicio.conceptoFacturacion,
                            CONCAT(residencial.nombres, ' ', residencial.apellidos) AS clienteAntiguo, 
                            residencial.cedula AS identificacionAntiguo,
                            CONCAT(nuevo_titular.nombres, ' ', nuevo_titular.apellidos) AS clienteNuevo, 
                            nuevo_titular.identificacion AS identificacionNuevo
                           FROM cambio_titular
                           INNER JOIN servicio ON cambio_titular.idServicio = servicio.idServicio
                           INNER JOIN contrato ON servicio.idContrato = contrato.idContrato
                           INNER JOIN residencial ON contrato.idResidencial = residencial.idResidencial
                           INNER JOIN nuevo_titular ON cambio_titular.idNuevoTitular = nuevo_titular.idNuevoTitular WHERE cambio_titular.idCambioTitular = $idCambioTitular";
//        echo $this->consulta;   
        
//        echo $this->consulta;
       $this->consultarBD();
            return $this->registros;
        
    }
//------------------------------------------------------------------------------
     public function getEliminarTitular($idCambioTitular =0) {
        $this->consulta = "SELECT 
                            cambio_titular.idCambioTitular,
                            cambio_titular.idNuevoTitular,
                            cambio_titular.idServicio,
                            cambio_titular.estado,
                            cambio_titular.registradopor,
                            cambio_titular.fechahorareg,
                            servicio.conceptoFacturacion,
                            CONCAT(residencial.nombres, ' ', residencial.apellidos) AS clienteAntiguo, 
                            residencial.cedula AS identificacionAntiguo,
                            CONCAT(nuevo_titular.nombres, ' ', nuevo_titular.apellidos) AS clienteNuevo, 
                            nuevo_titular.identificacion AS identificacionNuevo
                            FROM cambio_titular
                            INNER JOIN servicio ON cambio_titular.idServicio = servicio.idServicio
                            INNER JOIN contrato ON servicio.idContrato = contrato.idContrato
                            INNER JOIN residencial ON contrato.idResidencial = residencial.idResidencial
                            INNER JOIN nuevo_titular ON cambio_titular.idNuevoTitular = nuevo_titular.idNuevoTitular WHERE cambio_titular.idCambioTitular = $idCambioTitular";
//        echo $this->consulta;   
        
//        echo $this->consulta;
       $this->consultarBD();
            return $this->registros;
        
    }
    
    //------------------------------------------------------------------------------
     public function getAprobarTitular($idCambioTitular =0) {
        $this->consulta = "SELECT 
                            cambio_titular.idCambioTitular,
                            cambio_titular.idNuevoTitular,
                            cambio_titular.idServicio,
                            cambio_titular.estado,
                            cambio_titular.registradopor,
                            cambio_titular.fechahorareg,
                            servicio.conceptoFacturacion,
                            CONCAT(residencial.nombres, ' ', residencial.apellidos) AS clienteAntiguo, 
                            residencial.cedula AS identificacionAntiguo,
                            CONCAT(nuevo_titular.nombres, ' ', nuevo_titular.apellidos) AS clienteNuevo, 
                            nuevo_titular.identificacion AS identificacionNuevo
                            FROM cambio_titular
                            INNER JOIN servicio ON cambio_titular.idServicio = servicio.idServicio
                            INNER JOIN contrato ON servicio.idContrato = contrato.idContrato
                            INNER JOIN residencial ON contrato.idResidencial = residencial.idResidencial
                            INNER JOIN nuevo_titular ON cambio_titular.idNuevoTitular = nuevo_titular.idNuevoTitular WHERE cambio_titular.idCambioTitular = $idCambioTitular";
//        echo $this->consulta;   
        
//        echo $this->consulta;
       $this->consultarBD();
            return $this->registros;
        
    }
    public function getClienteRES($filtro = '') {
        $this->consulta = "SELECT 
                            residencial.idResidencial AS idCliente,
                            CONCAT(residencial.nombres, ' ', residencial.apellidos) AS cliente,
                            residencial.nombres,
                            residencial.apellidos,
                            residencial.cedula AS identificacion
                           FROM residencial";
        if ($filtro != '') {
            $this->consulta .= ' ' . $filtro;
        }
//        echo $this->consulta;
        $numRegistros = $this->consultarBD();
        if ($numRegistros > 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------    

    public function getServiciosRES($idResidencial = 0) {
        $this->consulta = "SELECT 
                            servicio.idServicio,
                            servicio.conceptoFacturacion
                           FROM servicio
                           INNER JOIN contrato ON servicio.idContrato = contrato.idContrato
                           INNER JOIN residencial ON contrato.idResidencial = residencial.idResidencial
                           WHERE servicio.estado != 'Eliminado' AND residencial.idResidencial = $idResidencial";
//        echo $this->consulta;
        $numRegistros = $this->consultarBD();
        if ($numRegistros > 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------    

    public function getInfoServicio($idServicio = 0) {
        $this->consulta = "SELECT 
                            servicio.idServicio,
                            servicio.conceptoFacturacion,
                            servicio.dirInstalacion,
                            servicio.estado,
                            CONCAT(departamento.nombreDpto, '-', municipio.nombreMcpo) AS ubicacion,
                            plan_internet.totalPago AS tarifa
                           FROM servicio
                           INNER JOIN internet ON servicio.idServicio = internet.idServicio
                           INNER JOIN plan_internet ON internet.idPlanInternet = plan_internet.idPlanInternet
                           INNER JOIN municipio ON servicio.idMcpo = municipio.idMcpo
                           INNER JOIN departamento ON municipio.idDpto = departamento.idDpto
                           WHERE servicio.idServicio = $idServicio LIMIT 1";
//        echo $this->consulta;
        $numRegistros = $this->consultarBD();
        if ($numRegistros > 0) {
            return true;
        } else {
            return false;
        }
    }

//------------------------------------------------------------------------------    
}

?>
